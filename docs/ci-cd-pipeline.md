# CI/CD Pipeline Documentation

This document describes the GitHub Actions CI/CD pipeline for the Azure PHP CI/CD Portal project.

## Pipeline Overview

The pipeline consists of multiple stages that run automatically on pull requests and pushes to the main branch:

```
Pull Request Flow:
1. Validation (PHP syntax + Unit tests)
2. Terraform (Format, Validate, Plan + Infracost)
   └─> If validation fails, pipeline stops

Merge to Main Flow:
1. Validation (PHP syntax + Unit tests)
2. Terraform (Format, Validate, Plan + Infracost)
3. Terraform Apply (only if changes detected)
4. Deploy to Azure
   └─> If any step fails, pipeline stops
```

## Pipeline Stages

### 1. Validation Stage

**Triggers**: Pull requests and pushes to main

**Purpose**: Ensure code quality and correctness before proceeding

**Steps**:
- Checkout code
- Setup PHP 8.2
- Validate Composer configuration
- Install PHP dependencies
- Check PHP syntax in all files
- Run PHPUnit unit tests

**Failure Behavior**: If validation fails, the entire pipeline stops. No infrastructure changes or deployments will occur.

### 2. Terraform Stage

**Triggers**: Pull requests and pushes to main (after validation succeeds)

**Purpose**: Validate infrastructure code and plan changes

**Steps**:
- Checkout code
- Setup Terraform 1.5.0
- **Format Check**: Verify Terraform files are properly formatted
- **Init**: Initialize Terraform with remote backend
- **Validate**: Validate Terraform configuration syntax
- **Plan**: Generate execution plan and detect changes
- **Infracost** (PR only): Calculate cost impact of infrastructure changes

**Failure Behavior**: If Terraform validation or planning fails, the pipeline stops.

**Outputs**:
- `app_service_name`: Name of the Azure App Service
- `app_service_url`: URL of the deployed application
- `terraform_changed`: Boolean indicating if infrastructure changes were detected

### 3. Terraform Apply Stage

**Triggers**: Only on push to main branch when infrastructure changes are detected

**Purpose**: Apply infrastructure changes to Azure

**Steps**:
- Checkout code
- Setup Terraform
- Initialize Terraform
- Apply changes automatically

**Failure Behavior**: If apply fails, deployment is skipped.

### 4. Deploy Stage

**Triggers**: Only on push to main branch (after Terraform stages complete)

**Purpose**: Deploy application code to Azure App Service

**Steps**:
- Checkout code
- Determine App Service name from previous stages
- Login to Azure
- Deploy application package
- Verify deployment with health check
- Generate deployment summary

**Failure Behavior**: If deployment fails, the pipeline fails but infrastructure remains in a consistent state.

## Required GitHub Secrets

Configure these secrets in your GitHub repository settings:

### Azure Authentication

```
ARM_CLIENT_ID              # Azure Service Principal Client ID
ARM_CLIENT_SECRET          # Azure Service Principal Client Secret
ARM_SUBSCRIPTION_ID        # Azure Subscription ID
ARM_TENANT_ID              # Azure Tenant ID
```

### Azure Credentials (for deployment)

```
AZURE_CREDENTIALS          # JSON object with Azure credentials
```

Format:
```json
{
  "clientId": "your-client-id",
  "clientSecret": "your-client-secret",
  "subscriptionId": "your-subscription-id",
  "tenantId": "your-tenant-id"
}
```

### Terraform Backend

```
TERRAFORM_BACKEND_RESOURCE_GROUP    # Resource group for Terraform state
TERRAFORM_BACKEND_STORAGE_ACCOUNT   # Storage account for Terraform state
TERRAFORM_BACKEND_CONTAINER         # Container name for Terraform state
TERRAFORM_BACKEND_KEY               # State file name (e.g., "terraform.tfstate")
```

### Infracost

```
INFRACOST_API_KEY          # API key from Infracost (get free at infracost.io)
```

## Infracost Integration

Infracost provides cost estimates for infrastructure changes on pull requests.

### Setup Infracost

1. Sign up for free at [infracost.io](https://www.infracost.io/)
2. Get your API key from the dashboard
3. Add the API key as `INFRACOST_API_KEY` secret in GitHub

### How It Works

On every pull request:
1. Infracost analyzes the Terraform plan
2. Calculates the cost impact of proposed changes
3. Posts a comment on the PR with:
   - Monthly cost breakdown
   - Cost difference from current state
   - Detailed resource pricing

### Example Infracost Comment

```
Monthly cost estimate

Project: terraform

 Name                                    Monthly Qty  Unit   Monthly Cost 
                                                                           
 azurerm_linux_web_app.main                                                
 ├─ Linux app service plan (F1)                  730  hours        $0.00  
 └─ Outbound data transfer                         0  GB           $0.00  
                                                                           
 OVERALL TOTAL                                                     $0.00 
──────────────────────────────────
0 cloud resources were detected
```

## Pipeline Behavior

### First-Time Deployment (Important!)

**If infrastructure doesn't exist yet**, you have two options:

**Option 1: Deploy via Pipeline (Recommended)**
1. Push your code to main branch
2. The pipeline will detect no infrastructure exists
3. Terraform Apply will create all resources
4. Deployment will proceed automatically

**Option 2: Deploy Locally First**
```bash
cd terraform
terraform init \
  -backend-config="resource_group_name=YOUR_RG" \
  -backend-config="storage_account_name=YOUR_STORAGE" \
  -backend-config="container_name=YOUR_CONTAINER" \
  -backend-config="key=terraform.tfstate"

terraform apply
```
Then push to GitHub to trigger application deployment.

### On Pull Request

1. ✅ Runs validation (syntax + tests)
2. ✅ Runs Terraform format check, validate, and plan
3. 💰 Posts Infracost comment with cost estimate
4. ❌ Does NOT apply infrastructure changes
5. ❌ Does NOT deploy to Azure

**Purpose**: Verify changes are valid before merging

### On Push to Main (Merge)

1. ✅ Runs validation (syntax + tests)
2. ✅ Runs Terraform format check, validate, and plan
3. ✅ Applies infrastructure changes (if detected)
4. ✅ Deploys application to Azure
5. ✅ Verifies deployment health

**Purpose**: Deploy validated changes to production

## Monitoring Pipeline Execution

### View Pipeline Status

1. Go to your GitHub repository
2. Click on "Actions" tab
3. Select the workflow run to view details

### Pipeline Outputs

Each stage provides detailed logs and outputs:

- **Validation**: Test results and syntax check output
- **Terraform**: Plan output showing proposed changes
- **Infracost**: Cost breakdown (on PRs)
- **Deploy**: Deployment status and application URL

### Deployment Summary

After successful deployment, a summary is added to the workflow run:

```
🚀 Deployment Successful!

- App Service: your-app-name
- URL: https://your-app-name.azurewebsites.net
- Status: ✅ Live
```

## Troubleshooting

### Common Issues

#### Terraform Plan Exit Code Error

**Symptom**: `[: -eq: unary operator expected` or `Terraform plan failed`

**Cause**: Shell script error in exit code handling

**Solution**: This has been fixed in the latest version. Pull the latest changes:
```bash
git pull origin main
```

#### App Service Name Not Found

**Symptom**: `app-name is a required input` in deployment step

**Cause**: Infrastructure hasn't been deployed yet, or outputs aren't being retrieved

**Solution**: 
1. Ensure infrastructure exists in Azure
2. If first deployment, push to main to create infrastructure
3. Check that Terraform outputs are defined in `terraform/outputs.tf`
4. Verify the pipeline retrieved outputs in the Terraform stage logs

#### Infrastructure Doesn't Exist

**Symptom**: Deploy fails because no app service exists

**Solution**: 
1. Push to main branch (not PR) to trigger Terraform Apply
2. Or deploy infrastructure manually first:
   ```bash
   cd terraform
   terraform init
   terraform apply
   ```

### Validation Fails

**Symptom**: Pipeline stops at validation stage

**Solutions**:
1. Run tests locally: `composer test`
2. Check PHP syntax: `php -l filename.php`
3. Fix failing tests or syntax errors
4. Commit and push fixes

### Terraform Format Check Fails

**Symptom**: "Terraform files are not properly formatted"

**Solution**:
```bash
cd terraform
terraform fmt -recursive
git add .
git commit -m "Format Terraform files"
git push
```

### Terraform Plan Fails

**Symptom**: Pipeline stops at Terraform plan stage

**Solutions**:
1. Check Terraform syntax locally: `terraform validate`
2. Verify Azure credentials are correct
3. Check Terraform backend configuration
4. Review error logs in GitHub Actions

### Deployment Fails

**Symptom**: Application doesn't respond after deployment

**Solutions**:
1. Check Azure App Service logs
2. Verify application configuration
3. Check startup script (`startup.sh`)
4. Review deployment logs in GitHub Actions

### Infracost Not Working

**Symptom**: No cost comment on pull request

**Solutions**:
1. Verify `INFRACOST_API_KEY` secret is set
2. Check Infracost API key is valid
3. Review Infracost step logs in GitHub Actions

## Best Practices

1. **Always create pull requests**: Don't push directly to main
2. **Review Infracost comments**: Understand cost implications before merging
3. **Monitor pipeline execution**: Check for warnings even if pipeline succeeds
4. **Keep secrets updated**: Rotate credentials regularly
5. **Test locally first**: Run tests and Terraform validate before pushing

## Pipeline Configuration

The pipeline is defined in `.github/workflows/ci-cd.yml`.

### Key Configuration

```yaml
env:
  TERRAFORM_VERSION: '1.5.0'  # Terraform version
  PHP_VERSION: '8.2'          # PHP version
```

### Customization

To modify the pipeline:

1. Edit `.github/workflows/ci-cd.yml`
2. Test changes on a feature branch
3. Create a pull request to review changes
4. Merge when validated

## Security Considerations

1. **Secrets**: Never commit secrets to the repository
2. **Service Principal**: Use least-privilege access for Azure credentials
3. **Branch Protection**: Enable branch protection rules on main branch
4. **Required Reviews**: Require pull request reviews before merging
5. **Status Checks**: Make pipeline checks required for merging

## Additional Resources

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [Terraform Azure Provider](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs)
- [Infracost Documentation](https://www.infracost.io/docs/)
- [Azure App Service Documentation](https://docs.microsoft.com/en-us/azure/app-service/)
