# Deployment Guide: Azure PHP CI/CD Portal

## Overview

This guide provides a complete walkthrough for deploying the Azure PHP CI/CD Portal from scratch. Follow these steps in order to set up the infrastructure, configure CI/CD automation, and deploy the application to Azure App Service.

**Total Estimated Time:** 90-120 minutes

## Prerequisites Checklist

Before starting the deployment, ensure you have the following:

### Required Accounts
- [ ] **Azure Account** - Free tier account with active subscription
  - Sign up at: https://azure.microsoft.com/free/
  - Provides $200 credit for 30 days + 12 months of free services
  
- [ ] **GitHub Account** - Free account with public repository access
  - Sign up at: https://github.com/join
  - Public repositories get unlimited GitHub Actions minutes

### Required Tools
- [ ] **Azure CLI** - Command-line tool for Azure management
  - Installation: https://docs.microsoft.com/cli/azure/install-azure-cli
  - Verify: `az --version`
  
- [ ] **Terraform** - Infrastructure as Code tool (v1.5.0 or higher)
  - Installation: https://www.terraform.io/downloads
  - Verify: `terraform --version`
  
- [ ] **PHP 8.0+** - For local development and testing
  - Installation: https://www.php.net/downloads
  - Verify: `php --version`
  
- [ ] **Git** - Version control system
  - Installation: https://git-scm.com/downloads
  - Verify: `git --version`

### Optional Tools
- [ ] **Composer** - PHP dependency manager (for running tests)
- [ ] **Text Editor/IDE** - VS Code, PHPStorm, or similar

## Deployment Steps

### Step 1: Create Azure Service Principal (15 minutes)

The service principal provides authentication for GitHub Actions and Terraform to manage Azure resources.

1. **Login to Azure CLI:**
   ```bash
   az login
   ```
   This opens a browser window for authentication.

2. **Get your subscription ID:**
   ```bash
   az account show --query id --output tsv
   ```
   Save this ID - you'll need it in the next step.

3. **Create the service principal:**
   ```bash
   az ad sp create-for-rbac --name "github-actions-sp" \
     --role contributor \
     --scopes /subscriptions/{subscription-id} \
     --sdk-auth
   ```
   Replace `{subscription-id}` with your actual subscription ID.

4. **Save the JSON output:**
   The command returns JSON credentials like this:
   ```json
   {
     "clientId": "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     "clientSecret": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
     "subscriptionId": "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     "tenantId": "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
   }
   ```
   **IMPORTANT:** Save this entire JSON output securely. You'll need it for GitHub Secrets.

**Troubleshooting:**
- If you get a permissions error, ensure you have Owner or User Access Administrator role on the subscription
- See [docs/service-principal.md](service-principal.md) for detailed instructions

**Estimated Time:** 15 minutes

---

### Step 2: Configure GitHub Repository and Secrets (10 minutes)

Set up your GitHub repository and configure the secrets needed for CI/CD automation.

1. **Create a public GitHub repository:**
   - Go to https://github.com/new
   - Name: `azure-php-cicd-portal` (or your preferred name)
   - Visibility: **Public** (required for unlimited Actions minutes on free tier)
   - Initialize with README: No (we'll push existing code)
   - Click "Create repository"

2. **Add GitHub Secrets:**
   - Navigate to your repository on GitHub
   - Go to **Settings** → **Secrets and variables** → **Actions**
   - Click **New repository secret**

3. **Add AZURE_CREDENTIALS secret:**
   - Name: `AZURE_CREDENTIALS`
   - Value: Paste the entire JSON output from Step 1
   - Click **Add secret**

4. **Add AZURE_APP_NAME secret:**
   - Click **New repository secret**
   - Name: `AZURE_APP_NAME`
   - Value: Your desired app name (e.g., `my-cloud-course-portal`)
     - Must be globally unique across all Azure
     - Use lowercase letters, numbers, and hyphens only
     - Between 2-60 characters
   - Click **Add secret**

**Verification:**
- You should see both secrets listed in the Actions secrets page
- Secret values are hidden after creation (this is normal)

**Estimated Time:** 10 minutes

---

### Step 3: Deploy Infrastructure with Terraform (20-30 minutes)

Use Terraform to create the Azure infrastructure (Resource Group, App Service Plan, App Service).

#### 3.1 Initialize Terraform

1. **Navigate to the terraform directory:**
   ```bash
   cd terraform
   ```

2. **Create terraform.tfvars file:**
   Copy the example file and customize it:
   ```bash
   copy terraform.tfvars.example terraform.tfvars
   ```
   
   Edit `terraform.tfvars` with your values:
   ```hcl
   resource_group_name  = "rg-cloud-course-portal"
   location             = "East US"
   app_service_plan_name = "asp-cloud-course-portal"
   app_name             = "my-cloud-course-portal"  # Must match AZURE_APP_NAME secret
   ```

   **Important:** The `app_name` must match the value you set in the `AZURE_APP_NAME` GitHub secret.

3. **Initialize Terraform:**
   ```bash
   terraform init
   ```
   This downloads the Azure provider and prepares Terraform.

**Expected Output:**
```
Initializing the backend...
Initializing provider plugins...
- Finding hashicorp/azurerm versions matching "~> 3.0"...
- Installing hashicorp/azurerm v3.x.x...

Terraform has been successfully initialized!
```

#### 3.2 Plan the Deployment

1. **Run terraform plan:**
   ```bash
   terraform plan
   ```
   This shows what resources will be created without actually creating them.

2. **Review the plan output:**
   - Should show 3 resources to be created:
     - `azurerm_resource_group.main`
     - `azurerm_service_plan.main`
     - `azurerm_linux_web_app.main`
   - Verify the configurations match your requirements
   - Check that the App Service Plan uses SKU "F1" (free tier)

**Expected Output:**
```
Plan: 3 to add, 0 to change, 0 to destroy.

Changes to Outputs:
  + app_service_name = "my-cloud-course-portal"
  + app_service_url  = (known after apply)
  + resource_group_name = "rg-cloud-course-portal"
```

#### 3.3 Apply the Configuration

1. **Apply the Terraform configuration:**
   ```bash
   terraform apply
   ```
   Type `yes` when prompted to confirm.

2. **Wait for completion:**
   This typically takes 2-5 minutes. Terraform will create the resources in Azure.

**Expected Output:**
```
Apply complete! Resources: 3 added, 0 changed, 0 destroyed.

Outputs:
app_service_name = "my-cloud-course-portal"
app_service_url = "https://my-cloud-course-portal.azurewebsites.net"
resource_group_name = "rg-cloud-course-portal"
```

3. **Save the outputs:**
   Note the `app_service_url` - this is where your application will be accessible.

#### 3.4 Verify Resources in Azure Portal

1. **Login to Azure Portal:**
   Go to https://portal.azure.com

2. **Navigate to Resource Groups:**
   - Click "Resource groups" in the left menu
   - Find your resource group (e.g., `rg-cloud-course-portal`)
   - Click to open it

3. **Verify resources:**
   You should see:
   - App Service Plan (e.g., `asp-cloud-course-portal`)
   - App Service (e.g., `my-cloud-course-portal`)

4. **Check App Service configuration:**
   - Click on the App Service
   - Go to **Settings** → **Configuration**
   - Verify PHP version is 8.0 or higher
   - Go to **Settings** → **TLS/SSL settings**
   - Verify "HTTPS Only" is enabled

**Estimated Time:** 20-30 minutes

---

### Step 4: Push Code to Trigger CI/CD (15-20 minutes)

Push your code to GitHub to trigger the automated deployment pipeline.

#### 4.1 Initialize Git Repository (if not already done)

1. **Navigate to project root:**
   ```bash
   cd ..  # Go back to project root if you're in terraform/
   ```

2. **Initialize git (if needed):**
   ```bash
   git init
   ```

3. **Add remote repository:**
   ```bash
   git remote add origin https://github.com/YOUR_USERNAME/azure-php-cicd-portal.git
   ```
   Replace `YOUR_USERNAME` with your GitHub username.

#### 4.2 Commit and Push Code

1. **Add all files:**
   ```bash
   git add .
   ```

2. **Commit the code:**
   ```bash
   git commit -m "Initial commit: Azure PHP CI/CD Portal"
   ```

3. **Push to GitHub:**
   ```bash
   git push -u origin main
   ```
   
   If your default branch is `master`, use:
   ```bash
   git push -u origin master
   ```

#### 4.3 Monitor GitHub Actions Workflow

1. **Open GitHub Actions:**
   - Go to your repository on GitHub
   - Click the **Actions** tab

2. **View the workflow run:**
   - You should see a workflow run starting automatically
   - Click on the workflow run to see details

3. **Monitor the jobs:**
   - **Validate job:** Runs PHP syntax validation
   - **Deploy job:** Deploys code to Azure App Service (runs after validate succeeds)

4. **Wait for completion:**
   - Typical deployment time: 3-5 minutes
   - Green checkmark = success
   - Red X = failure (see troubleshooting section)

**Expected Workflow Steps:**
```
✓ Validate
  ✓ Checkout code
  ✓ Setup PHP
  ✓ Validate PHP syntax
  
✓ Deploy
  ✓ Checkout code
  ✓ Login to Azure
  ✓ Deploy to Azure App Service
  ✓ Verify deployment
```

**Estimated Time:** 15-20 minutes

---

### Step 5: Verify Deployment (5 minutes)

Confirm that your application is live and accessible.

1. **Access the application:**
   - Open your browser
   - Navigate to the URL from Terraform outputs (e.g., `https://my-cloud-course-portal.azurewebsites.net`)

2. **Test all pages:**
   - Home page: Should display course overview
   - Curriculum page: Should list course modules
   - Faculty page: Should show faculty profiles
   - Admissions page: Should display requirements
   - Contact page: Should show contact form

3. **Test responsive design:**
   - Resize browser window
   - Test on mobile device (or use browser dev tools)
   - Verify navigation menu adapts to screen size

4. **Check for errors:**
   - Open browser developer console (F12)
   - Look for any JavaScript or loading errors
   - All pages should load without errors

**Success Criteria:**
- ✓ All pages load successfully
- ✓ Navigation works correctly
- ✓ Content displays properly
- ✓ Responsive design functions on different screen sizes
- ✓ No console errors

**Estimated Time:** 5 minutes

---

## Post-Deployment: Updating the Application

Once deployed, you can update the application by pushing changes to GitHub.

### Making Updates

1. **Make code changes locally:**
   - Edit PHP files, CSS, or other content
   - Test locally: `php -S localhost:8000 -t public`

2. **Commit changes:**
   ```bash
   git add .
   git commit -m "Description of changes"
   ```

3. **Push to GitHub:**
   ```bash
   git push origin main
   ```

4. **Automatic deployment:**
   - GitHub Actions automatically triggers
   - Validates and deploys your changes
   - Typically completes in 3-5 minutes

### Updating Infrastructure

If you need to change infrastructure (e.g., change region, add resources):

1. **Edit Terraform files:**
   - Modify `terraform/main.tf` or `terraform/variables.tf`
   - Update `terraform/terraform.tfvars` if needed

2. **Plan the changes:**
   ```bash
   cd terraform
   terraform plan
   ```

3. **Apply the changes:**
   ```bash
   terraform apply
   ```

4. **Verify in Azure Portal:**
   - Check that resources updated correctly

---

## Infrastructure Management with Terraform

This section provides detailed instructions for managing your Azure infrastructure using Terraform.

### Terraform Workflow Overview

Terraform uses a declarative approach to infrastructure management:
1. **Write** - Define infrastructure in `.tf` files
2. **Plan** - Preview changes before applying
3. **Apply** - Create or update infrastructure
4. **Destroy** - Remove infrastructure when no longer needed

### Initializing Terraform

Before using Terraform, you must initialize the working directory:

```bash
cd terraform
terraform init
```

**What this does:**
- Downloads the Azure provider plugin
- Initializes the backend for state storage
- Prepares the working directory for other Terraform commands

**When to run:**
- First time using Terraform in this directory
- After adding new providers
- After cloning the repository on a new machine

### Creating terraform.tfvars

The `terraform.tfvars` file contains your specific configuration values:

1. **Copy the example file:**
   ```bash
   copy terraform.tfvars.example terraform.tfvars
   ```

2. **Edit terraform.tfvars:**
   ```hcl
   # Azure Resource Group
   resource_group_name = "rg-cloud-course-portal"
   
   # Azure Region (choose one close to your users)
   # Options: "East US", "West US", "West Europe", "Southeast Asia", etc.
   location = "East US"
   
   # App Service Plan name
   app_service_plan_name = "asp-cloud-course-portal"
   
   # App Service name (must be globally unique)
   # Use lowercase, numbers, and hyphens only
   app_name = "my-cloud-course-portal-12345"
   ```

**Important Notes:**
- `app_name` must be globally unique across all Azure
- `app_name` must match the `AZURE_APP_NAME` GitHub secret
- Choose a `location` close to your target audience for better performance

### Planning Deployment

Always run `terraform plan` before applying changes:

```bash
terraform plan
```

**What this does:**
- Compares current state with desired state
- Shows what resources will be created, modified, or destroyed
- Validates configuration syntax
- Does NOT make any actual changes

**Review the output:**
```
Terraform will perform the following actions:

  # azurerm_resource_group.main will be created
  + resource "azurerm_resource_group" "main" {
      + id       = (known after apply)
      + location = "eastus"
      + name     = "rg-cloud-course-portal"
    }

  # azurerm_service_plan.main will be created
  + resource "azurerm_service_plan" "main" {
      + id                = (known after apply)
      + location          = "eastus"
      + name              = "asp-cloud-course-portal"
      + os_type           = "Linux"
      + sku_name          = "F1"
      ...
    }

  # azurerm_linux_web_app.main will be created
  + resource "azurerm_linux_web_app" "main" {
      + id                = (known after apply)
      + name              = "my-cloud-course-portal"
      + https_only        = true
      ...
    }

Plan: 3 to add, 0 to change, 0 to destroy.
```

**Verify:**
- Number of resources matches expectations (3 for initial deployment)
- Resource names are correct
- SKU is "F1" for free tier
- No unexpected deletions

### Applying Deployment

After reviewing the plan, apply the configuration:

```bash
terraform apply
```

**Interactive approval:**
- Terraform shows the plan again
- Type `yes` to confirm and proceed
- Type `no` to cancel

**Auto-approve (use with caution):**
```bash
terraform apply -auto-approve
```

**What happens during apply:**
1. Terraform creates resources in Azure
2. Progress is shown in real-time
3. State file is updated (`terraform.tfstate`)
4. Outputs are displayed

**Expected output:**
```
azurerm_resource_group.main: Creating...
azurerm_resource_group.main: Creation complete after 2s
azurerm_service_plan.main: Creating...
azurerm_service_plan.main: Creation complete after 15s
azurerm_linux_web_app.main: Creating...
azurerm_linux_web_app.main: Creation complete after 45s

Apply complete! Resources: 3 added, 0 changed, 0 destroyed.

Outputs:

app_service_name = "my-cloud-course-portal"
app_service_url = "https://my-cloud-course-portal.azurewebsites.net"
resource_group_name = "rg-cloud-course-portal"
```

**Typical timing:**
- Resource Group: 1-3 seconds
- App Service Plan: 10-20 seconds
- App Service: 30-60 seconds
- **Total: 1-2 minutes**

### Verifying Resources in Azure Portal

After deployment, verify resources were created correctly:

1. **Login to Azure Portal:**
   - Go to https://portal.azure.com
   - Sign in with your Azure account

2. **Navigate to Resource Groups:**
   - Click "Resource groups" in the left sidebar
   - Or search for "Resource groups" in the top search bar

3. **Open your resource group:**
   - Find your resource group (e.g., `rg-cloud-course-portal`)
   - Click to open it

4. **Verify resources:**
   You should see 2 resources:
   - **App Service Plan** (e.g., `asp-cloud-course-portal`)
     - Type: App Service plan
     - Pricing tier: Free F1
   - **App Service** (e.g., `my-cloud-course-portal`)
     - Type: App Service
     - Status: Running

5. **Check App Service details:**
   - Click on the App Service
   - **Overview tab:**
     - Status should be "Running"
     - URL should match Terraform output
   - **Configuration tab:**
     - General settings → Stack: PHP 8.0 or higher
     - General settings → Platform: 64 Bit
   - **TLS/SSL settings tab:**
     - HTTPS Only: On

6. **Test the default page:**
   - Click the URL in the Overview tab
   - Should show Azure default page (before code deployment)
   - After GitHub Actions deployment, shows your portal

### Viewing Terraform State

Terraform maintains state in `terraform.tfstate`:

```bash
# View current state
terraform show

# List all resources in state
terraform state list

# Show specific resource details
terraform state show azurerm_linux_web_app.main
```

**Important:**
- Never manually edit `terraform.tfstate`
- Commit `terraform.tfstate` to git is optional (contains resource IDs)
- For team environments, use remote state (Azure Storage, Terraform Cloud)

### Updating Infrastructure

To modify existing infrastructure:

1. **Edit Terraform files:**
   - Modify `main.tf`, `variables.tf`, or `terraform.tfvars`
   - Example: Change location, add tags, modify configuration

2. **Plan the changes:**
   ```bash
   terraform plan
   ```
   Review what will change.

3. **Apply the changes:**
   ```bash
   terraform apply
   ```

**Example: Adding tags**

Edit `terraform/main.tf`:
```hcl
resource "azurerm_resource_group" "main" {
  name     = var.resource_group_name
  location = var.location
  
  tags = {
    Environment = "Production"
    Project     = "Cloud Course Portal"
    ManagedBy   = "Terraform"
  }
}
```

Then apply:
```bash
terraform plan
terraform apply
```

### Terraform Format and Validation

Keep your Terraform code clean and valid:

**Format code:**
```bash
terraform fmt
```
Automatically formats `.tf` files to canonical style.

**Check formatting:**
```bash
terraform fmt -check
```
Returns exit code 0 if formatted, non-zero if changes needed.

**Validate configuration:**
```bash
terraform validate
```
Checks syntax and internal consistency.

**Run before committing:**
```bash
terraform fmt
terraform validate
```

### Troubleshooting Terraform

**Issue: "Error: A resource with the ID already exists"**

Solution: Import existing resource into state:
```bash
terraform import azurerm_linux_web_app.main /subscriptions/{sub-id}/resourceGroups/{rg}/providers/Microsoft.Web/sites/{app-name}
```

**Issue: "Error: State lock"**

Solution: If previous operation was interrupted:
```bash
terraform force-unlock <lock-id>
```
Use with caution - only if you're sure no other operation is running.

**Issue: "Error: Provider configuration not present"**

Solution: Run `terraform init` again.

### Destroying Resources

When you no longer need the infrastructure:

```bash
terraform destroy
```

**What this does:**
- Shows plan of resources to be destroyed
- Asks for confirmation
- Deletes all resources managed by Terraform

**Auto-approve (use with caution):**
```bash
terraform destroy -auto-approve
```

**Destroy specific resource:**
```bash
terraform destroy -target=azurerm_linux_web_app.main
```

**Verification:**
- Check Azure Portal - resource group should be deleted
- All resources removed
- No charges incurred

**Estimated time:** 5-10 minutes

---

## CI/CD Pipeline Usage Guide

This section explains how to use GitHub Actions for continuous integration and deployment.

### Understanding the CI/CD Workflow

The GitHub Actions workflow (`.github/workflows/deploy.yml`) automates:
1. **Validation** - Checks code quality and syntax
2. **Deployment** - Deploys code to Azure App Service

**Workflow triggers:**
- **Push to main branch** - Runs validation + deployment
- **Pull request** - Runs validation only (no deployment)

### Pushing Code to Trigger Validation

Every time you push code or create a pull request, GitHub Actions validates your code.

**What gets validated:**
- PHP syntax errors (`php -l`)
- File structure integrity
- Configuration validity

**To trigger validation:**

1. **Create a feature branch:**
   ```bash
   git checkout -b feature/update-content
   ```

2. **Make changes and commit:**
   ```bash
   git add .
   git commit -m "Update course curriculum"
   ```

3. **Push to GitHub:**
   ```bash
   git push origin feature/update-content
   ```

4. **Create pull request:**
   - Go to your repository on GitHub
   - Click "Pull requests" → "New pull request"
   - Select your feature branch
   - Click "Create pull request"

5. **Validation runs automatically:**
   - GitHub Actions starts the "validate" job
   - Results appear in the pull request
   - Green checkmark = validation passed
   - Red X = validation failed

### Viewing Workflow Runs in GitHub Actions

Monitor your CI/CD pipeline execution:

1. **Navigate to Actions tab:**
   - Go to your repository on GitHub
   - Click the **Actions** tab at the top

2. **View workflow runs:**
   - See list of all workflow runs
   - Most recent runs appear at the top
   - Each run shows:
     - Commit message
     - Branch name
     - Trigger event (push, pull_request)
     - Status (in progress, success, failure)
     - Duration

3. **Click on a workflow run:**
   - See detailed view of all jobs
   - **Validate** job (always runs)
   - **Deploy** job (only on main branch)

4. **View job details:**
   - Click on a job name (e.g., "validate")
   - See all steps executed
   - Expand steps to see detailed logs
   - Download logs for offline review

**Workflow run statuses:**
- 🟡 **In progress** - Currently running
- ✅ **Success** - All jobs completed successfully
- ❌ **Failure** - One or more jobs failed
- ⚪ **Cancelled** - Manually cancelled
- ⏭️ **Skipped** - Job skipped due to conditions

### Interpreting Workflow Results

#### Successful Workflow

**Validation Success:**
```
✓ Validate (2m 15s)
  ✓ Set up job
  ✓ Checkout code
  ✓ Setup PHP
  ✓ Validate PHP syntax
  ✓ Post Checkout code
  ✓ Complete job
```

**Deployment Success:**
```
✓ Deploy (3m 42s)
  ✓ Set up job
  ✓ Checkout code
  ✓ Login to Azure
  ✓ Deploy to Azure App Service
  ✓ Verify deployment
  ✓ Complete job
```

**What this means:**
- Code is valid and error-free
- Deployment completed successfully
- Application is live with latest changes
- Safe to merge pull request (if applicable)

#### Failed Workflow

**Validation Failure Example:**
```
✓ Validate (1m 8s)
  ✓ Set up job
  ✓ Checkout code
  ✓ Setup PHP
  ✗ Validate PHP syntax
    Error: PHP Parse error: syntax error, unexpected '}' in public/index.php on line 42
  ⏭️ Post Checkout code
  ✓ Complete job
```

**What this means:**
- PHP syntax error detected
- Deployment blocked (won't run)
- Fix required before merging

**Deployment Failure Example:**
```
✓ Validate (2m 10s)
✗ Deploy (1m 25s)
  ✓ Set up job
  ✓ Checkout code
  ✗ Login to Azure
    Error: AADSTS7000215: Invalid client secret provided
  ⏭️ Deploy to Azure App Service
  ⏭️ Verify deployment
  ✓ Complete job
```

**What this means:**
- Validation passed
- Azure authentication failed
- Credentials need updating

### Troubleshooting Failed Workflows

#### PHP Syntax Errors

**Symptom:** Validate job fails with "PHP Parse error"

**Solution:**
1. Read the error message for file and line number
2. Fix the syntax error locally
3. Test locally: `php -l public/index.php`
4. Commit and push the fix

**Example:**
```bash
# Test specific file
php -l public/index.php

# Test all PHP files
find public includes -name "*.php" -exec php -l {} \;
```

#### Azure Authentication Failures

**Symptom:** Deploy job fails with "Login failed" or "Invalid credentials"

**Solution:**
1. Regenerate service principal:
   ```bash
   az ad sp create-for-rbac --name "github-actions-sp" \
     --role contributor \
     --scopes /subscriptions/{subscription-id} \
     --sdk-auth
   ```

2. Update GitHub secret:
   - Go to repository Settings → Secrets → Actions
   - Edit `AZURE_CREDENTIALS`
   - Paste new JSON credentials
   - Save

3. Re-run the workflow:
   - Go to Actions tab
   - Click on failed workflow run
   - Click "Re-run all jobs"

#### Deployment Timeout

**Symptom:** Deploy job fails with "Timeout" or takes longer than 10 minutes

**Solution:**
1. Check Azure service health: https://status.azure.com
2. Verify App Service is running in Azure Portal
3. Try re-running the workflow
4. If persistent, check App Service logs for issues

#### App Service Name Conflict

**Symptom:** Deploy fails with "App name already exists" or "Conflict"

**Solution:**
1. Verify `AZURE_APP_NAME` secret matches Terraform `app_name`
2. Check App Service exists in Azure Portal
3. If mismatch, update secret or Terraform variable
4. Re-run workflow

### Manual Workflow Triggers

You can manually trigger workflows without pushing code:

1. **Navigate to Actions tab:**
   - Go to your repository on GitHub
   - Click "Actions"

2. **Select workflow:**
   - Click on "Deploy to Azure" workflow in left sidebar

3. **Run workflow:**
   - Click "Run workflow" button (top right)
   - Select branch to run from
   - Click "Run workflow"

**Note:** Manual triggers require workflow configuration:
```yaml
on:
  workflow_dispatch:  # Enables manual triggers
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]
```

### Monitoring Deployment Status

**Real-time monitoring:**

1. **During deployment:**
   - Watch the Deploy job in GitHub Actions
   - "Deploy to Azure App Service" step shows progress
   - Typically takes 2-4 minutes

2. **After deployment:**
   - "Verify deployment" step checks if app is accessible
   - Performs HTTP health check
   - Confirms deployment success

**Post-deployment verification:**

1. **Check application URL:**
   ```bash
   curl https://your-app-name.azurewebsites.net
   ```

2. **View in browser:**
   - Open application URL
   - Verify changes are live
   - Test functionality

3. **Check Azure App Service logs:**
   - Azure Portal → App Service → Monitoring → Log stream
   - Or use Azure CLI:
     ```bash
     az webapp log tail --name your-app-name --resource-group your-rg-name
     ```

### Workflow Optimization Tips

**Reduce workflow execution time:**

1. **Cache dependencies:**
   - PHP dependencies cached automatically by setup-php action
   - Reduces setup time

2. **Parallel jobs:**
   - Validation and deployment run sequentially (by design)
   - Ensures code is valid before deploying

3. **Conditional steps:**
   - Deploy job only runs on main branch
   - Saves Actions minutes on pull requests

**Monitor Actions usage:**
- GitHub free tier: 2,000 minutes/month for public repos
- View usage: Settings → Billing → Plans and usage
- Typical workflow: 5-6 minutes per run
- ~300-400 deployments per month within free tier

### Best Practices

**Before pushing to main:**
1. Test locally: `php -S localhost:8000 -t public`
2. Validate PHP syntax: `find . -name "*.php" -exec php -l {} \;`
3. Review changes: `git diff`
4. Use descriptive commit messages

**For team collaboration:**
1. Use feature branches for development
2. Create pull requests for code review
3. Wait for validation to pass before merging
4. Merge to main only after approval

**For production deployments:**
1. Test in local environment first
2. Review GitHub Actions logs after deployment
3. Verify application functionality
4. Monitor Azure App Service logs for errors
5. Keep deployment frequency reasonable (avoid excessive deployments)

### Updating the Application

Once deployed, you can update the application by pushing changes to GitHub:

1. **Make code changes locally:**
   - Edit PHP files, CSS, or other content
   - Test locally: `php -S localhost:8000 -t public`

2. **Commit changes:**
   ```bash
   git add .
   git commit -m "Description of changes"
   ```

3. **Push to GitHub:**
   ```bash
   git push origin main
   ```

4. **Automatic deployment:**
   - GitHub Actions automatically triggers
   - Validates and deploys your changes
   - Typically completes in 3-5 minutes

5. **Verify deployment:**
   - Check GitHub Actions for success
   - Visit application URL to see changes
   - Test functionality

---

## Cleanup: Destroying Resources

To avoid charges and clean up all Azure resources:

### Option 1: Destroy with Terraform (Recommended)

1. **Navigate to terraform directory:**
   ```bash
   cd terraform
   ```

2. **Run terraform destroy:**
   ```bash
   terraform destroy
   ```
   Type `yes` when prompted.

3. **Verify deletion:**
   - Check Azure Portal
   - Resource group should be deleted
   - All resources removed

**Estimated Time:** 5-10 minutes

### Option 2: Delete via Azure Portal

1. **Login to Azure Portal:**
   Go to https://portal.azure.com

2. **Navigate to Resource Groups:**
   - Click "Resource groups"
   - Find your resource group

3. **Delete the resource group:**
   - Click on the resource group
   - Click "Delete resource group"
   - Type the resource group name to confirm
   - Click "Delete"

**Note:** Deleting the resource group removes all resources within it.

---

## Troubleshooting

### Common Issues

#### Deployment Fails: "App name already exists"
**Problem:** The App Service name is already taken globally.

**Solution:**
1. Choose a different app name in `terraform/terraform.tfvars`
2. Update the `AZURE_APP_NAME` GitHub secret
3. Run `terraform apply` again

#### GitHub Actions Fails: "Login failed"
**Problem:** Azure credentials are invalid or expired.

**Solution:**
1. Regenerate service principal credentials (see Step 1)
2. Update the `AZURE_CREDENTIALS` GitHub secret
3. Re-run the workflow

#### Terraform Apply Fails: "Quota exceeded"
**Problem:** Free tier limits reached.

**Solution:**
1. Wait for quota reset (monthly)
2. Delete unused resources
3. Consider upgrading to paid tier

#### Application Shows "Service Unavailable"
**Problem:** App Service is starting or deployment failed.

**Solution:**
1. Wait 2-3 minutes for app to start
2. Check deployment logs in GitHub Actions
3. Check App Service logs in Azure Portal

For more troubleshooting help, see [docs/troubleshooting.md](troubleshooting.md).

---

## Next Steps

After successful deployment:

1. **Configure custom domain** (optional):
   - See Azure documentation for custom domain setup
   - Requires domain ownership verification

2. **Set up monitoring:**
   - Enable Application Insights in Azure Portal
   - Configure alerts for errors or downtime

3. **Enable continuous monitoring:**
   - Review GitHub Actions workflow runs regularly
   - Monitor Azure free tier usage

4. **Customize content:**
   - Update course information in `includes/config.php`
   - Modify page content in `public/*.php`
   - Update styling in `public/assets/css/style.css`

---

## Summary

You've successfully deployed the Azure PHP CI/CD Portal! Here's what you accomplished:

- ✓ Created Azure service principal for authentication
- ✓ Configured GitHub repository with secrets
- ✓ Deployed infrastructure using Terraform
- ✓ Set up automated CI/CD with GitHub Actions
- ✓ Deployed the PHP application to Azure App Service
- ✓ Verified the application is live and accessible

**Total Time:** 90-120 minutes

For additional help:
- [Azure Setup Guide](azure-setup.md)
- [GitHub Configuration](github-setup.md)
- [Local Development](local-development.md)
- [Troubleshooting](troubleshooting.md)
