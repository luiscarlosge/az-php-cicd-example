# Requirements Document

## Introduction

This document specifies the requirements for a PHP-based static portal that showcases a Post Graduate Course in Cloud Computing. The portal will be deployed on Azure App Service using the free tier, with CI/CD automation through GitHub Actions and infrastructure management via Atlantis/Terraform.

## Glossary

- **Portal**: The PHP web application serving course information
- **Azure_App_Service**: Microsoft Azure's platform-as-a-service for hosting web applications
- **GitHub_Actions**: GitHub's CI/CD automation platform
- **Atlantis**: Terraform pull request automation tool
- **Free_Tier**: Azure App Service's F1 pricing tier with no cost
- **GitHub_Free_Account**: GitHub's free tier with public repositories and limited Actions minutes
- **CI/CD_Pipeline**: Continuous Integration and Continuous Deployment automated workflow
- **Infrastructure_as_Code**: Managing infrastructure through declarative configuration files
- **Static_Content**: Web content that doesn't require database queries or dynamic generation

## Requirements

### Requirement 1: Portal Content and Structure

**User Story:** As a prospective student, I want to view comprehensive information about the Post Graduate Course in Cloud Computing, so that I can make an informed decision about enrollment.

#### Acceptance Criteria

1. THE Portal SHALL display a home page with course overview and key highlights
2. THE Portal SHALL display a curriculum page listing all course modules and topics
3. THE Portal SHALL display a faculty page with instructor profiles and credentials
4. THE Portal SHALL display an admissions page with enrollment requirements and process
5. THE Portal SHALL display a contact page with inquiry form and contact information
6. WHEN a user navigates between pages, THE Portal SHALL maintain consistent navigation and branding
7. THE Portal SHALL render properly on desktop, tablet, and mobile devices (responsive design)

### Requirement 2: PHP Application Implementation

**User Story:** As a developer, I want a maintainable PHP application structure, so that the portal is easy to update and extend.

#### Acceptance Criteria

1. THE Portal SHALL be implemented using PHP 8.0 or higher
2. THE Portal SHALL use a modular structure with separate files for header, footer, and navigation components
3. THE Portal SHALL serve static HTML content generated through PHP includes
4. THE Portal SHALL NOT require a database for content storage
5. WHEN content needs updating, THE Portal SHALL allow modifications through PHP template files
6. THE Portal SHALL include proper HTML5 semantic markup and accessibility features
7. THE Portal SHALL load CSS stylesheets and assets efficiently

### Requirement 3: Azure App Service Configuration

**User Story:** As a system administrator, I want to deploy the portal on Azure App Service free tier, so that hosting costs are minimized while maintaining availability.

#### Acceptance Criteria

1. THE Portal SHALL be deployed to Azure App Service using the F1 (Free) pricing tier
2. THE Azure_App_Service SHALL be configured to run PHP 8.x runtime
3. THE Azure_App_Service SHALL be configured with a custom domain name (optional) or use the default azurewebsites.net domain
4. THE Azure_App_Service SHALL have deployment slots disabled (not available in free tier)
5. WHEN the free tier quota is exceeded, THE Azure_App_Service SHALL display appropriate error messages
6. THE Azure_App_Service SHALL be configured in a specific Azure region (user-selectable)

### Requirement 4: Infrastructure as Code with Terraform

**User Story:** As a DevOps engineer, I want infrastructure defined as code using Terraform, so that environments are reproducible and version-controlled.

#### Acceptance Criteria

1. THE Infrastructure_as_Code SHALL define the Azure App Service using Terraform configuration files
2. THE Infrastructure_as_Code SHALL define the Azure Resource Group for organizing resources
3. THE Infrastructure_as_Code SHALL define the Azure App Service Plan with F1 tier specifications
4. THE Infrastructure_as_Code SHALL include variables for configurable parameters (region, app name, resource group name)
5. THE Infrastructure_as_Code SHALL include outputs for the deployed application URL
6. WHEN Terraform configurations are applied, THE Infrastructure_as_Code SHALL create all required Azure resources
7. THE Infrastructure_as_Code SHALL be stored in the GitHub repository under an 'infrastructure' or 'terraform' directory

### Requirement 5: GitHub Repository and CI/CD Pipeline

**User Story:** As a developer, I want automated CI/CD through GitHub Actions using a free GitHub account, so that code changes are automatically tested and deployed without incurring costs.

#### Acceptance Criteria

1. THE GitHub repository SHALL be hosted on a GitHub_Free_Account
2. THE GitHub repository SHALL be public (required for unlimited Actions minutes on free tier)
3. THE GitHub_Actions SHALL trigger on push events to the main branch
4. THE GitHub_Actions SHALL trigger on pull request events for validation
5. WHEN code is pushed to main, THE CI/CD_Pipeline SHALL deploy the application to Azure App Service
6. WHEN a pull request is created, THE CI/CD_Pipeline SHALL run validation checks without deploying
7. THE CI/CD_Pipeline SHALL include a job for PHP syntax validation and linting
8. THE CI/CD_Pipeline SHALL include a job for deploying to Azure using Azure CLI or deployment credentials
9. THE CI/CD_Pipeline SHALL use GitHub Secrets for storing Azure credentials securely
10. THE CI/CD_Pipeline SHALL optimize workflow execution time to stay within free tier limits (2,000 minutes/month for public repos)
11. WHEN deployment succeeds, THE CI/CD_Pipeline SHALL report success status
12. WHEN deployment fails, THE CI/CD_Pipeline SHALL report failure with error details

### Requirement 6: Atlantis Configuration for Terraform Automation

**User Story:** As a DevOps engineer, I want Atlantis to automate Terraform workflows through pull requests on a free GitHub account, so that infrastructure changes are reviewed and applied safely.

#### Acceptance Criteria

1. THE Atlantis SHALL be configured to monitor the public GitHub repository for pull requests
2. WHEN a pull request modifies Terraform files, THE Atlantis SHALL automatically run 'terraform plan'
3. WHEN a pull request is approved and merged, THE Atlantis SHALL run 'terraform apply' automatically or on command
4. THE Atlantis SHALL post plan results as pull request comments for review
5. THE Atlantis SHALL authenticate with Azure using service principal credentials
6. THE Atlantis SHALL use a configuration file (atlantis.yaml) defining project settings
7. THE Atlantis SHALL prevent concurrent Terraform operations on the same workspace
8. THE Documentation SHALL include options for running Atlantis locally or using free cloud alternatives

### Requirement 7: Documentation and Setup Instructions

**User Story:** As a new team member, I want comprehensive setup documentation, so that I can configure and deploy the portal independently.

#### Acceptance Criteria

1. THE Documentation SHALL include step-by-step instructions for Azure free tier account setup
2. THE Documentation SHALL include instructions for creating an Azure service principal for authentication
3. THE Documentation SHALL include instructions for configuring GitHub repository secrets
4. THE Documentation SHALL include instructions for setting up Atlantis (self-hosted or cloud)
5. THE Documentation SHALL include instructions for local development environment setup
6. THE Documentation SHALL include a troubleshooting section for common deployment issues
7. THE Documentation SHALL include architecture diagrams showing the CI/CD workflow
8. WHEN following the documentation, THE User SHALL be able to deploy the portal from scratch within 2 hours

### Requirement 8: Deployment Workflow and Validation

**User Story:** As a quality assurance engineer, I want automated validation before deployment, so that broken code doesn't reach production.

#### Acceptance Criteria

1. WHEN code is committed, THE CI/CD_Pipeline SHALL validate PHP syntax using php -l
2. WHEN code is committed, THE CI/CD_Pipeline SHALL check for common security issues (if applicable)
3. WHEN validation passes, THE CI/CD_Pipeline SHALL proceed to deployment
4. WHEN validation fails, THE CI/CD_Pipeline SHALL block deployment and notify developers
5. THE CI/CD_Pipeline SHALL include a manual approval step for production deployments (optional)
6. WHEN deployment completes, THE CI/CD_Pipeline SHALL verify the application is accessible via HTTP health check

### Requirement 9: Security and Access Control

**User Story:** As a security administrator, I want secure credential management and access control, so that sensitive information is protected.

#### Acceptance Criteria

1. THE Portal SHALL NOT expose sensitive credentials in source code or configuration files
2. THE GitHub_Actions SHALL use GitHub Secrets for storing Azure credentials
3. THE Atlantis SHALL use environment variables or secure secret management for Azure credentials
4. THE Azure_App_Service SHALL use HTTPS for all connections (free tier supports HTTPS on azurewebsites.net domain)
5. WHEN accessing Azure resources, THE CI/CD_Pipeline SHALL use service principal authentication with minimal required permissions
6. THE Terraform state SHALL be stored remotely in Azure Storage with encryption (optional but recommended)

### Requirement 10: Monitoring and Logging

**User Story:** As a system administrator, I want basic monitoring and logging, so that I can troubleshoot issues and track application health.

#### Acceptance Criteria

1. THE Azure_App_Service SHALL have application logging enabled
2. THE Azure_App_Service SHALL have web server logging enabled
3. WHEN errors occur, THE Portal SHALL log error details to Azure App Service logs
4. THE Documentation SHALL include instructions for accessing and viewing logs through Azure Portal
5. THE Documentation SHALL include instructions for accessing logs through Azure CLI
