# GitHub Repository Setup Guide

This guide walks you through setting up a GitHub repository for the PHP CI/CD Portal project, including configuring GitHub Secrets for Azure authentication and enabling GitHub Actions.

## Prerequisites

- A GitHub account (free tier is sufficient)
- Azure Service Principal credentials (see [Service Principal Setup Guide](./service-principal.md))
- Your Azure App Service name (or the name you plan to use)

## Step 1: Create a GitHub Account (If Needed)

If you don't already have a GitHub account:

1. Go to https://github.com
2. Click "Sign up" in the top-right corner
3. Follow the prompts to create your account:
   - Enter your email address
   - Create a password
   - Choose a username
   - Verify your account via email

4. Choose the Free plan (sufficient for this project)

### GitHub Free Tier Benefits

The free tier includes:
- Unlimited public repositories
- Unlimited private repositories
- 2,000 GitHub Actions minutes per month (for private repos)
- Unlimited GitHub Actions minutes for public repositories
- GitHub Pages hosting
- Community support

**Important**: For this project, we'll use a **public repository** to get unlimited GitHub Actions minutes.

## Step 2: Create a New Repository

1. Log in to GitHub
2. Click the "+" icon in the top-right corner
3. Select "New repository"

### Repository Configuration

Fill in the repository details:

- **Repository name**: `azure-php-cicd-portal` (or your preferred name)
- **Description**: "PHP portal for Post Graduate Course in Cloud Computing with Azure CI/CD"
- **Visibility**: Select **Public**
  - This gives you unlimited GitHub Actions minutes
  - The code doesn't contain sensitive information (credentials are in Secrets)
- **Initialize repository**:
  - ☐ Do NOT check "Add a README file" (we'll push our existing code)
  - ☐ Do NOT add .gitignore (we already have one)
  - ☐ Do NOT choose a license (optional, can add later)

4. Click "Create repository"

### Repository Created

You'll see a page with instructions for pushing existing code. Keep this page open - we'll use these commands later.

## Step 3: Configure GitHub Secrets

GitHub Secrets allow you to store sensitive information (like Azure credentials) securely. These secrets are encrypted and only exposed to GitHub Actions workflows.

### Navigate to Repository Settings

1. Go to your repository on GitHub
2. Click the "Settings" tab (top menu)
3. In the left sidebar, scroll down to "Security"
4. Click "Secrets and variables"
5. Click "Actions"

You should now see the "Actions secrets and variables" page.

### Add AZURE_CREDENTIALS Secret

This secret contains the service principal credentials for authenticating with Azure.

1. Click the "New repository secret" button

2. Fill in the secret details:
   - **Name**: `AZURE_CREDENTIALS`
   - **Value**: Paste the entire JSON output from the service principal creation

   The JSON should look like this:
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

3. Click "Add secret"

**Important**: 
- Ensure the JSON is valid (no extra spaces, line breaks, or characters)
- Copy the entire JSON output from the `az ad sp create-for-rbac` command
- You can validate JSON format using an online JSON validator

### Add AZURE_APP_NAME Secret

This secret contains the name of your Azure App Service.

1. Click "New repository secret" again

2. Fill in the secret details:
   - **Name**: `AZURE_APP_NAME`
   - **Value**: Your App Service name (e.g., `my-php-portal-app`)

   **Note**: This should match the `app_name` variable in your Terraform configuration.

3. Click "Add secret"

### Verify Secrets

After adding both secrets, you should see them listed on the "Actions secrets" page:
- `AZURE_CREDENTIALS`
- `AZURE_APP_NAME`

**Security Note**: Once added, you cannot view the secret values again. You can only update or delete them.

## Step 4: Enable GitHub Actions

GitHub Actions should be enabled by default for new repositories, but let's verify:

1. In your repository, click the "Settings" tab
2. In the left sidebar, click "Actions" under "Code and automation"
3. Click "General"

### Actions Permissions

Ensure the following settings are configured:

- **Actions permissions**: Select "Allow all actions and reusable workflows"
  - This allows the repository to use GitHub Actions

- **Workflow permissions**: Select "Read and write permissions"
  - This allows workflows to commit changes if needed (optional for this project)

- **Allow GitHub Actions to create and approve pull requests**: Check this box
  - This is useful if you want to automate PR creation (optional)

4. Click "Save" if you made any changes

## Step 5: Push Your Code to GitHub

Now that the repository is set up, push your local code to GitHub.

### Initialize Git (If Not Already Done)

If you haven't initialized Git in your project directory:

```bash
cd /path/to/your/project
git init
```

### Add Remote Repository

Add your GitHub repository as the remote origin:

```bash
git remote add origin https://github.com/your-username/azure-php-cicd-portal.git
```

Replace `your-username` with your GitHub username and `azure-php-cicd-portal` with your repository name.

### Stage and Commit Files

```bash
git add .
git commit -m "Initial commit: PHP portal with Azure CI/CD"
```

### Push to GitHub

```bash
git branch -M main
git push -u origin main
```

**Note**: If you're using an older Git version, the default branch might be `master` instead of `main`. Adjust accordingly.

### Verify Push

1. Go to your repository on GitHub
2. Refresh the page
3. You should see all your project files

## Step 6: Verify GitHub Actions Workflow

After pushing your code, GitHub Actions should automatically detect the workflow file and start running.

### View Workflow Runs

1. In your repository, click the "Actions" tab
2. You should see a workflow run for your recent commit
3. Click on the workflow run to view details

### Workflow Status

The workflow will show:
- **Validation job**: Runs on all branches (validates PHP syntax)
- **Deployment job**: Only runs on the `main` branch (deploys to Azure)

### First Run Behavior

On the first push to `main`:
- The validation job should complete successfully
- The deployment job will run and attempt to deploy to Azure
- If Azure resources don't exist yet, the deployment may fail (this is expected)

**Next Step**: Deploy infrastructure using Terraform before the deployment can succeed.

## Step 7: Configure Branch Protection (Optional but Recommended)

Branch protection rules help maintain code quality by requiring reviews and status checks before merging.

### Set Up Branch Protection

1. Go to repository "Settings"
2. Click "Branches" in the left sidebar
3. Under "Branch protection rules", click "Add rule"

### Configure Protection Rules

- **Branch name pattern**: `main`
- **Protect matching branches**:
  - ☑ Require a pull request before merging
    - ☑ Require approvals: 1 (if working in a team)
  - ☑ Require status checks to pass before merging
    - Search for and select: `validate` (your validation job)
  - ☑ Require branches to be up to date before merging
  - ☐ Require conversation resolution before merging (optional)
  - ☐ Require signed commits (optional, for extra security)

4. Click "Create" or "Save changes"

### Benefits of Branch Protection

- Prevents direct pushes to `main` (forces use of pull requests)
- Ensures validation passes before merging
- Requires code review (if approvals are required)
- Maintains a clean commit history

## Step 8: Test the CI/CD Pipeline

Let's test that the GitHub Actions workflow is working correctly.

### Create a Test Branch

```bash
git checkout -b test-cicd
```

### Make a Small Change

Edit a file (e.g., `README.md`) and commit:

```bash
echo "Testing CI/CD pipeline" >> README.md
git add README.md
git commit -m "Test: Verify CI/CD pipeline"
git push origin test-cicd
```

### Create a Pull Request

1. Go to your repository on GitHub
2. You should see a banner suggesting to create a pull request
3. Click "Compare & pull request"
4. Add a title and description
5. Click "Create pull request"

### Verify Workflow Runs

1. In the pull request, scroll down to the "Checks" section
2. You should see the "validate" job running
3. Wait for it to complete (should show a green checkmark)

### Merge the Pull Request

1. If validation passes, click "Merge pull request"
2. Click "Confirm merge"
3. Optionally, delete the branch

### Verify Deployment

1. Go to the "Actions" tab
2. You should see a new workflow run for the merge to `main`
3. This time, both "validate" and "deploy" jobs should run
4. The deploy job will attempt to deploy to Azure

## Troubleshooting

### Issue: Secrets not found in workflow

**Symptoms**: Workflow fails with "Secret not found" or empty secret value.

**Solution**:
1. Verify secrets are added in "Settings" → "Secrets and variables" → "Actions"
2. Check secret names match exactly (case-sensitive): `AZURE_CREDENTIALS`, `AZURE_APP_NAME`
3. Ensure you're looking at repository secrets, not environment secrets
4. Re-add the secrets if necessary

### Issue: GitHub Actions workflow not running

**Symptoms**: No workflow runs appear in the "Actions" tab.

**Solution**:
1. Verify the workflow file exists at `.github/workflows/deploy.yml`
2. Check the workflow file syntax (YAML must be valid)
3. Ensure GitHub Actions is enabled in repository settings
4. Try pushing a new commit to trigger the workflow

### Issue: Deployment fails with authentication error

**Symptoms**: Deploy job fails with "Authentication failed" or "Invalid credentials".

**Solution**:
1. Verify the `AZURE_CREDENTIALS` secret contains valid JSON
2. Check that the service principal hasn't been deleted
3. Verify the service principal has "Contributor" role on the subscription
4. Try creating a new service principal and updating the secret

### Issue: Deployment fails with "App Service not found"

**Symptoms**: Deploy job fails with "Resource not found" or "App Service does not exist".

**Solution**:
1. Verify Azure resources are created (use Terraform to deploy infrastructure first)
2. Check that `AZURE_APP_NAME` matches the actual App Service name in Azure
3. Verify the App Service is in the same subscription as the service principal

### Issue: Workflow runs but doesn't deploy

**Symptoms**: Validation job runs, but deployment job is skipped.

**Solution**:
1. Check the workflow condition: `if: github.ref == 'refs/heads/main'`
2. Ensure you're pushing to the `main` branch (not `master` or another branch)
3. Verify the deployment job has `needs: validate` and validation passed

### Issue: Public repository concerns

**Symptoms**: Worried about code being public.

**Solution**:
- The code itself doesn't contain sensitive information
- All credentials are stored in GitHub Secrets (encrypted)
- If you prefer, you can use a private repository (but you'll have limited Actions minutes)
- For private repos, you get 2,000 minutes/month on the free tier

## Security Best Practices

1. **Never commit secrets to Git**:
   - Always use GitHub Secrets for sensitive data
   - Add credential files to `.gitignore`
   - Use tools like `git-secrets` to prevent accidental commits

2. **Rotate secrets regularly**:
   - Update service principal credentials every 90 days
   - Update GitHub Secrets after rotation

3. **Use environment-specific secrets**:
   - For production, consider using GitHub Environments
   - Separate secrets for dev, staging, and production

4. **Limit secret access**:
   - Only grant repository access to trusted collaborators
   - Use branch protection to control who can trigger deployments

5. **Monitor workflow runs**:
   - Review workflow logs regularly
   - Set up notifications for failed workflows
   - Investigate any suspicious activity

6. **Enable two-factor authentication**:
   - Enable 2FA on your GitHub account
   - Require 2FA for all organization members (if applicable)

## GitHub Actions Usage Monitoring

### Check Actions Minutes Usage

1. Go to your GitHub profile
2. Click "Settings"
3. Click "Billing and plans" in the left sidebar
4. Click "Plans and usage"
5. Scroll to "Actions & Packages"

Here you can see:
- Minutes used this month
- Minutes remaining
- Storage used

### Optimize Actions Usage

For public repositories, you have unlimited minutes, but for private repos:

1. **Cache dependencies**:
   - Use `actions/cache` to cache Composer dependencies
   - Reduces workflow execution time

2. **Limit workflow triggers**:
   - Only run on specific branches or paths
   - Use `paths` filter to run only when relevant files change

3. **Use self-hosted runners** (advanced):
   - Run workflows on your own infrastructure
   - Doesn't count against GitHub Actions minutes

## Next Steps

After setting up GitHub:

1. **Deploy Infrastructure**: Use Terraform to create Azure resources
   - See the main README for Terraform deployment instructions

2. **Configure Atlantis** (optional): Automate Terraform workflows
   - Follow the [Atlantis Setup Guide](./atlantis-setup.md)

3. **Test End-to-End**: Make a code change and verify it deploys automatically

4. **Set Up Monitoring**: Configure Azure Application Insights (optional)

## Additional Resources

- [GitHub Actions Documentation](https://docs.github.com/actions)
- [GitHub Secrets Documentation](https://docs.github.com/actions/security-guides/encrypted-secrets)
- [Azure Login Action](https://github.com/Azure/login)
- [Azure Web App Deploy Action](https://github.com/Azure/webapps-deploy)
- [GitHub Actions Billing](https://docs.github.com/billing/managing-billing-for-github-actions)
