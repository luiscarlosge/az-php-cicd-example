# Azure PHP CI/CD Portal

A PHP-based static portal showcasing a Post Graduate Course in Cloud Computing, deployed on Azure App Service with automated CI/CD through GitHub Actions and infrastructure managed via Terraform.

## Overview

This project demonstrates a complete DevOps workflow for deploying a PHP web application to Azure using modern cloud-native practices. It's designed as a learning resource for understanding CI/CD pipelines, Infrastructure as Code, and Azure cloud services.

### Key Technologies

- **PHP 8.0+** for the web application
- **Azure App Service (Free Tier)** for hosting
- **Terraform** for Infrastructure as Code
- **GitHub Actions** for CI/CD automation
- **Atlantis** (optional) for Terraform workflow automation
- **PHPUnit** for testing

## Features

- 📚 **Comprehensive Course Portal**: Complete information about a Post Graduate Course in Cloud Computing
- 🎨 **Responsive Design**: Mobile-first design that works on all devices (mobile, tablet, desktop)
- 🔒 **Secure Deployment**: HTTPS enforced, credentials managed via GitHub Secrets
- 🚀 **Automated CI/CD**: Push to deploy with automated testing and validation
- 🏗️ **Infrastructure as Code**: All Azure resources defined and managed via Terraform
- 📊 **Logging & Monitoring**: Application and web server logging enabled
- 💰 **Zero-Cost Hosting**: Deployed on Azure App Service free tier (F1)
- ✅ **Comprehensive Testing**: Unit tests and property-based tests included
- 📖 **Extensive Documentation**: Step-by-step guides for setup and deployment

## Quick Start

### Prerequisites

Before you begin, ensure you have:

- **PHP 8.0 or higher** installed
- **Composer** for PHP dependency management
- **Git** for version control
- **Azure account** (free tier is sufficient)
- **GitHub account** (free tier is sufficient)
- **Azure CLI** (for service principal creation)
- **Terraform** (for infrastructure deployment)

### Local Development Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/azure-php-cicd-portal.git
   cd azure-php-cicd-portal
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Start the local development server:**
   ```bash
   php -S localhost:8000 -t public
   ```

4. **Access the portal:**
   Open your browser and navigate to `http://localhost:8000`

5. **Verify all pages work:**
   - Home: http://localhost:8000/
   - Curriculum: http://localhost:8000/curriculum.php
   - Faculty: http://localhost:8000/faculty.php
   - Admissions: http://localhost:8000/admissions.php
   - Contact: http://localhost:8000/contact.php

### Running Tests

```bash
# Run all unit tests
vendor/bin/phpunit

# Run specific test suite
vendor/bin/phpunit tests/unit

# Run with verbose output
vendor/bin/phpunit --verbose

# Run with code coverage (requires Xdebug)
vendor/bin/phpunit --coverage-html coverage
```

## Deployment to Azure

Follow these steps to deploy the portal to Azure:

### 1. Set Up Azure Account

Create a free Azure account and subscription:

📖 **[Azure Setup Guide](docs/azure-setup.md)** - Complete walkthrough for creating your Azure account

### 2. Create Service Principal

Create an Azure Service Principal for authentication:

📖 **[Service Principal Guide](docs/service-principal.md)** - Step-by-step instructions with Azure CLI commands

### 3. Configure GitHub Repository

Set up your GitHub repository and secrets:

📖 **[GitHub Setup Guide](docs/github-setup.md)** - Configure repository, secrets, and Actions

### 4. Deploy Infrastructure with Terraform

Create Azure resources using Terraform:

```bash
# Navigate to terraform directory
cd terraform

# Initialize Terraform
terraform init

# Create terraform.tfvars from example
cp terraform.tfvars.example terraform.tfvars

# Edit terraform.tfvars with your values
# Set: app_name, location, resource_group_name

# Plan the deployment
terraform plan

# Apply the configuration
terraform apply
```

📖 **[Terraform Documentation](terraform/README.md)** - Detailed Terraform usage guide

### 5. Push Code to Trigger Deployment

Once infrastructure is ready, push your code:

```bash
git add .
git commit -m "Initial deployment"
git push origin main
```

GitHub Actions will automatically:
1. Validate PHP syntax
2. Run unit tests
3. Deploy to Azure App Service
4. Verify deployment with health check

### 6. Verify Deployment

Access your deployed application:
```
https://<your-app-name>.azurewebsites.net
```

Check deployment status in GitHub Actions tab.

## Project Structure

```
azure-php-cicd-portal/
├── public/                     # Web root directory
│   ├── index.php              # Home page
│   ├── curriculum.php         # Curriculum page
│   ├── faculty.php            # Faculty page
│   ├── admissions.php         # Admissions page
│   ├── contact.php            # Contact page
│   └── assets/                # Static assets
│       ├── css/               # Stylesheets
│       ├── js/                # JavaScript files
│       └── images/            # Images and logos
├── includes/                   # PHP components and configuration
│   ├── config.php             # Site configuration and constants
│   ├── header.php             # Header component
│   ├── navigation.php         # Navigation menu component
│   └── footer.php             # Footer component
├── terraform/                  # Infrastructure as Code
│   ├── main.tf                # Main Terraform configuration
│   ├── variables.tf           # Input variables
│   ├── outputs.tf             # Output values
│   ├── providers.tf           # Provider configuration
│   ├── backend.tf             # Remote state configuration
│   └── terraform.tfvars.example  # Example variable values
├── .github/workflows/          # GitHub Actions workflows
│   └── deploy.yml             # CI/CD pipeline configuration
├── tests/                      # Test files
│   ├── unit/                  # PHP unit tests
│   │   ├── PageRenderTest.php # Page rendering tests
│   │   └── ComponentTest.php  # Component tests
│   └── terraform/             # Terraform tests
│       └── terraform_test.go  # Infrastructure tests
├── docs/                       # Documentation
│   ├── azure-setup.md         # Azure account setup
│   ├── service-principal.md   # Service principal creation
│   ├── github-setup.md        # GitHub configuration
│   ├── atlantis-setup.md      # Atlantis setup (optional)
│   ├── local-development.md   # Local dev environment
│   ├── architecture.md        # System architecture
│   ├── troubleshooting.md     # Common issues
│   └── logging.md             # Logging and monitoring
├── .kiro/specs/               # Project specifications
│   └── azure-php-cicd-portal/
│       ├── requirements.md    # Requirements document
│       ├── design.md          # Design document
│       └── tasks.md           # Implementation tasks
├── composer.json              # PHP dependencies
├── phpunit.xml                # PHPUnit configuration
├── atlantis.yaml              # Atlantis configuration
├── .gitignore                 # Git ignore rules
└── README.md                  # This file
```

## Technology Stack

### Frontend
- **HTML5**: Semantic markup for accessibility
- **CSS3**: Responsive design with flexbox/grid
- **JavaScript**: Minimal vanilla JS for interactivity

### Backend
- **PHP 8.0+**: Server-side rendering
- **Composer**: Dependency management

### Infrastructure
- **Azure App Service**: Web hosting (F1 Free Tier)
- **Azure Resource Group**: Resource organization
- **Azure App Service Plan**: Compute resources

### DevOps
- **Git**: Version control
- **GitHub**: Repository hosting
- **GitHub Actions**: CI/CD automation
- **Terraform**: Infrastructure as Code
- **Atlantis**: Terraform PR automation (optional)

### Testing
- **PHPUnit**: Unit testing framework
- **Terratest**: Infrastructure testing (Go)

### Monitoring
- **Azure App Service Logs**: Application logging
- **Azure Monitor**: Metrics and alerts

## CI/CD Pipeline

The GitHub Actions workflow automatically:

1. **Validation** (on all branches):
   - Checks out code
   - Sets up PHP 8.0
   - Validates PHP syntax
   - Runs PHPUnit tests

2. **Deployment** (on main branch only):
   - Checks out code
   - Authenticates with Azure
   - Deploys to App Service
   - Verifies deployment with health check

### Workflow Triggers

- **Push to any branch**: Runs validation
- **Pull request**: Runs validation
- **Push to main**: Runs validation + deployment

## Cost Breakdown

This project is designed to run on Azure's free tier:

### Azure App Service F1 (Free Tier)
- **Cost**: $0.00/month
- **Included**:
  - 1 GB RAM
  - 1 GB storage
  - 60 CPU minutes/day
  - 165 MB bandwidth/day
  - Custom domain support
  - Free SSL certificate

### GitHub Actions
- **Cost**: $0.00/month (for public repositories)
- **Included**:
  - Unlimited Actions minutes for public repos
  - 2,000 minutes/month for private repos (free tier)

### Total Monthly Cost
- **$0.00** when using free tiers

**Note**: Costs may apply if you exceed free tier limits or upgrade to paid tiers.

## Security Features

- ✅ **HTTPS Enforced**: All traffic uses SSL/TLS encryption
- ✅ **Credential Management**: Secrets stored in GitHub Secrets (encrypted)
- ✅ **Service Principal**: Minimal permissions (Contributor role)
- ✅ **No Hardcoded Secrets**: All credentials via environment variables
- ✅ **Branch Protection**: Optional PR approval before merge
- ✅ **Automated Testing**: Code validated before deployment
- ✅ **Audit Logs**: Azure Activity Logs track all changes

## Performance Considerations

### Free Tier Limitations
- **CPU**: 60 minutes per day
- **Memory**: 1 GB
- **Bandwidth**: 165 MB outbound per day
- **Always On**: Not available (app may sleep after inactivity)

### Optimization Tips
- Minimize CPU-intensive operations
- Optimize images and assets
- Use browser caching
- Consider upgrading to paid tier for production use

## Troubleshooting

### Common Issues

**Issue**: Deployment fails with authentication error
- **Solution**: Verify `AZURE_CREDENTIALS` secret is correct
- **Guide**: [Service Principal Guide](docs/service-principal.md)

**Issue**: App Service name already taken
- **Solution**: Choose a unique app name in `terraform.tfvars`
- **Guide**: [Troubleshooting Guide](docs/troubleshooting.md#app-service-name-conflicts)

**Issue**: PHP syntax errors in workflow
- **Solution**: Run `php -l` locally to check syntax
- **Guide**: [Troubleshooting Guide](docs/troubleshooting.md#php-syntax-errors)

**Issue**: Free tier quota exceeded
- **Solution**: Wait for daily reset or upgrade tier
- **Guide**: [Troubleshooting Guide](docs/troubleshooting.md#azure-free-tier-quota-exceeded)

For more issues and solutions, see the **[Troubleshooting Guide](docs/troubleshooting.md)**.

## Contributing

We welcome contributions! Here's how to get started:

1. **Fork the repository**
2. **Create a feature branch**:
   ```bash
   git checkout -b feature/amazing-feature
   ```
3. **Make your changes**:
   - Follow existing code style
   - Add tests for new features
   - Update documentation as needed
4. **Run tests**:
   ```bash
   vendor/bin/phpunit
   ```
5. **Commit your changes**:
   ```bash
   git commit -m 'Add amazing feature'
   ```
6. **Push to your fork**:
   ```bash
   git push origin feature/amazing-feature
   ```
7. **Open a Pull Request**

### Contribution Guidelines

- Write clear, descriptive commit messages
- Include tests for new functionality
- Update documentation for user-facing changes
- Follow PHP PSR-12 coding standards
- Ensure all tests pass before submitting PR

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

Need help? Here are your options:

1. **Documentation**: Check the [docs/](docs/) directory for guides
2. **Troubleshooting**: See the [Troubleshooting Guide](docs/troubleshooting.md)
3. **GitHub Issues**: Open an issue for bugs or feature requests
4. **GitHub Discussions**: Ask questions or share ideas
5. **Email**: Contact info@example.com for general inquiries

## Acknowledgments

This project was made possible by:

- **Microsoft Azure** for providing free tier hosting
- **GitHub** for free repository hosting and Actions
- **HashiCorp** for Terraform
- **Runatlantis** for Atlantis automation
- **PHPUnit** for testing framework
- The open-source community for inspiration and tools

## Learning Resources

Want to learn more about the technologies used?

- [Azure App Service Documentation](https://docs.microsoft.com/azure/app-service/)
- [GitHub Actions Documentation](https://docs.github.com/actions)
- [Terraform Documentation](https://www.terraform.io/docs)
- [PHP Documentation](https://www.php.net/docs.php)
- [Atlantis Documentation](https://www.runatlantis.io/docs/)

## Project Status

- ✅ **Active Development**: Project is actively maintained
- ✅ **Production Ready**: Suitable for deployment
- ✅ **Well Documented**: Comprehensive guides available
- ✅ **Tested**: Unit tests and infrastructure tests included

## Roadmap

Future enhancements under consideration:

- [ ] Add database support (Azure Database for MySQL)
- [ ] Implement user authentication (Azure AD B2C)
- [ ] Add Application Insights for advanced monitoring
- [ ] Create mobile app using the same backend
- [ ] Add API endpoints for programmatic access
- [ ] Implement caching with Azure Redis Cache
- [ ] Add multi-language support
- [ ] Create admin dashboard for content management

## Contact

- **Project Maintainer**: Luis Carlos Galvis
- **Email**: luis.galvis.espitia@gmail.com.com
- **GitHub**: [@your-username](https://github.com/your-username)
- **Website**: https://your-app.azurewebsites.net

---

**Built with ❤️ using Azure, PHP, and DevOps best practices**
