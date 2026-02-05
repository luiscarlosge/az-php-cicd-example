# Azure Service Principal Setup Guide

This guide explains how to create an Azure Service Principal for authenticating GitHub Actions and Atlantis with your Azure subscription.

## What is a Service Principal?

A Service Principal is an identity created for use with applications, services, and automation tools to access Azure resources. Think of it as a "service account" that allows automated tools to authenticate with Azure without using your personal credentials.

For this project, the Service Principal will be used by:
- **GitHub Actions**: To deploy the PHP application to Azure App Service
- **Atlantis** (optional): To manage infrastructure with Terraform

## Prerequisites

- An active Azure subscription (see [Azure Setup Guide](./azure-setup.md))
- Your Azure Subscription ID (found in Azure Portal → Subscriptions)
- Azure CLI installed on your local machine

## Step 1: Install Azure CLI

### Windows

**Option 1: MSI Installer (Recommended)**
1. Download the MSI installer from: https://aka.ms/installazurecliwindows
2. Run the installer and follow the prompts
3. Restart your terminal after installation

**Option 2: PowerShell**
```powershell
Invoke-WebRequest -Uri https://aka.ms/installazurecliwindows -OutFile .\AzureCLI.msi
Start-Process msiexec.exe -Wait -ArgumentList '/I AzureCLI.msi /quiet'
```

### macOS

**Option 1: Homebrew (Recommended)**
```bash
brew update && brew install azure-cli
```

**Option 2: Install Script**
```bash
curl -L https://aka.ms/InstallAzureCli | bash
```

### Linux

**Ubuntu/Debian:**
```bash
curl -sL https://aka.ms/InstallAzureCLIDeb | sudo bash
```

**RHEL/CentOS/Fedora:**
```bash
sudo rpm --import https://packages.microsoft.com/keys/microsoft.asc
sudo dnf install azure-cli
```

### Verify Installation

After installation, verify the Azure CLI is installed:
```bash
az --version
```

You should see output showing the Azure CLI version (e.g., `azure-cli 2.50.0`).

## Step 2: Login to Azure

Open your terminal and run:

```bash
az login
```

This will:
1. Open your default web browser
2. Prompt you to sign in with your Azure account
3. Display a success message in the terminal once authenticated

**Alternative: Login without browser**
If you're on a machine without a browser or in a remote session:
```bash
az login --use-device-code
```

Follow the instructions to complete authentication.

### Verify Login

Check that you're logged in and see your subscriptions:
```bash
az account list --output table
```

You should see a list of your Azure subscriptions.

## Step 3: Set the Correct Subscription

If you have multiple subscriptions, set the one you want to use:

```bash
az account set --subscription "<your-subscription-id>"
```

Replace `<your-subscription-id>` with your actual Subscription ID.

Verify the correct subscription is selected:
```bash
az account show --output table
```

## Step 4: Create the Service Principal

Now create the Service Principal with the necessary permissions:

```bash
az ad sp create-for-rbac --name "github-actions-sp" --role contributor --scopes /subscriptions/<your-subscription-id> --sdk-auth
```

**Important**: Replace `<your-subscription-id>` with your actual Subscription ID.

### Understanding the Command

- `az ad sp create-for-rbac`: Creates a service principal for role-based access control
- `--name "github-actions-sp"`: Name for the service principal (you can change this)
- `--role contributor`: Assigns the "Contributor" role (can create/manage resources)
- `--scopes /subscriptions/<id>`: Limits access to your specific subscription
- `--sdk-auth`: Outputs credentials in a format compatible with Azure SDKs

### Expected Output

The command will output JSON credentials like this:

```json
{
  "clientId": "12345678-1234-1234-1234-123456789abc",
  "clientSecret": "your-client-secret-here",
  "subscriptionId": "87654321-4321-4321-4321-cba987654321",
  "tenantId": "abcdefgh-abcd-abcd-abcd-abcdefghijkl",
  "activeDirectoryEndpointUrl": "https://login.microsoftonline.com",
  "resourceManagerEndpointUrl": "https://management.azure.com/",
  "activeDirectoryGraphResourceId": "https://graph.windows.net/",
  "sqlManagementEndpointUrl": "https://management.core.windows.net:8443/",
  "galleryEndpointUrl": "https://gallery.azure.com/",
  "managementEndpointUrl": "https://management.core.windows.net/"
}
```

**CRITICAL**: Save this entire JSON output immediately! You won't be able to retrieve the `clientSecret` again.

### Save the Credentials Securely

1. Copy the entire JSON output
2. Save it to a secure location (password manager, encrypted file, etc.)
3. **Never commit this to Git or share it publicly**
4. You'll need this for configuring GitHub Secrets

## Step 5: Verify Service Principal Creation

Verify the service principal was created:

```bash
az ad sp list --display-name "github-actions-sp" --output table
```

You should see your service principal listed with its Application ID (same as `clientId`).

## Step 6: Understand the Permissions

The "Contributor" role grants the service principal permission to:
- Create and manage Azure resources
- Deploy applications to App Service
- Manage resource groups
- Configure App Service settings

The "Contributor" role does NOT allow:
- Managing access control (IAM)
- Assigning roles to other users
- Deleting the subscription

### Principle of Least Privilege

For production environments, consider creating a custom role with only the specific permissions needed:

```bash
# Example: Create a custom role (optional, for advanced users)
az role definition create --role-definition '{
  "Name": "App Service Deployer",
  "Description": "Can deploy to App Service only",
  "Actions": [
    "Microsoft.Web/sites/*",
    "Microsoft.Web/serverfarms/*"
  ],
  "AssignableScopes": ["/subscriptions/<your-subscription-id>"]
}'
```

For this tutorial, the "Contributor" role is sufficient and simpler to set up.

## Step 7: Configure GitHub Secrets

Now that you have the service principal credentials, you need to add them to GitHub Secrets.

See the [GitHub Setup Guide](./github-setup.md) for detailed instructions on:
1. Adding the `AZURE_CREDENTIALS` secret (the entire JSON output)
2. Adding the `AZURE_APP_NAME` secret (your App Service name)

## Step 8: Test the Service Principal (Optional)

You can test that the service principal works by logging in with it:

```bash
az login --service-principal \
  --username <clientId> \
  --password <clientSecret> \
  --tenant <tenantId>
```

Replace `<clientId>`, `<clientSecret>`, and `<tenantId>` with values from your JSON output.

If successful, you'll see a confirmation message. Then logout:

```bash
az logout
```

And log back in with your personal account:

```bash
az login
```

## Managing Service Principals

### List All Service Principals

```bash
az ad sp list --show-mine --output table
```

### View Service Principal Details

```bash
az ad sp show --id <clientId>
```

### Delete a Service Principal

If you need to delete the service principal (e.g., to create a new one):

```bash
az ad sp delete --id <clientId>
```

**Warning**: Deleting the service principal will break any automation using it (GitHub Actions, Atlantis).

## Rotating Credentials (Security Best Practice)

For security, you should rotate service principal credentials periodically (e.g., every 90 days).

### Create a New Client Secret

```bash
az ad sp credential reset --id <clientId> --append
```

This creates a new secret while keeping the old one active (allowing zero-downtime rotation).

### Update GitHub Secrets

1. Copy the new `clientSecret` from the output
2. Update the `AZURE_CREDENTIALS` secret in GitHub with the new JSON
3. Test that deployments still work
4. Remove the old credential:

```bash
az ad sp credential delete --id <clientId> --key-id <old-key-id>
```

## Troubleshooting

### Issue: "Insufficient privileges to complete the operation"

**Cause**: Your Azure account doesn't have permission to create service principals.

**Solution**:
- You need "Application Administrator" or "Global Administrator" role in Azure AD
- Contact your Azure administrator to create the service principal for you
- Or ask them to grant you the necessary permissions

### Issue: "The subscription is not registered to use namespace 'Microsoft.Web'"

**Cause**: The required resource provider is not registered.

**Solution**:
```bash
az provider register --namespace Microsoft.Web
az provider show --namespace Microsoft.Web --query "registrationState"
```

Wait until the registration state is "Registered".

### Issue: Service principal authentication fails in GitHub Actions

**Cause**: Incorrect credentials or formatting in GitHub Secrets.

**Solution**:
1. Verify the JSON in `AZURE_CREDENTIALS` is valid (use a JSON validator)
2. Ensure there are no extra spaces or line breaks
3. Verify the `clientSecret` hasn't expired
4. Try creating a new service principal and updating the secret

### Issue: "The client with object id does not have authorization"

**Cause**: Service principal doesn't have the necessary permissions.

**Solution**:
```bash
# Re-assign the Contributor role
az role assignment create \
  --assignee <clientId> \
  --role Contributor \
  --scope /subscriptions/<subscription-id>
```

### Issue: Can't find the service principal after creation

**Cause**: Service principal creation may take a few minutes to propagate.

**Solution**:
- Wait 2-3 minutes and try again
- Verify you're looking in the correct Azure AD tenant
- Use the `clientId` to search instead of the display name

## Security Best Practices

1. **Never commit credentials to Git**:
   - Always use GitHub Secrets or environment variables
   - Add credential files to `.gitignore`

2. **Use minimal permissions**:
   - Grant only the permissions needed for the task
   - Consider custom roles for production environments

3. **Rotate credentials regularly**:
   - Set a reminder to rotate credentials every 90 days
   - Use Azure Key Vault for production environments

4. **Monitor service principal activity**:
   - Review Azure Activity Logs regularly
   - Set up alerts for suspicious activity

5. **Use separate service principals**:
   - Use different service principals for different environments (dev, staging, prod)
   - Use different service principals for different tools (GitHub Actions, Atlantis)

6. **Enable MFA for your personal account**:
   - Even though the service principal doesn't use MFA, your personal account should
   - This protects against unauthorized service principal creation

## Next Steps

After creating the service principal:

1. **Configure GitHub Secrets**: Follow the [GitHub Setup Guide](./github-setup.md)
2. **Test the CI/CD Pipeline**: Push code to trigger a deployment
3. **Configure Atlantis** (optional): Follow the [Atlantis Setup Guide](./atlantis-setup.md)

## Additional Resources

- [Azure Service Principal Documentation](https://docs.microsoft.com/azure/active-directory/develop/app-objects-and-service-principals)
- [Azure CLI Reference](https://docs.microsoft.com/cli/azure/)
- [Azure RBAC Documentation](https://docs.microsoft.com/azure/role-based-access-control/)
- [GitHub Actions Azure Login](https://github.com/Azure/login)
