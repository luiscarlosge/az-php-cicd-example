# Atlantis Setup Guide

## Overview

Atlantis is a tool for automating Terraform workflows through pull requests. This guide covers multiple options for setting up Atlantis with your Azure PHP CI/CD Portal project.

## Table of Contents

1. [Self-Hosted Atlantis Setup](#self-hosted-atlantis-setup)
2. [Local Testing with ngrok](#local-testing-with-ngrok)
3. [GitHub Webhook Configuration](#github-webhook-configuration)
4. [Azure Authentication](#azure-authentication)
5. [Alternative: GitHub Actions with Terraform](#alternative-github-actions-with-terraform)

---

## Self-Hosted Atlantis Setup

### Prerequisites

- Docker installed on your host machine
- GitHub personal access token with repo permissions
- Azure service principal credentials

### Option 1: Running Atlantis with Docker

1. **Create a directory for Atlantis configuration:**

```bash
mkdir -p ~/atlantis-config
cd ~/atlantis-config
```

2. **Create an environment file** (`atlantis.env`):

```bash
# GitHub Configuration
ATLANTIS_GH_USER=your-github-username
ATLANTIS_GH_TOKEN=your-github-personal-access-token
ATLANTIS_GH_WEBHOOK_SECRET=your-webhook-secret
ATLANTIS_REPO_ALLOWLIST=github.com/your-org/your-repo

# Azure Authentication (Service Principal)
ARM_CLIENT_ID=your-service-principal-client-id
ARM_CLIENT_SECRET=your-service-principal-client-secret
ARM_SUBSCRIPTION_ID=your-azure-subscription-id
ARM_TENANT_ID=your-azure-tenant-id

# Atlantis Configuration
ATLANTIS_ATLANTIS_URL=http://your-public-url:4141
ATLANTIS_PORT=4141
```

3. **Run Atlantis container:**

```bash
docker run -d \
  --name atlantis \
  -p 4141:4141 \
  --env-file atlantis.env \
  -v ~/atlantis-config:/atlantis-data \
  ghcr.io/runatlantis/atlantis:latest \
  server \
  --gh-user=$ATLANTIS_GH_USER \
  --gh-token=$ATLANTIS_GH_TOKEN \
  --gh-webhook-secret=$ATLANTIS_GH_WEBHOOK_SECRET \
  --repo-allowlist=$ATLANTIS_REPO_ALLOWLIST
```

4. **Verify Atlantis is running:**

```bash
docker logs atlantis
```

You should see output indicating Atlantis is listening on port 4141.

### Option 2: Running Atlantis Binary

1. **Download the Atlantis binary:**

```bash
# For Linux
wget https://github.com/runatlantis/atlantis/releases/download/v0.27.0/atlantis_linux_amd64.zip
unzip atlantis_linux_amd64.zip

# For macOS
wget https://github.com/runatlantis/atlantis/releases/download/v0.27.0/atlantis_darwin_amd64.zip
unzip atlantis_darwin_amd64.zip

# For Windows
# Download from: https://github.com/runatlantis/atlantis/releases/download/v0.27.0/atlantis_windows_amd64.zip
```

2. **Set environment variables:**

```bash
export ATLANTIS_GH_USER=your-github-username
export ATLANTIS_GH_TOKEN=your-github-personal-access-token
export ATLANTIS_GH_WEBHOOK_SECRET=your-webhook-secret
export ATLANTIS_REPO_ALLOWLIST=github.com/your-org/your-repo

# Azure credentials
export ARM_CLIENT_ID=your-service-principal-client-id
export ARM_CLIENT_SECRET=your-service-principal-client-secret
export ARM_SUBSCRIPTION_ID=your-azure-subscription-id
export ARM_TENANT_ID=your-azure-tenant-id
```

3. **Run Atlantis:**

```bash
./atlantis server \
  --gh-user=$ATLANTIS_GH_USER \
  --gh-token=$ATLANTIS_GH_TOKEN \
  --gh-webhook-secret=$ATLANTIS_GH_WEBHOOK_SECRET \
  --repo-allowlist=$ATLANTIS_REPO_ALLOWLIST \
  --port=4141
```

---

## Local Testing with ngrok

For local development and testing, you can use ngrok to expose your local Atlantis instance to the internet.

### Setup Steps

1. **Install ngrok:**

```bash
# Download from https://ngrok.com/download
# Or use package managers:

# macOS
brew install ngrok

# Windows (with Chocolatey)
choco install ngrok

# Linux (snap)
snap install ngrok
```

2. **Authenticate ngrok** (sign up for free account at ngrok.com):

```bash
ngrok authtoken YOUR_NGROK_AUTH_TOKEN
```

3. **Start Atlantis locally** (using Docker or binary as described above)

4. **Start ngrok tunnel:**

```bash
ngrok http 4141
```

5. **Note the forwarding URL** from ngrok output:

```
Forwarding    https://abc123.ngrok.io -> http://localhost:4141
```

6. **Update your Atlantis environment** with the ngrok URL:

```bash
export ATLANTIS_ATLANTIS_URL=https://abc123.ngrok.io
```

7. **Restart Atlantis** with the new URL

8. **Configure GitHub webhook** (see next section) using the ngrok URL

### Important Notes for ngrok

- Free ngrok URLs change each time you restart ngrok
- You'll need to update the GitHub webhook URL when the ngrok URL changes
- For production use, consider a permanent hosting solution
- ngrok free tier has connection limits

---

## GitHub Webhook Configuration

Atlantis requires a webhook to receive pull request events from GitHub.

### Steps to Configure Webhook

1. **Navigate to your GitHub repository**

2. **Go to Settings → Webhooks → Add webhook**

3. **Configure the webhook:**

   - **Payload URL**: `https://your-atlantis-url:4141/events` (or ngrok URL)
   - **Content type**: `application/json`
   - **Secret**: Use the same value as `ATLANTIS_GH_WEBHOOK_SECRET`
   - **Which events**: Select "Let me select individual events"
     - Check: `Pull request reviews`
     - Check: `Pull requests`
     - Check: `Issue comments`
     - Check: `Push` (optional)

4. **Click "Add webhook"**

5. **Verify webhook delivery:**
   - GitHub will send a test ping
   - Check the "Recent Deliveries" tab to see if it succeeded
   - Check Atlantis logs for incoming webhook events

### Webhook URL Format

```
https://your-atlantis-domain:4141/events
```

For ngrok:
```
https://abc123.ngrok.io/events
```

---

## Azure Authentication

Atlantis needs Azure credentials to execute Terraform commands that create/modify Azure resources.

### Service Principal Environment Variables

Atlantis uses the following environment variables for Azure authentication:

```bash
ARM_CLIENT_ID=<service-principal-client-id>
ARM_CLIENT_SECRET=<service-principal-client-secret>
ARM_SUBSCRIPTION_ID=<azure-subscription-id>
ARM_TENANT_ID=<azure-tenant-id>
```

### Creating a Service Principal

If you haven't created a service principal yet, follow these steps:

1. **Login to Azure CLI:**

```bash
az login
```

2. **Create service principal:**

```bash
az ad sp create-for-rbac \
  --name "atlantis-terraform-sp" \
  --role Contributor \
  --scopes /subscriptions/<your-subscription-id>
```

3. **Save the output** - you'll need these values:

```json
{
  "appId": "<client-id>",
  "displayName": "atlantis-terraform-sp",
  "password": "<client-secret>",
  "tenant": "<tenant-id>"
}
```

4. **Set environment variables:**

```bash
export ARM_CLIENT_ID=<appId>
export ARM_CLIENT_SECRET=<password>
export ARM_SUBSCRIPTION_ID=<your-subscription-id>
export ARM_TENANT_ID=<tenant>
```

### Minimal Required Permissions

For security best practices, grant only the permissions needed:

- **Contributor** role on the resource group (not subscription-wide)
- Or create a custom role with specific permissions:
  - `Microsoft.Resources/deployments/*`
  - `Microsoft.Web/serverfarms/*`
  - `Microsoft.Web/sites/*`

```bash
# Assign contributor role to specific resource group
az role assignment create \
  --assignee <service-principal-client-id> \
  --role Contributor \
  --scope /subscriptions/<subscription-id>/resourceGroups/<resource-group-name>
```

### Verifying Azure Authentication

Test that Atlantis can authenticate with Azure:

1. **Create a test PR** with a Terraform change
2. **Check Atlantis logs** for authentication errors
3. **Verify plan output** shows Azure resources

---

## Alternative: GitHub Actions with Terraform

If self-hosting Atlantis is not feasible, you can use GitHub Actions to achieve similar Terraform automation.

### Advantages of GitHub Actions Approach

- No infrastructure to maintain
- Free for public repositories (2,000 minutes/month)
- Integrated with GitHub
- Easier to set up for small teams

### Disadvantages

- Less interactive than Atlantis
- No automatic plan comments on PRs (requires additional setup)
- Manual approval process different from Atlantis

### Implementation

Create `.github/workflows/terraform.yml`:

```yaml
name: Terraform

on:
  pull_request:
    paths:
      - 'terraform/**'
  push:
    branches:
      - main
    paths:
      - 'terraform/**'

jobs:
  terraform-plan:
    name: Terraform Plan
    runs-on: ubuntu-latest
    if: github.event_name == 'pull_request'
    
    steps:
      - name: Checkout code
        uses: actions/checkout@v3
      
      - name: Setup Terraform
        uses: hashicorp/setup-terraform@v2
        with:
          terraform_version: 1.5.0
      
      - name: Terraform Init
        working-directory: terraform
        run: terraform init
        env:
          ARM_CLIENT_ID: ${{ secrets.ARM_CLIENT_ID }}
          ARM_CLIENT_SECRET: ${{ secrets.ARM_CLIENT_SECRET }}
          ARM_SUBSCRIPTION_ID: ${{ secrets.ARM_SUBSCRIPTION_ID }}
          ARM_TENANT_ID: ${{ secrets.ARM_TENANT_ID }}
      
      - name: Terraform Format Check
        working-directory: terraform
        run: terraform fmt -check
      
      - name: Terraform Validate
        working-directory: terraform
        run: terraform validate
      
      - name: Terraform Plan
        working-directory: terraform
        run: terraform plan -no-color
        env:
          ARM_CLIENT_ID: ${{ secrets.ARM_CLIENT_ID }}
          ARM_CLIENT_SECRET: ${{ secrets.ARM_CLIENT_SECRET }}
          ARM_SUBSCRIPTION_ID: ${{ secrets.ARM_SUBSCRIPTION_ID }}
          ARM_TENANT_ID: ${{ secrets.ARM_TENANT_ID }}
  
  terraform-apply:
    name: Terraform Apply
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/main' && github.event_name == 'push'
    
    steps:
      - name: Checkout code
        uses: actions/checkout@v3
      
      - name: Setup Terraform
        uses: hashicorp/setup-terraform@v2
        with:
          terraform_version: 1.5.0
      
      - name: Terraform Init
        working-directory: terraform
        run: terraform init
        env:
          ARM_CLIENT_ID: ${{ secrets.ARM_CLIENT_ID }}
          ARM_CLIENT_SECRET: ${{ secrets.ARM_CLIENT_SECRET }}
          ARM_SUBSCRIPTION_ID: ${{ secrets.ARM_SUBSCRIPTION_ID }}
          ARM_TENANT_ID: ${{ secrets.ARM_TENANT_ID }}
      
      - name: Terraform Apply
        working-directory: terraform
        run: terraform apply -auto-approve
        env:
          ARM_CLIENT_ID: ${{ secrets.ARM_CLIENT_ID }}
          ARM_CLIENT_SECRET: ${{ secrets.ARM_CLIENT_SECRET }}
          ARM_SUBSCRIPTION_ID: ${{ secrets.ARM_SUBSCRIPTION_ID }}
          ARM_TENANT_ID: ${{ secrets.ARM_TENANT_ID }}
```

### Required GitHub Secrets

Add these secrets to your GitHub repository (Settings → Secrets → Actions):

- `ARM_CLIENT_ID`
- `ARM_CLIENT_SECRET`
- `ARM_SUBSCRIPTION_ID`
- `ARM_TENANT_ID`

### Workflow Behavior

- **On Pull Request**: Runs `terraform plan` and validates configuration
- **On Push to Main**: Runs `terraform apply` to deploy changes
- All Terraform operations use Azure service principal authentication

---

## Atlantis Commands

Once Atlantis is set up, you can use these commands in pull request comments:

### Basic Commands

- `atlantis plan` - Run terraform plan
- `atlantis apply` - Run terraform apply (requires approval)
- `atlantis unlock` - Unlock the state if locked

### Advanced Commands

- `atlantis plan -p azure-infrastructure` - Plan specific project
- `atlantis apply -p azure-infrastructure` - Apply specific project
- `atlantis plan -- -var="app_name=custom-name"` - Pass Terraform variables

### Workflow Example

1. Create a branch with Terraform changes
2. Open a pull request
3. Atlantis automatically runs `terraform plan`
4. Review the plan output in PR comments
5. Request approval from team member
6. Once approved, comment `atlantis apply`
7. Atlantis applies the changes
8. Merge the pull request

---

## Troubleshooting

### Atlantis Not Responding to PRs

- Check webhook delivery in GitHub (Settings → Webhooks)
- Verify Atlantis logs for incoming events
- Ensure repository is in `ATLANTIS_REPO_ALLOWLIST`
- Check GitHub token has correct permissions

### Azure Authentication Failures

- Verify service principal credentials are correct
- Check service principal has required permissions
- Ensure environment variables are set correctly
- Test authentication with Azure CLI: `az login --service-principal`

### Terraform State Lock Issues

- Check if another Atlantis operation is running
- Manually unlock state: `atlantis unlock`
- Or use Terraform CLI: `terraform force-unlock <lock-id>`

### ngrok Connection Issues

- Free tier has connection limits
- URL changes on restart - update webhook
- Consider paid ngrok plan for stability

---

## Security Best Practices

1. **Use webhook secrets** to verify GitHub requests
2. **Limit service principal permissions** to minimum required
3. **Use repo allowlist** to restrict which repositories Atlantis can access
4. **Rotate credentials regularly** (service principal, GitHub token)
5. **Enable HTTPS** for Atlantis (use reverse proxy like nginx)
6. **Store sensitive values** in environment variables, not in code
7. **Review Terraform plans** before applying
8. **Use branch protection** to require approvals before merge

---

## Additional Resources

- [Atlantis Documentation](https://www.runatlantis.io/docs/)
- [Atlantis GitHub Repository](https://github.com/runatlantis/atlantis)
- [Azure Service Principal Documentation](https://docs.microsoft.com/en-us/azure/active-directory/develop/howto-create-service-principal-portal)
- [ngrok Documentation](https://ngrok.com/docs)
- [Terraform Azure Provider](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs)
