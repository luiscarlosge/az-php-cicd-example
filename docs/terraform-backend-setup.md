# Terraform Backend Setup Guide

## Overview

This guide explains how to set up Azure Storage as a backend for Terraform state management. Using a remote backend ensures:

- **State Locking**: Prevents concurrent modifications
- **Team Collaboration**: Shared state across team members
- **State History**: Version control for infrastructure changes
- **Security**: Encrypted storage in Azure

## Prerequisites

- Azure CLI installed and configured
- Azure subscription with appropriate permissions
- Bash shell (Git Bash on Windows, native on Linux/Mac)

## Quick Setup

### Step 1: Run the Setup Script

```bash
# Make the script executable
chmod +x setup-terraform-backend.sh

# Run the script
./setup-terraform-backend.sh
```

The script will:
1. Create a resource group for Terraform state
2. Create a storage account with a unique name
3. Create a blob container for state files
4. Display the configuration values

### Step 2: Save the Output Values

The script outputs values like:

```
Add these values to your GitHub Secrets:
  TERRAFORM_BACKEND_RESOURCE_GROUP: terraform-state-rg
  TERRAFORM_BACKEND_STORAGE_ACCOUNT: tfstate1a2b3c4d
  TERRAFORM_BACKEND_CONTAINER: tfstate
  TERRAFORM_BACKEND_KEY: terraform.tfstate
```

### Step 3: Configure GitHub Secrets

1. Go to your GitHub repository
2. Navigate to **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret**
4. Add each secret:

| Secret Name | Description | Example Value |
|-------------|-------------|---------------|
| `TERRAFORM_BACKEND_RESOURCE_GROUP` | Resource group name | `terraform-state-rg` |
| `TERRAFORM_BACKEND_STORAGE_ACCOUNT` | Storage account name | `tfstate1a2b3c4d` |
| `TERRAFORM_BACKEND_CONTAINER` | Container name | `tfstate` |
| `TERRAFORM_BACKEND_KEY` | State file name | `terraform.tfstate` |

### Step 4: Verify Backend Configuration

The `terraform/backend.tf` file is already configured to use these values:

```hcl
terraform {
  backend "azurerm" {
    # Values provided via environment variables in GitHub Actions
    # or via -backend-config flags for local development
  }
}
```

## Local Development Setup

For local Terraform operations, initialize with backend config:

```bash
cd terraform

# Initialize with backend configuration
terraform init \
  -backend-config="resource_group_name=terraform-state-rg" \
  -backend-config="storage_account_name=tfstate1a2b3c4d" \
  -backend-config="container_name=tfstate" \
  -backend-config="key=terraform.tfstate"
```

**Tip**: Create a `backend-config.tfvars` file (add to .gitignore):

```hcl
resource_group_name  = "terraform-state-rg"
storage_account_name = "tfstate1a2b3c4d"
container_name       = "tfstate"
key                  = "terraform.tfstate"
```

Then initialize with:
```bash
terraform init -backend-config=backend-config.tfvars
```

## Manual Setup (Alternative)

If you prefer manual setup instead of using the script:

### 1. Create Resource Group

```bash
az group create \
  --name terraform-state-rg \
  --location eastus
```

### 2. Create Storage Account

```bash
# Generate unique name
STORAGE_ACCOUNT_NAME="tfstate$(openssl rand -hex 4)"

az storage account create \
  --name $STORAGE_ACCOUNT_NAME \
  --resource-group terraform-state-rg \
  --location eastus \
  --sku Standard_LRS \
  --encryption-services blob \
  --https-only true \
  --min-tls-version TLS1_2
```

### 3. Get Storage Account Key

```bash
ACCOUNT_KEY=$(az storage account keys list \
  --resource-group terraform-state-rg \
  --account-name $STORAGE_ACCOUNT_NAME \
  --query '[0].value' -o tsv)
```

### 4. Create Blob Container

```bash
az storage container create \
  --name tfstate \
  --account-name $STORAGE_ACCOUNT_NAME \
  --account-key $ACCOUNT_KEY
```

### 5. Save Configuration

Save the values for GitHub Secrets and local development.

## Verifying Backend Setup

### Check Storage Account

```bash
az storage account show \
  --name tfstate1a2b3c4d \
  --resource-group terraform-state-rg \
  --query "{Name:name, Location:location, Sku:sku.name}"
```

### List Containers

```bash
az storage container list \
  --account-name tfstate1a2b3c4d \
  --query "[].name"
```

### Check State File

After running Terraform:

```bash
az storage blob list \
  --account-name tfstate1a2b3c4d \
  --container-name tfstate \
  --query "[].name"
```

## GitHub Actions Integration

The workflow automatically uses the backend configuration:

```yaml
- name: Terraform Init
  working-directory: ./terraform
  run: |
    terraform init \
      -backend-config="resource_group_name=${{ secrets.TERRAFORM_BACKEND_RESOURCE_GROUP }}" \
      -backend-config="storage_account_name=${{ secrets.TERRAFORM_BACKEND_STORAGE_ACCOUNT }}" \
      -backend-config="container_name=${{ secrets.TERRAFORM_BACKEND_CONTAINER }}" \
      -backend-config="key=${{ secrets.TERRAFORM_BACKEND_KEY }}"
```

## State Locking

Azure Storage automatically provides state locking using blob leases. This prevents:
- Concurrent Terraform operations
- State corruption
- Race conditions

## Security Best Practices

### 1. Enable Encryption

The setup script enables encryption by default:
```bash
--encryption-services blob
```

### 2. Enforce HTTPS

Only HTTPS connections are allowed:
```bash
--https-only true
```

### 3. Minimum TLS Version

Enforce TLS 1.2 or higher:
```bash
--min-tls-version TLS1_2
```

### 4. Access Control

Limit access to the storage account:

```bash
# Disable public access
az storage account update \
  --name tfstate1a2b3c4d \
  --resource-group terraform-state-rg \
  --allow-blob-public-access false
```

### 5. Network Rules

Restrict access to specific IPs or VNets:

```bash
az storage account network-rule add \
  --account-name tfstate1a2b3c4d \
  --resource-group terraform-state-rg \
  --ip-address YOUR-IP-ADDRESS
```

## Troubleshooting

### Issue: "Backend initialization required"

**Solution**: Run `terraform init` with backend config:
```bash
terraform init -backend-config=backend-config.tfvars
```

### Issue: "Error acquiring state lock"

**Cause**: Another Terraform operation is in progress or a previous operation was interrupted.

**Solution**: 
1. Wait for the other operation to complete
2. If stuck, force unlock (use with caution):
   ```bash
   terraform force-unlock LOCK_ID
   ```

### Issue: "Storage account not found"

**Solution**: Verify the storage account name and resource group:
```bash
az storage account show \
  --name tfstate1a2b3c4d \
  --resource-group terraform-state-rg
```

### Issue: "Access denied"

**Solution**: Ensure the service principal has appropriate permissions:
```bash
az role assignment create \
  --assignee <service-principal-id> \
  --role "Storage Blob Data Contributor" \
  --scope /subscriptions/<subscription-id>/resourceGroups/terraform-state-rg
```

## State Management

### View State

```bash
terraform state list
```

### Pull State

```bash
terraform state pull > terraform.tfstate.backup
```

### Remove Resource from State

```bash
terraform state rm azurerm_resource_group.main
```

### Import Existing Resource

```bash
terraform import azurerm_resource_group.main /subscriptions/.../resourceGroups/my-rg
```

## Cost Considerations

### Storage Account Costs

- **Storage**: ~$0.02 per GB/month (LRS)
- **Operations**: Minimal cost for state operations
- **Bandwidth**: Egress charges may apply

**Typical monthly cost**: < $1.00 for small projects

### Cost Optimization

1. Use Standard LRS (cheapest option)
2. Enable lifecycle management to delete old state versions
3. Use a single storage account for multiple projects (different containers)

## Backup and Recovery

### Enable Soft Delete

```bash
az storage account blob-service-properties update \
  --account-name tfstate1a2b3c4d \
  --resource-group terraform-state-rg \
  --enable-delete-retention true \
  --delete-retention-days 30
```

### Enable Versioning

```bash
az storage account blob-service-properties update \
  --account-name tfstate1a2b3c4d \
  --resource-group terraform-state-rg \
  --enable-versioning true
```

### Manual Backup

```bash
# Download state file
az storage blob download \
  --account-name tfstate1a2b3c4d \
  --container-name tfstate \
  --name terraform.tfstate \
  --file terraform.tfstate.backup
```

## Multi-Environment Setup

For multiple environments (dev, staging, prod), use different state files:

```bash
# Development
terraform init -backend-config="key=dev/terraform.tfstate"

# Staging
terraform init -backend-config="key=staging/terraform.tfstate"

# Production
terraform init -backend-config="key=prod/terraform.tfstate"
```

Or use workspaces:

```bash
terraform workspace new dev
terraform workspace new staging
terraform workspace new prod
```

## Additional Resources

- [Terraform Azure Backend Documentation](https://www.terraform.io/docs/language/settings/backends/azurerm.html)
- [Azure Storage Documentation](https://docs.microsoft.com/azure/storage/)
- [Terraform State Management](https://www.terraform.io/docs/language/state/)

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Review Terraform and Azure documentation
3. Open an issue in the repository
