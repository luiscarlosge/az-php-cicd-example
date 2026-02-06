# Troubleshooting Guide

This guide provides solutions to common issues you may encounter when developing, deploying, or running the Azure PHP CI/CD Portal.

## Table of Contents

1. [PHP Syntax Errors](#php-syntax-errors)
2. [Azure Authentication Failures](#azure-authentication-failures)
3. [Terraform State Lock Issues](#terraform-state-lock-issues)
4. [GitHub Actions Workflow Failures](#github-actions-workflow-failures)
5. [Azure Free Tier Quota Exceeded](#azure-free-tier-quota-exceeded)
6. [App Service Name Conflicts](#app-service-name-conflicts)
7. [Deployment Failures](#deployment-failures)
8. [Local Development Issues](#local-development-issues)
9. [Performance Issues](#performance-issues)

---

## PHP Syntax Errors

### Symptoms

- GitHub Actions validation job fails
- Local PHP server shows parse errors
- White screen when accessing pages
- Error messages like "Parse error: syntax error, unexpected..."

### Common Causes and Solutions

#### Missing Semicolons

**Error:**
```
Parse error: syntax error, unexpected 'echo' (T_ECHO), expecting ',' or ';'
```

**Solution:**
```php
// Wrong
echo "Hello World"
echo "Another line";

// Correct
echo "Hello World";
echo "Another line";
```

#### Mismatched Brackets/Parentheses

**Error:**
```
Parse error: syntax error, unexpected end of file
```

**Solution:**
Check that all opening brackets have matching closing brackets:
```php
// Wrong
if ($condition) {
    echo "Test";
// Missing closing brace

// Correct
if ($condition) {
    echo "Test";
}
```

#### Incorrect String Quotes

**Error:**
```
Parse error: syntax error, unexpected 'string' (T_STRING)
```

**Solution:**
```php
// Wrong
echo 'It's a test';  // Single quote inside single quotes

// Correct
echo "It's a test";  // Use double quotes
echo 'It\'s a test'; // Or escape the quote
```

#### Undefined Variables

**Error:**
```
Warning: Undefined variable $variable_name
```

**Solution:**
```php
// Wrong
echo $undefined_variable;

// Correct
$defined_variable = "value";
echo $defined_variable;

// Or check if defined
if (isset($variable)) {
    echo $variable;
}
```

### Debugging PHP Syntax Errors

1. **Check the error message** - it usually tells you the line number
2. **Use PHP linter locally:**
   ```bash
   php -l public/index.php
   ```
3. **Enable error display** (development only):
   ```php
   ini_set('display_errors', '1');
   error_reporting(E_ALL);
   ```
4. **Check your IDE** - most IDEs highlight syntax errors
5. **Review recent changes** - the error is likely in code you just modified

---

## Azure Authentication Failures

### Symptoms

- GitHub Actions deploy job fails with "Authentication failed"
- Terraform commands fail with "Error: building account"
- Error messages like "AADSTS700016: Application not found"

### Common Causes and Solutions

#### Invalid Service Principal Credentials

**Error:**
```
Error: AADSTS7000215: Invalid client secret is provided
```

**Solution:**

1. Verify the service principal credentials are correct:
   ```bash
   az login --service-principal \
     --username <client-id> \
     --password <client-secret> \
     --tenant <tenant-id>
   ```

2. If login fails, create a new service principal:
   ```bash
   az ad sp create-for-rbac \
     --name "github-actions-sp-new" \
     --role contributor \
     --scopes /subscriptions/<subscription-id> \
     --sdk-auth
   ```

3. Update GitHub Secrets with new credentials

#### Expired Service Principal Secret

**Error:**
```
Error: AADSTS7000222: The provided client secret keys are expired
```

**Solution:**

1. Reset the service principal credentials:
   ```bash
   az ad sp credential reset --id <client-id> --append
   ```

2. Update `AZURE_CREDENTIALS` secret in GitHub with new JSON

#### Insufficient Permissions

**Error:**
```
Error: The client does not have authorization to perform action
```

**Solution:**

1. Verify service principal has Contributor role:
   ```bash
   az role assignment list --assignee <client-id> --output table
   ```

2. If missing, assign the role:
   ```bash
   az role assignment create \
     --assignee <client-id> \
     --role Contributor \
     --scope /subscriptions/<subscription-id>
   ```

#### Incorrect Subscription

**Error:**
```
Error: Subscription not found
```

**Solution:**

1. Verify the subscription ID in your credentials:
   ```bash
   az account list --output table
   ```

2. Update the service principal scope if needed:
   ```bash
   az ad sp create-for-rbac \
     --name "github-actions-sp" \
     --role contributor \
     --scopes /subscriptions/<correct-subscription-id> \
     --sdk-auth
   ```

### Credential Rotation Best Practices

To avoid authentication issues:

1. **Set calendar reminders** to rotate credentials every 90 days
2. **Test new credentials** before deleting old ones
3. **Use Azure Key Vault** for production environments
4. **Monitor service principal activity** in Azure Activity Logs

---

## Terraform State Lock Issues

### Symptoms

- Terraform commands hang or timeout
- Error message: "Error: Error acquiring the state lock"
- Multiple Terraform operations running simultaneously

### Understanding State Locks

Terraform uses state locks to prevent concurrent operations that could corrupt the state file. When a lock is acquired, other operations must wait.

### Common Causes and Solutions

#### Previous Operation Didn't Release Lock

**Error:**
```
Error: Error acquiring the state lock
Lock Info:
  ID:        abc123-def456-ghi789
  Path:      terraform.tfstate
  Operation: OperationTypeApply
  Who:       user@hostname
  Version:   1.5.0
  Created:   2024-01-01 12:00:00 UTC
```

**Solution:**

1. **Wait for the operation to complete** - if another operation is genuinely running

2. **Force unlock** if the operation failed without releasing the lock:
   ```bash
   cd terraform
   terraform force-unlock abc123-def456-ghi789
   ```
   Replace `abc123-def456-ghi789` with the Lock ID from the error message.

3. **Verify no operations are running** before force unlocking:
   - Check GitHub Actions for running workflows
   - Check if anyone else is running Terraform

#### Concurrent Terraform Operations

**Error:**
```
Error: Another Terraform operation is already running
```

**Solution:**

1. Wait for the current operation to complete
2. Check GitHub Actions logs to see what's running
3. If stuck, force unlock (use with caution):
   ```bash
   cd terraform
   terraform force-unlock <LOCK_ID>
   ```

#### Remote State Backend Issues

**Error:**
```
Error: Failed to get existing workspaces: storage: service returned error
```

**Solution:**

1. Verify Azure Storage account exists and is accessible
2. Check storage account access keys are correct
3. Verify network connectivity to Azure
4. Check storage account isn't locked or deleted

### Preventing State Lock Issues

1. **Avoid concurrent operations** - coordinate with team members
2. **Use GitHub Actions** - workflows prevent concurrent runs automatically
3. **Set up proper CI/CD** - ensure only one deployment runs at a time
4. **Use remote state** - local state files can't be locked across machines

---

## GitHub Actions Workflow Failures

### Symptoms

- Red X on commits in GitHub
- Workflow runs show "Failed" status
- Email notifications about failed workflows

### Common Causes and Solutions

#### Validation Job Failures

**Error:**
```
PHP Parse error: syntax error, unexpected...
```

**Solution:**

1. Fix PHP syntax errors (see [PHP Syntax Errors](#php-syntax-errors))
2. Test locally before pushing:
   ```bash
   find public includes -name "*.php" -exec php -l {} \;
   ```

#### Deployment Job Failures

**Error:**
```
Error: No such host is known (your-app.azurewebsites.net)
```

**Solution:**

1. Verify Azure resources exist:
   ```bash
   az webapp list --output table
   ```

2. If missing, deploy infrastructure with Terraform:
   ```bash
   cd terraform
   terraform init
   terraform apply
   ```

3. Verify `AZURE_APP_NAME` secret matches actual App Service name

#### Secret Not Found

**Error:**
```
Error: Secret AZURE_CREDENTIALS not found
```

**Solution:**

1. Go to GitHub repository → Settings → Secrets → Actions
2. Verify secrets exist:
   - `AZURE_CREDENTIALS`
   - `AZURE_APP_NAME`
3. Add missing secrets (see [GitHub Setup Guide](./github-setup.md))

#### Timeout Errors

**Error:**
```
Error: The operation was canceled.
```

**Solution:**

1. Check Azure service health: https://status.azure.com/
2. Retry the workflow (click "Re-run jobs" in GitHub Actions)
3. If persistent, check network connectivity to Azure
4. Consider increasing timeout in workflow file:
   ```yaml
   timeout-minutes: 30
   ```

#### Workflow Syntax Errors

**Error:**
```
Invalid workflow file: .github/workflows/deploy.yml
```

**Solution:**

1. Validate YAML syntax using an online validator
2. Check indentation (YAML is whitespace-sensitive)
3. Verify all required fields are present
4. Review GitHub Actions documentation for correct syntax

### Debugging Workflow Failures

1. **View workflow logs:**
   - Go to Actions tab in GitHub
   - Click on the failed workflow run
   - Expand failed steps to see detailed logs

2. **Enable debug logging:**
   Add secrets to your repository:
   - `ACTIONS_STEP_DEBUG` = `true`
   - `ACTIONS_RUNNER_DEBUG` = `true`

3. **Test locally:**
   - Use `act` to run GitHub Actions locally: https://github.com/nektos/act

4. **Check workflow status:**
   ```bash
   gh run list --workflow=deploy.yml
   gh run view <run-id>
   ```

---

## Azure Free Tier Quota Exceeded

### Symptoms

- App becomes unresponsive or slow
- Error: "Quota exceeded for this resource"
- HTTP 403 or 503 errors
- Azure Portal shows quota warnings

### Understanding Free Tier Limits

Azure App Service F1 tier has these limits:
- **CPU**: 60 minutes per day
- **Memory**: 1 GB
- **Storage**: 1 GB
- **Bandwidth**: 165 MB outbound per day

### Common Causes and Solutions

#### CPU Quota Exceeded

**Symptoms:**
- App stops responding
- Error: "CPU quota exceeded"

**Solution:**

1. **Wait for quota reset** (resets daily at midnight UTC)

2. **Optimize PHP code:**
   - Reduce unnecessary processing
   - Cache static content
   - Optimize database queries (if added later)

3. **Upgrade to paid tier** (if needed):
   ```bash
   az appservice plan update \
     --name <plan-name> \
     --resource-group <rg-name> \
     --sku B1
   ```

#### Bandwidth Quota Exceeded

**Symptoms:**
- App becomes inaccessible
- Error: "Bandwidth quota exceeded"

**Solution:**

1. **Wait for quota reset** (resets daily)

2. **Optimize assets:**
   - Compress images
   - Minify CSS/JS
   - Use CDN for static assets (if needed)

3. **Monitor bandwidth usage:**
   ```bash
   az monitor metrics list \
     --resource <app-service-resource-id> \
     --metric BytesSent \
     --output table
   ```

#### Storage Quota Exceeded

**Symptoms:**
- Deployment fails
- Error: "Not enough storage"

**Solution:**

1. **Clean up old files:**
   ```bash
   az webapp deployment source delete \
     --name <app-name> \
     --resource-group <rg-name>
   ```

2. **Reduce application size:**
   - Remove unnecessary files
   - Exclude vendor directory from deployment (install via Composer on Azure)

### Monitoring Quota Usage

1. **Azure Portal:**
   - Go to App Service → Metrics
   - View CPU Time, Data Out, File System Usage

2. **Azure CLI:**
   ```bash
   az monitor metrics list \
     --resource <app-service-resource-id> \
     --metric CpuTime,BytesSent,FileSystemUsage \
     --output table
   ```

3. **Set up alerts:**
   ```bash
   az monitor metrics alert create \
     --name cpu-quota-alert \
     --resource-group <rg-name> \
     --scopes <app-service-resource-id> \
     --condition "avg CpuTime > 3000" \
     --description "CPU quota approaching limit"
   ```

### Preventing Quota Issues

1. **Monitor usage regularly** - check metrics daily
2. **Optimize code** - reduce CPU and bandwidth usage
3. **Use caching** - cache static content and API responses
4. **Plan for growth** - upgrade to paid tier when needed
5. **Test load** - ensure app can handle expected traffic

---

## App Service Name Conflicts

### Symptoms

- Terraform apply fails with "Name already exists"
- Error: "The specified name is already in use"
- Cannot create App Service with desired name

### Understanding App Service Names

App Service names must be:
- Globally unique across all Azure
- 2-60 characters long
- Contain only letters, numbers, and hyphens
- Start and end with a letter or number

### Common Causes and Solutions

#### Name Already Taken

**Error:**
```
Error: A resource with the ID already exists
```

**Solution:**

1. **Choose a different name:**
   - Add a unique suffix: `my-app-12345`
   - Use your organization name: `myorg-php-portal`
   - Add environment: `php-portal-dev`

2. **Update Terraform variables:**
   ```bash
   cd terraform
   # Edit terraform.tfvars
   app_name = "my-unique-app-name-12345"
   ```

3. **Apply changes:**
   ```bash
   terraform apply
   ```

4. **Update GitHub Secret:**
   - Update `AZURE_APP_NAME` with new name

#### Name Contains Invalid Characters

**Error:**
```
Error: The name can only contain letters, numbers, and hyphens
```

**Solution:**

Use only valid characters:
```hcl
# Wrong
app_name = "my_app_name"  # Underscores not allowed
app_name = "my.app.name"  # Dots not allowed

# Correct
app_name = "my-app-name"  # Hyphens are allowed
```

#### Name Too Short or Too Long

**Error:**
```
Error: The name must be between 2 and 60 characters
```

**Solution:**

Adjust name length:
```hcl
# Too short
app_name = "a"  # Only 1 character

# Too long
app_name = "my-very-long-application-name-that-exceeds-sixty-characters-limit"

# Correct
app_name = "my-app-name"  # Between 2-60 characters
```

### Checking Name Availability

Before creating, check if a name is available:

```bash
az webapp list --query "[?name=='my-app-name']" --output table
```

If the command returns results, the name is taken.

### Naming Conventions

Use a consistent naming convention:

```
<project>-<environment>-<region>-<random>
```

Examples:
- `php-portal-prod-eastus-a1b2`
- `cloudcourse-dev-westeu-x9y8`
- `pgcc-staging-centralus-m3n4`

---

## Deployment Failures

### Symptoms

- GitHub Actions deploy job fails
- App Service shows old version of code
- Changes not reflected after deployment

### Common Causes and Solutions

#### Deployment Timeout

**Error:**
```
Error: Deployment timed out after 600 seconds
```

**Solution:**

1. **Increase timeout** in workflow:
   ```yaml
   - name: Deploy to Azure App Service
     uses: azure/webapps-deploy@v2
     timeout-minutes: 15
   ```

2. **Check Azure service health**

3. **Retry deployment**

#### Deployment Package Too Large

**Error:**
```
Error: The deployment package is too large
```

**Solution:**

1. **Exclude unnecessary files** in `.gitignore`:
   ```
   vendor/
   node_modules/
   tests/
   .git/
   ```

2. **Use .deployment file** to specify what to deploy:
   ```
   [config]
   project = public
   ```

#### App Service Not Started

**Error:**
```
Error: The app is not running
```

**Solution:**

1. **Start the App Service:**
   ```bash
   az webapp start \
     --name <app-name> \
     --resource-group <rg-name>
   ```

2. **Check App Service status:**
   ```bash
   az webapp show \
     --name <app-name> \
     --resource-group <rg-name> \
     --query state
   ```

#### Incorrect Deployment Path

**Error:**
```
Error: Could not find index.php
```

**Solution:**

1. **Verify deployment package** includes `public/` directory

2. **Configure App Service** to use correct path:
   ```bash
   az webapp config set \
     --name <app-name> \
     --resource-group <rg-name> \
     --startup-file "public/index.php"
   ```

### Verifying Deployment

After deployment, verify:

1. **Check deployment logs:**
   ```bash
   az webapp log deployment show \
     --name <app-name> \
     --resource-group <rg-name>
   ```

2. **Access the app:**
   ```bash
   curl https://<app-name>.azurewebsites.net
   ```

3. **Check application logs:**
   ```bash
   az webapp log tail \
     --name <app-name> \
     --resource-group <rg-name>
   ```

---

## Local Development Issues

### PHP Server Won't Start

**Error:**
```
Failed to listen on localhost:8000
```

**Solution:**

1. **Check if port is in use:**
   ```bash
   # Windows
   netstat -ano | findstr :8000
   
   # macOS/Linux
   lsof -i :8000
   ```

2. **Use a different port:**
   ```bash
   php -S localhost:3000 -t public
   ```

3. **Kill the process using the port:**
   ```bash
   # Windows
   taskkill /PID <process-id> /F
   
   # macOS/Linux
   kill -9 <process-id>
   ```

### Changes Not Reflecting

**Symptoms:**
- Code changes don't appear in browser
- Old content still showing

**Solution:**

1. **Hard refresh browser:** `Ctrl+Shift+R` (Windows/Linux) or `Cmd+Shift+R` (macOS)
2. **Clear browser cache**
3. **Restart PHP server**
4. **Check you're editing the correct file**
5. **Verify file is saved**

### Composer Install Fails

**Error:**
```
Your requirements could not be resolved to an installable set of packages
```

**Solution:**

1. **Update Composer:**
   ```bash
   composer self-update
   ```

2. **Clear Composer cache:**
   ```bash
   composer clear-cache
   ```

3. **Delete vendor directory and reinstall:**
   ```bash
   rm -rf vendor composer.lock
   composer install
   ```

---

## Performance Issues

### Slow Page Load Times

**Symptoms:**
- Pages take several seconds to load
- High CPU usage
- Slow response times

**Solutions:**

1. **Enable OPcache** in `php.ini`:
   ```ini
   opcache.enable=1
   opcache.memory_consumption=128
   opcache.max_accelerated_files=10000
   ```

2. **Optimize includes:**
   - Use `require_once` instead of `require` for files included multiple times
   - Minimize number of includes

3. **Profile with Xdebug:**
   ```bash
   php -d xdebug.mode=profile -S localhost:8000 -t public
   ```

4. **Check Azure metrics** for resource constraints

### High Memory Usage

**Symptoms:**
- Out of memory errors
- App crashes or restarts

**Solutions:**

1. **Increase PHP memory limit** (if possible):
   ```ini
   memory_limit = 256M
   ```

2. **Optimize code:**
   - Unset large variables when done
   - Avoid loading entire files into memory
   - Use generators for large datasets

3. **Monitor memory usage:**
   ```php
   echo memory_get_usage() / 1024 / 1024 . " MB\n";
   ```

---

## Getting Help

If you're still experiencing issues:

1. **Check Azure Status:** https://status.azure.com/
2. **Review GitHub Actions logs** for detailed error messages
3. **Check Azure App Service logs** via Portal or CLI
4. **Search GitHub Issues** for similar problems
5. **Create a GitHub Issue** with:
   - Detailed description of the problem
   - Steps to reproduce
   - Error messages and logs
   - Environment information (OS, PHP version, etc.)

## Additional Resources

- [Azure App Service Documentation](https://docs.microsoft.com/azure/app-service/)
- [PHP Documentation](https://www.php.net/docs.php)
- [Terraform Azure Provider](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs)
- [GitHub Actions Documentation](https://docs.github.com/actions)
- [Azure CLI Reference](https://docs.microsoft.com/cli/azure/)
