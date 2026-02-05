# Design Document: Azure PHP CI/CD Portal

## Overview

This design document describes the architecture and implementation approach for a PHP-based static portal showcasing a Post Graduate Course in Cloud Computing. The system uses Azure App Service (free tier) for hosting, GitHub Actions for CI/CD automation, Terraform for infrastructure as code, and Atlantis for Terraform workflow automation.

The portal serves static content through PHP templates without requiring a database, making it lightweight and suitable for the Azure free tier. The entire deployment pipeline is automated through GitHub Actions, with infrastructure changes managed through Atlantis-automated Terraform workflows.

## Architecture

### High-Level Architecture

```
┌─────────────────┐
│   Developer     │
│   Local Dev     │
└────────┬────────┘
         │
         │ git push
         ▼
┌─────────────────────────────────────────────────────────┐
│              GitHub Repository (Public)                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐ │
│  │  PHP Source  │  │  Terraform   │  │   GitHub     │ │
│  │    Files     │  │    Config    │  │   Actions    │ │
│  └──────────────┘  └──────────────┘  └──────────────┘ │
└───────────┬─────────────────┬───────────────┬──────────┘
            │                 │               │
            │                 │               │
    ┌───────▼────────┐  ┌────▼─────┐  ┌─────▼──────┐
    │  GitHub        │  │ Atlantis │  │  GitHub    │
    │  Actions       │  │ (Terraform│  │  Actions   │
    │  (Validation)  │  │  PR Auto) │  │  (Deploy)  │
    └───────┬────────┘  └────┬─────┘  └─────┬──────┘
            │                │               │
            │                │               │
            │         ┌──────▼──────┐        │
            │         │   Azure     │        │
            │         │  Resources  │◄───────┘
            │         │  (Terraform)│
            │         └─────────────┘
            │                │
            └────────────────┼────────────────┐
                             │                │
                      ┌──────▼──────────┐     │
                      │  Azure App      │     │
                      │  Service (F1)   │◄────┘
                      │  PHP Runtime    │
                      └─────────────────┘
                             │
                             ▼
                      ┌─────────────┐
                      │  End Users  │
                      │  (Browser)  │
                      └─────────────┘
```

### Component Interaction Flow

1. **Development Flow:**
   - Developer writes PHP code and Terraform configurations locally
   - Code is committed and pushed to GitHub public repository
   - GitHub Actions triggers automatically on push/PR

2. **Infrastructure Flow (Terraform + Atlantis):**
   - Pull request with Terraform changes triggers Atlantis
   - Atlantis runs `terraform plan` and posts results as PR comment
   - After approval and merge, Atlantis runs `terraform apply`
   - Azure resources are created/updated via Terraform

3. **Application Deployment Flow:**
   - GitHub Actions validates PHP syntax
   - GitHub Actions deploys PHP files to Azure App Service
   - Azure App Service serves the portal to end users

## Components and Interfaces

### 1. PHP Portal Application

**Structure:**
```
/
├── public/
│   ├── index.php              # Home page
│   ├── curriculum.php         # Curriculum page
│   ├── faculty.php            # Faculty page
│   ├── admissions.php         # Admissions page
│   ├── contact.php            # Contact page
│   └── assets/
│       ├── css/
│       │   └── style.css      # Main stylesheet
│       ├── js/
│       │   └── main.js        # JavaScript (if needed)
│       └── images/
│           └── logo.png       # Course logo
├── includes/
│   ├── header.php             # Common header
│   ├── footer.php             # Common footer
│   ├── navigation.php         # Navigation menu
│   └── config.php             # Configuration constants
└── composer.json              # PHP dependencies (if any)
```

**Key Components:**

- **Header Component** (`includes/header.php`):
  - HTML5 doctype and meta tags
  - Responsive viewport configuration
  - CSS stylesheet links
  - Common page header with logo and title

- **Navigation Component** (`includes/navigation.php`):
  - Responsive navigation menu
  - Active page highlighting
  - Mobile-friendly hamburger menu

- **Footer Component** (`includes/footer.php`):
  - Copyright information
  - Contact links
  - Social media links (optional)

- **Page Templates** (`public/*.php`):
  - Include header, navigation, and footer
  - Page-specific content in main section
  - Semantic HTML5 markup

**Configuration** (`includes/config.php`):
```php
<?php
// Site configuration
define('SITE_NAME', 'Post Graduate Course in Cloud Computing');
define('SITE_URL', 'https://your-app.azurewebsites.net');
define('CONTACT_EMAIL', 'info@example.com');

// Course information
define('COURSE_DURATION', '2 years');
define('COURSE_START_DATE', 'September 2024');
```

### 2. Azure Infrastructure (Terraform)

**Directory Structure:**
```
terraform/
├── main.tf                    # Main Terraform configuration
├── variables.tf               # Input variables
├── outputs.tf                 # Output values
├── providers.tf               # Provider configuration
├── terraform.tfvars.example   # Example variable values
└── backend.tf                 # Remote state configuration (optional)
```

**Resources Defined:**

1. **Resource Group:**
   - Container for all Azure resources
   - Configurable location/region

2. **App Service Plan:**
   - SKU: F1 (Free tier)
   - OS: Linux
   - Reserved: true (for Linux)

3. **App Service:**
   - Runtime: PHP 8.0+
   - Always On: false (not available in F1)
   - HTTPS Only: true
   - Site configuration for PHP

**Terraform Configuration Example:**
```hcl
# Resource Group
resource "azurerm_resource_group" "main" {
  name     = var.resource_group_name
  location = var.location
}

# App Service Plan (Free Tier)
resource "azurerm_service_plan" "main" {
  name                = var.app_service_plan_name
  location            = azurerm_resource_group.main.location
  resource_group_name = azurerm_resource_group.main.name
  os_type             = "Linux"
  sku_name            = "F1"
}

# App Service
resource "azurerm_linux_web_app" "main" {
  name                = var.app_name
  location            = azurerm_resource_group.main.location
  resource_group_name = azurerm_resource_group.main.name
  service_plan_id     = azurerm_service_plan.main.id

  site_config {
    application_stack {
      php_version = "8.0"
    }
    always_on = false
  }

  https_only = true
}
```

**Variables:**
- `resource_group_name`: Name of the Azure resource group
- `location`: Azure region (e.g., "East US", "West Europe")
- `app_service_plan_name`: Name of the App Service Plan
- `app_name`: Name of the App Service (must be globally unique)

**Outputs:**
- `app_service_url`: The URL of the deployed application
- `app_service_name`: The name of the App Service
- `resource_group_name`: The name of the resource group

### 3. GitHub Actions CI/CD Pipeline

**Workflow File:** `.github/workflows/deploy.yml`

**Jobs:**

1. **Validate Job:**
   - Triggers on: push to main, pull requests
   - Steps:
     - Checkout code
     - Setup PHP 8.0+
     - Validate PHP syntax (`php -l`)
     - Run PHP linter (optional)
     - Check for security issues (optional)

2. **Deploy Job:**
   - Triggers on: push to main only
   - Depends on: Validate job success
   - Steps:
     - Checkout code
     - Setup PHP 8.0+
     - Login to Azure using service principal
     - Deploy to Azure App Service using Azure CLI or publish profile
     - Verify deployment with health check

**Workflow Configuration:**
```yaml
name: Deploy to Azure

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  validate:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.0'
      
      - name: Validate PHP syntax
        run: find public includes -name "*.php" -exec php -l {} \;
  
  deploy:
    needs: validate
    if: github.ref == 'refs/heads/main'
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Login to Azure
        uses: azure/login@v1
        with:
          creds: ${{ secrets.AZURE_CREDENTIALS }}
      
      - name: Deploy to Azure App Service
        uses: azure/webapps-deploy@v2
        with:
          app-name: ${{ secrets.AZURE_APP_NAME }}
          package: .
```

**Required GitHub Secrets:**
- `AZURE_CREDENTIALS`: Service principal credentials (JSON format)
- `AZURE_APP_NAME`: Name of the Azure App Service

### 4. Atlantis Configuration

**Configuration File:** `atlantis.yaml`

```yaml
version: 3
projects:
  - name: azure-infrastructure
    dir: terraform
    workspace: default
    terraform_version: v1.5.0
    autoplan:
      when_modified: ["*.tf", "*.tfvars"]
      enabled: true
    apply_requirements: ["approved", "mergeable"]
    workflow: default
```

**Atlantis Setup Options:**

1. **Self-Hosted (Local/VM):**
   - Run Atlantis server on local machine or VM
   - Configure webhook in GitHub repository
   - Requires public URL (use ngrok for local testing)

2. **Cloud-Hosted (Free Alternatives):**
   - Use GitHub Actions with Terraform
   - Use Terraform Cloud free tier
   - Use Spacelift free tier (if available)

**Atlantis Workflow:**
1. Developer creates PR with Terraform changes
2. Atlantis automatically runs `terraform plan`
3. Plan results posted as PR comment
4. Reviewer approves PR
5. Developer comments `atlantis apply` or merges PR
6. Atlantis runs `terraform apply`
7. Infrastructure updated in Azure

### 5. Authentication and Credentials

**Azure Service Principal:**
- Created via Azure CLI: `az ad sp create-for-rbac`
- Permissions: Contributor role on subscription or resource group
- Credentials stored as GitHub Secret

**Service Principal JSON Format:**
```json
{
  "clientId": "<client-id>",
  "clientSecret": "<client-secret>",
  "subscriptionId": "<subscription-id>",
  "tenantId": "<tenant-id>"
}
```

**Terraform Authentication:**
- Uses service principal via environment variables
- Set in Atlantis configuration or GitHub Actions

## Data Models

### Portal Content Structure

Since the portal is static, content is embedded directly in PHP templates. However, we define a logical content structure:

**Course Information:**
```php
$course_info = [
    'name' => 'Post Graduate Course in Cloud Computing',
    'duration' => '2 years',
    'start_date' => 'September 2024',
    'mode' => 'Full-time / Part-time',
    'description' => 'Comprehensive program covering cloud architecture, DevOps, and modern cloud platforms'
];
```

**Curriculum Modules:**
```php
$curriculum = [
    [
        'module' => 'Cloud Fundamentals',
        'topics' => ['Cloud Computing Concepts', 'Service Models (IaaS, PaaS, SaaS)', 'Deployment Models'],
        'credits' => 6
    ],
    [
        'module' => 'Cloud Architecture',
        'topics' => ['Design Patterns', 'Scalability', 'High Availability', 'Disaster Recovery'],
        'credits' => 6
    ],
    [
        'module' => 'DevOps and CI/CD',
        'topics' => ['Version Control', 'Continuous Integration', 'Continuous Deployment', 'Infrastructure as Code'],
        'credits' => 6
    ],
    [
        'module' => 'Cloud Platforms',
        'topics' => ['AWS', 'Azure', 'Google Cloud Platform', 'Multi-cloud Strategies'],
        'credits' => 8
    ],
    [
        'module' => 'Cloud Security',
        'topics' => ['Identity and Access Management', 'Encryption', 'Compliance', 'Security Best Practices'],
        'credits' => 6
    ],
    [
        'module' => 'Capstone Project',
        'topics' => ['Real-world Cloud Implementation', 'Project Management', 'Presentation'],
        'credits' => 8
    ]
];
```

**Faculty Information:**
```php
$faculty = [
    [
        'name' => 'Dr. Jane Smith',
        'title' => 'Professor of Cloud Computing',
        'credentials' => 'PhD in Computer Science, AWS Certified Solutions Architect',
        'specialization' => 'Cloud Architecture and Distributed Systems',
        'image' => 'assets/images/faculty/jane-smith.jpg'
    ],
    [
        'name' => 'Prof. John Doe',
        'title' => 'Associate Professor',
        'credentials' => 'MS in Software Engineering, Azure Solutions Architect Expert',
        'specialization' => 'DevOps and Infrastructure Automation',
        'image' => 'assets/images/faculty/john-doe.jpg'
    ]
];
```

**Contact Form Data:**
```php
// Contact form fields (not processed, just displayed)
$contact_form = [
    'fields' => [
        ['name' => 'full_name', 'type' => 'text', 'label' => 'Full Name', 'required' => true],
        ['name' => 'email', 'type' => 'email', 'label' => 'Email Address', 'required' => true],
        ['name' => 'phone', 'type' => 'tel', 'label' => 'Phone Number', 'required' => false],
        ['name' => 'message', 'type' => 'textarea', 'label' => 'Your Message', 'required' => true]
    ],
    'submit_note' => 'Note: This is a demo form. For actual inquiries, please email info@example.com'
];
```

## Correctness Properties


*A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Consistent Navigation and Branding Across Pages

*For any* page in the portal, the navigation menu and branding elements (header, logo, site name) should be present and consistent in structure and styling.

**Validates: Requirements 1.6**

### Property 2: Responsive Design Across Viewport Sizes

*For any* page in the portal and any viewport width (desktop: ≥1024px, tablet: 768-1023px, mobile: <768px), the page should render properly with appropriate layout adjustments and no horizontal scrolling.

**Validates: Requirements 1.7**

### Property 3: HTML5 Semantic Markup and Accessibility

*For any* page in the portal, the HTML output should be valid HTML5 with proper semantic elements (header, nav, main, footer, article, section) and include accessibility attributes (alt text for images, ARIA labels where appropriate, proper heading hierarchy).

**Validates: Requirements 2.6**

### Property 4: No Exposed Credentials in Source Code

*For any* file in the repository (PHP, YAML, Terraform, configuration files), the file should not contain hardcoded credentials, API keys, passwords, or sensitive tokens. All sensitive values should be referenced through environment variables, GitHub Secrets, or Azure Key Vault.

**Validates: Requirements 9.1**

### Property 5: Required Documentation Sections Present

*For all* required documentation sections (Azure setup, service principal creation, GitHub secrets configuration, Atlantis setup, local development, troubleshooting, architecture diagrams, log access via Portal, log access via CLI), the documentation should include that section with step-by-step instructions.

**Validates: Requirements 7.1, 7.2, 7.3, 7.4, 7.5, 7.6, 7.7, 10.4, 10.5**

## Error Handling

### PHP Application Errors

**Error Display:**
- Production environment: Display user-friendly error messages
- Development environment: Display detailed error information
- All errors logged to Azure App Service logs

**Common Error Scenarios:**
1. **Missing Include Files:**
   - Error: PHP include/require fails
   - Handling: Log error, display "Page temporarily unavailable" message
   - Prevention: Validate all include paths during deployment

2. **Invalid Configuration:**
   - Error: Missing or invalid configuration constants
   - Handling: Use default values where possible, log warning
   - Prevention: Validate configuration during deployment

3. **Asset Loading Failures:**
   - Error: CSS/JS/image files not found (404)
   - Handling: Graceful degradation (page still functional without styling)
   - Prevention: Validate asset paths during deployment

**PHP Error Configuration:**
```php
// Production settings
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', '/home/LogFiles/php_errors.log');
error_reporting(E_ALL);
```

### Azure Deployment Errors

**Common Deployment Failures:**

1. **Authentication Failures:**
   - Cause: Invalid or expired service principal credentials
   - Detection: GitHub Actions fails at Azure login step
   - Resolution: Regenerate service principal credentials, update GitHub Secrets
   - Prevention: Document credential rotation schedule

2. **Resource Quota Exceeded:**
   - Cause: Free tier limits reached (CPU time, bandwidth, storage)
   - Detection: Azure returns quota exceeded error
   - Resolution: Wait for quota reset or upgrade to paid tier
   - Prevention: Monitor usage through Azure Portal

3. **App Service Name Conflict:**
   - Cause: App Service name already taken (must be globally unique)
   - Detection: Terraform apply fails with name conflict error
   - Resolution: Choose different app name in variables
   - Prevention: Use naming convention with unique suffix

4. **Invalid Terraform Configuration:**
   - Cause: Syntax errors or invalid resource configuration
   - Detection: Terraform plan/apply fails
   - Resolution: Fix Terraform configuration based on error message
   - Prevention: Use `terraform validate` in CI pipeline

### GitHub Actions Errors

**Workflow Failure Handling:**

1. **PHP Syntax Errors:**
   - Detection: `php -l` command fails
   - Action: Block deployment, notify via GitHub status check
   - Resolution: Fix PHP syntax errors in code

2. **Deployment Timeout:**
   - Detection: Azure deployment step exceeds timeout
   - Action: Fail workflow, preserve logs
   - Resolution: Investigate Azure service health, retry deployment

3. **Secret Not Found:**
   - Detection: Workflow references undefined secret
   - Action: Fail workflow with clear error message
   - Resolution: Add missing secret to GitHub repository settings

**Error Notification:**
- GitHub Actions automatically notifies on workflow failure
- Failed workflows visible in GitHub Actions tab
- Email notifications configurable per user

### Atlantis Errors

**Terraform Workflow Errors:**

1. **Plan Failures:**
   - Cause: Invalid Terraform syntax or configuration
   - Detection: `terraform plan` command fails
   - Action: Post error details as PR comment
   - Resolution: Fix Terraform configuration, push update

2. **Apply Failures:**
   - Cause: Azure API errors, resource conflicts, permission issues
   - Detection: `terraform apply` command fails
   - Action: Post error details as PR comment, do not merge
   - Resolution: Investigate Azure error, fix configuration

3. **State Lock Conflicts:**
   - Cause: Concurrent Terraform operations
   - Detection: Terraform state lock acquisition fails
   - Action: Wait for lock release or force unlock (with caution)
   - Resolution: Coordinate Terraform operations, avoid concurrent PRs

**Atlantis Error Recovery:**
- Failed plans can be re-run with `atlantis plan` comment
- Failed applies require fixing the issue and re-running
- State locks can be manually released if needed

## Testing Strategy

### Overview

The testing strategy employs a dual approach combining unit tests for specific scenarios and property-based tests for universal correctness guarantees. This comprehensive approach ensures both concrete functionality and general system properties are validated.

**Testing Layers:**
1. **Unit Tests**: Validate specific examples, edge cases, and error conditions
2. **Property-Based Tests**: Verify universal properties across all inputs
3. **Integration Tests**: Validate end-to-end workflows and component interactions
4. **Infrastructure Tests**: Validate Terraform configurations and Azure deployments

### Unit Testing

**PHP Application Tests:**

Test files located in `tests/unit/`:

1. **Page Rendering Tests:**
   - Test each page loads without PHP errors
   - Test include files are properly loaded
   - Test configuration values are accessible
   - Test page-specific content is present

2. **Component Tests:**
   - Test header component renders correctly
   - Test navigation component generates proper menu
   - Test footer component includes required elements
   - Test active page highlighting in navigation

3. **Configuration Tests:**
   - Test configuration constants are defined
   - Test default values are set correctly
   - Test configuration file is loadable

4. **Error Handling Tests:**
   - Test missing include file handling
   - Test invalid configuration handling
   - Test error logging functionality

**Example Unit Test (PHPUnit):**
```php
<?php
use PHPUnit\Framework\TestCase;

class HomePageTest extends TestCase
{
    public function testHomePageLoadsWithoutErrors()
    {
        ob_start();
        include __DIR__ . '/../../public/index.php';
        $output = ob_get_clean();
        
        $this->assertNotEmpty($output);
        $this->assertStringContainsString('Post Graduate Course', $output);
    }
    
    public function testNavigationIsPresent()
    {
        ob_start();
        include __DIR__ . '/../../public/index.php';
        $output = ob_get_clean();
        
        $this->assertStringContainsString('<nav', $output);
        $this->assertStringContainsString('Curriculum', $output);
        $this->assertStringContainsString('Faculty', $output);
    }
}
```

**Terraform Configuration Tests:**

Test files located in `tests/terraform/`:

1. **Terraform Validation:**
   - Test `terraform validate` passes
   - Test `terraform fmt -check` passes
   - Test required variables are defined
   - Test outputs are defined

2. **Resource Configuration Tests:**
   - Test App Service Plan uses F1 SKU
   - Test App Service uses PHP 8.0+ runtime
   - Test HTTPS is enforced
   - Test resource naming follows conventions

**Example Terraform Test (Terratest):**
```go
func TestTerraformAzureAppService(t *testing.T) {
    terraformOptions := &terraform.Options{
        TerraformDir: "../../terraform",
        Vars: map[string]interface{}{
            "app_name": "test-app-" + random.UniqueId(),
            "location": "East US",
        },
    }
    
    defer terraform.Destroy(t, terraformOptions)
    terraform.InitAndPlan(t, terraformOptions)
    
    // Verify plan includes correct resources
    planStruct := terraform.InitAndPlanAndShow(t, terraformOptions)
    // Add assertions here
}
```

**GitHub Actions Workflow Tests:**

Test files located in `tests/workflows/`:

1. **Workflow Syntax Tests:**
   - Test YAML syntax is valid
   - Test required jobs are defined
   - Test job dependencies are correct
   - Test secrets are referenced properly

2. **Workflow Logic Tests:**
   - Test validation runs on all branches
   - Test deployment only runs on main branch
   - Test deployment depends on validation success

### Property-Based Testing

**Configuration:** Each property test runs a minimum of 100 iterations with randomized inputs.

**Test Framework:** Use appropriate property-based testing library for the language:
- PHP: PHPUnit with data providers or Eris library
- Python: Hypothesis
- Go: gopter

**Property Test Implementation:**

Each correctness property from the design document must be implemented as a property-based test with the following tag format:

```php
/**
 * Feature: azure-php-cicd-portal, Property 1: Consistent Navigation and Branding Across Pages
 * 
 * @test
 * @dataProvider pageProvider
 */
public function testConsistentNavigationAcrossPages($pagePath)
{
    // Generate random page or use all pages
    // Load page
    // Assert navigation elements present
    // Assert branding elements present
    // Assert structure is consistent
}
```

**Property Tests to Implement:**

1. **Property 1: Consistent Navigation and Branding**
   - Generator: All portal pages
   - Test: Each page contains navigation and branding elements
   - Tag: `Feature: azure-php-cicd-portal, Property 1: Consistent Navigation and Branding Across Pages`

2. **Property 2: Responsive Design**
   - Generator: All pages × viewport widths (320px, 768px, 1024px, 1920px)
   - Test: Page renders without horizontal scroll, layout adjusts appropriately
   - Tag: `Feature: azure-php-cicd-portal, Property 2: Responsive Design Across Viewport Sizes`

3. **Property 3: HTML5 Semantic Markup**
   - Generator: All portal pages
   - Test: HTML validates, contains semantic elements, has accessibility attributes
   - Tag: `Feature: azure-php-cicd-portal, Property 3: HTML5 Semantic Markup and Accessibility`

4. **Property 4: No Exposed Credentials**
   - Generator: All files in repository
   - Test: File content doesn't match credential patterns (API keys, passwords, tokens)
   - Tag: `Feature: azure-php-cicd-portal, Property 4: No Exposed Credentials in Source Code`

5. **Property 5: Required Documentation Sections**
   - Generator: List of required documentation sections
   - Test: Documentation file contains each required section
   - Tag: `Feature: azure-php-cicd-portal, Property 5: Required Documentation Sections Present`

### Integration Testing

**End-to-End Workflow Tests:**

1. **Local Development Workflow:**
   - Clone repository
   - Install dependencies
   - Run local PHP server
   - Access all pages
   - Verify functionality

2. **CI/CD Pipeline Workflow:**
   - Create feature branch
   - Make code changes
   - Push to GitHub
   - Verify validation runs
   - Merge to main
   - Verify deployment runs
   - Verify application accessible

3. **Infrastructure Workflow:**
   - Create Terraform changes in branch
   - Create pull request
   - Verify Atlantis runs plan
   - Review plan output
   - Approve and merge PR
   - Verify Atlantis applies changes
   - Verify resources created in Azure

**Integration Test Tools:**
- Selenium/Playwright for browser-based testing
- Azure CLI for infrastructure verification
- GitHub API for workflow verification

### Infrastructure Testing

**Terraform Testing Approach:**

1. **Static Analysis:**
   - `terraform validate`: Syntax and configuration validation
   - `terraform fmt -check`: Code formatting validation
   - `tflint`: Linting for best practices and errors
   - `checkov`: Security and compliance scanning

2. **Plan Testing:**
   - Run `terraform plan` in test environment
   - Verify expected resources in plan
   - Verify no unexpected changes
   - Verify resource configurations match requirements

3. **Apply Testing (Test Environment):**
   - Apply Terraform in isolated test environment
   - Verify resources created successfully
   - Verify resource configurations in Azure
   - Test application deployment to test environment
   - Destroy test environment after validation

**Azure Resource Validation:**

After deployment, validate:
- App Service exists and is running
- App Service Plan has correct SKU (F1)
- PHP runtime version is correct (8.0+)
- HTTPS is enforced
- Logging is enabled
- Application is accessible via URL

### Test Execution

**Local Testing:**
```bash
# PHP unit tests
composer install
vendor/bin/phpunit tests/unit

# Terraform validation
cd terraform
terraform init
terraform validate
terraform fmt -check

# Workflow validation
yamllint .github/workflows/*.yml
```

**CI Pipeline Testing:**
- All tests run automatically on push/PR
- Validation must pass before deployment
- Test results visible in GitHub Actions
- Failed tests block deployment

**Test Coverage Goals:**
- Unit test coverage: >80% of PHP code
- Property tests: All 5 properties implemented
- Integration tests: All critical workflows covered
- Infrastructure tests: All Terraform configurations validated

### Continuous Testing

**Automated Test Execution:**
- Unit tests run on every commit
- Property tests run on every commit
- Integration tests run on main branch merges
- Infrastructure tests run on Terraform changes

**Test Maintenance:**
- Review and update tests when requirements change
- Add tests for new features
- Remove obsolete tests
- Keep test execution time optimized (<5 minutes for full suite)
