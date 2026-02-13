# GitHub Secrets Setup Guide

This guide explains how to configure all required GitHub secrets for the CI/CD pipeline.

## Required Secrets Overview

The pipeline requires the following secrets to be configured in your GitHub repository:

| Secret Name | Purpose | Format |
|-------------|---------|--------|
| `ARM_CLIENT_ID` | Azure Service Principal Client ID | UUID |
| `ARM_CLIENT_SECRET` | Azure Service Principal Secret | String |
| `ARM_SUBSCRIPTION_ID` | Azure Subscription ID | UUID |
| `ARM_TENANT_ID` | Azure Tenant ID | UUID |
| `AZURE_CREDENTIALS` | Azure credentials for deployment | JSON |
| `TERRAFORM_BACKEND_RESOURCE_GROUP` | Terraform state resource group | String |
| `TERRAFORM_BACKEND_STORAGE_ACCOUNT` | Terraform state storage account | String |
| `TERRAFORM_BACKEND_CONTAINER` | Terraform state container | String |
| `TERRAFORM_BACKEND_KEY` | Terraform state file name | String |
| `INFRACOST_API_KEY` | Infracost API key for cost analysis | String |

## Step-by-Step Setup

### 1. Navigate to Repository Settings

1. Go to your GitHub repository
2. Click on **Settings** tab
3. In the left sidebar, click **Secrets and variables** → **Actions**
4. Click **New repository secret**

### 2. Azure Authentication Secrets

These secrets are obtained from your Azure Service Principal.

#### Create Service Principal

If you haven't created a service principal yet:

```bash
# Login to Azure
az login

# Create service principal
az ad sp create-for-rbac \
  --name "github-actions-sp" \
  --role contributor \
  --scopes /subscriptions/{subscription-id} \
  --sdk-auth
```

This command outputs JSON like:

```json
{
  "clientId": "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
  "clientSecret": "your-client-secret",
  "subscriptionId": "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
  "tenantId": "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
  "activeDirectoryEndpointUrl": "https://login.microsoftonline.com",
  "resourceManagerEndpointUrl": "https://management.azure.com/",
  "activeDirectoryGraphResourceId": "https://graph.windows.net/",
  "sqlManagementEndpointUrl": "https://management.core.windows.net:8443/",
  "galleryEndpointUrl": "https://gallery.azure.com/",
  "managementEndpointUrl": "https://management.core.windows.net/"
}
```

#### Add Individual Secrets

Add these secrets one by one:

**ARM_CLIENT_ID**
- Name: `ARM_CLIENT_ID`
- Value: Copy the `clientId` from the JSON output
- Example: `12345678-1234-1234-1234-123456789abc`

**ARM_CLIENT_SECRET**
- Name: `ARM_CLIENT_SECRET`
- Value: Copy the `clientSecret` from the JSON output
- Example: `your-secret-value-here`

**ARM_SUBSCRIPTION_ID**
- Name: `ARM_SUBSCRIPTION_ID`
- Value: Copy the `subscriptionId` from the JSON output
- Example: `87654321-4321-4321-4321-cba987654321`

**ARM_TENANT_ID**
- Name: `ARM_TENANT_ID`
- Value: Copy the `tenantId` from the JSON output
- Example: `11111111-2222-3333-4444-555555555555`

**AZURE_CREDENTIALS**
- Name: `AZURE_CREDENTIALS`
- Value: Copy the **entire JSON output** from the service principal creation
- Format:
```json
{
  "clientId": "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
  "clientSecret": "your-client-secret",
  "subscriptionId": "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
  "tenantId": "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
}
```

### 3. Terraform Backend Secrets

These secrets configure where Terraform stores its state file.

#### Option A: Use Existing Backend

If you already have a Terraform backend configured:

**TERRAFORM_BACKEND_RESOURCE_GROUP**
- Name: `TERRAFORM_BACKEND_RESOURCE_GROUP`
- Value: Your existing resource group name
- Example: `terraform-state-rg`

**TERRAFORM_BACKEND_STORAGE_ACCOUNT**
- Name: `TERRAFORM_BACKEND_STORAGE_ACCOUNT`
- Value: Your existing storage account name
- Example: `tfstatestorage123`

**TERRAFORM_BACKEND_CONTAINER**
- Name: `TERRAFORM_BACKEND_CONTAINER`
- Value: Your existing container name
- Example: `tfstate`

**TERRAFORM_BACKEND_KEY**
- Name: `TERRAFORM_BACKEND_KEY`
- Value: State file name
- Example: `terraform.tfstate`

#### Option B: Create New Backend

If you need to create a new Terraform backend:

```bash
# Set variables
RESOURCE_GROUP="terraform-state-rg"
STORAGE_ACCOUNT="tfstate$(date +%s)"  # Must be globally unique
CONTAINER="tfstate"
LOCATION="eastus2"

# Create resource group
az group create \
  --name $RESOURCE_GROUP \
  --location $LOCATION

# Create storage account
az storage account create \
  --name $STORAGE_ACCOUNT \
  --resource-group $RESOURCE_GROUP \
  --location $LOCATION \
  --sku Standard_LRS \
  --encryption-services blob

# Create container
az storage container create \
  --name $CONTAINER \
  --account-name $STORAGE_ACCOUNT
```

Then add the secrets:

- `TERRAFORM_BACKEND_RESOURCE_GROUP`: Value from `$RESOURCE_GROUP`
- `TERRAFORM_BACKEND_STORAGE_ACCOUNT`: Value from `$STORAGE_ACCOUNT`
- `TERRAFORM_BACKEND_CONTAINER`: Value from `$CONTAINER`
- `TERRAFORM_BACKEND_KEY`: `terraform.tfstate`

📖 See [Terraform Backend Setup Guide](terraform-backend-setup.md) for detailed instructions.

### 4. Infracost API Key

Infracost provides cost estimates for infrastructure changes.

#### Get Infracost API Key

1. Go to [infracost.io](https://www.infracost.io/)
2. Sign up for a free account
3. Navigate to your dashboard
4. Copy your API key

#### Add Secret

**INFRACOST_API_KEY**
- Name: `INFRACOST_API_KEY`
- Value: Your Infracost API key
- Example: `ico-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`

**Note**: Infracost is free for up to 10,000 resources per month.

## Verification

After adding all secrets, verify they're configured correctly:

1. Go to **Settings** → **Secrets and variables** → **Actions**
2. You should see 10 repository secrets listed
3. Click on each secret to verify the name (you can't view the value)

### Expected Secrets List

- ✅ ARM_CLIENT_ID
- ✅ ARM_CLIENT_SECRET
- ✅ ARM_SUBSCRIPTION_ID
- ✅ ARM_TENANT_ID
- ✅ AZURE_CREDENTIALS
- ✅ TERRAFORM_BACKEND_RESOURCE_GROUP
- ✅ TERRAFORM_BACKEND_STORAGE_ACCOUNT
- ✅ TERRAFORM_BACKEND_CONTAINER
- ✅ TERRAFORM_BACKEND_KEY
- ✅ INFRACOST_API_KEY

## Testing the Configuration

After adding all secrets, test the pipeline:

1. Create a new branch:
   ```bash
   git checkout -b test-pipeline
   ```

2. Make a small change (e.g., update README)

3. Commit and push:
   ```bash
   git add .
   git commit -m "Test pipeline configuration"
   git push origin test-pipeline
   ```

4. Create a pull request

5. Check the Actions tab to see if the pipeline runs successfully

## Troubleshooting

### Secret Not Found Error

**Error**: `Secret ARM_CLIENT_ID not found`

**Solution**: 
1. Verify the secret name is exactly as shown (case-sensitive)
2. Ensure the secret is added to the repository (not organization)
3. Check that you're in the correct repository

### Authentication Failed

**Error**: `Azure authentication failed`

**Solution**:
1. Verify service principal credentials are correct
2. Check that the service principal has Contributor role
3. Ensure the subscription ID is correct
4. Verify the tenant ID matches your Azure AD

### Terraform Backend Error

**Error**: `Error loading state: storage account not found`

**Solution**:
1. Verify the storage account exists in Azure
2. Check that the resource group name is correct
3. Ensure the container exists in the storage account
4. Verify the service principal has access to the storage account

### Infracost Error

**Error**: `Infracost API key is invalid`

**Solution**:
1. Verify the API key is correct (no extra spaces)
2. Check that your Infracost account is active
3. Generate a new API key if needed

## Security Best Practices

1. **Never commit secrets**: Don't add secrets to your code
2. **Rotate regularly**: Change secrets periodically
3. **Least privilege**: Use minimal permissions for service principals
4. **Monitor usage**: Check Azure Activity Logs for suspicious activity
5. **Use separate credentials**: Different credentials for dev/prod
6. **Enable MFA**: Use multi-factor authentication for Azure account

## Updating Secrets

To update a secret:

1. Go to **Settings** → **Secrets and variables** → **Actions**
2. Click on the secret name
3. Click **Update secret**
4. Enter the new value
5. Click **Update secret**

**Note**: Updating a secret doesn't trigger a new workflow run. You'll need to push a new commit or manually trigger the workflow.

## Additional Resources

- [GitHub Secrets Documentation](https://docs.github.com/en/actions/security-guides/encrypted-secrets)
- [Azure Service Principal Documentation](https://docs.microsoft.com/en-us/azure/active-directory/develop/howto-create-service-principal-portal)
- [Terraform Backend Configuration](https://www.terraform.io/docs/language/settings/backends/azurerm.html)
- [Infracost Documentation](https://www.infracost.io/docs/)
