# Setup Checklist

Use this checklist to ensure your CI/CD pipeline is properly configured.

## ✅ Pre-Deployment Checklist

### 1. Local Testing
- [ ] Dependencies installed: `composer install`
- [ ] All tests passing: `composer test`
- [ ] PHP syntax valid: `find includes public -name "*.php" -exec php -l {} \;`

### 2. Azure Setup
- [ ] Azure account created
- [ ] Azure subscription active
- [ ] Service Principal created
- [ ] Service Principal has Contributor role
- [ ] Terraform backend storage account created
- [ ] Terraform backend container created

### 3. GitHub Secrets Configuration
- [ ] `ARM_CLIENT_ID` configured
- [ ] `ARM_CLIENT_SECRET` configured
- [ ] `ARM_SUBSCRIPTION_ID` configured
- [ ] `ARM_TENANT_ID` configured
- [ ] `AZURE_CREDENTIALS` configured (JSON format)
- [ ] `TERRAFORM_BACKEND_RESOURCE_GROUP` configured
- [ ] `TERRAFORM_BACKEND_STORAGE_ACCOUNT` configured
- [ ] `TERRAFORM_BACKEND_CONTAINER` configured
- [ ] `TERRAFORM_BACKEND_KEY` configured
- [ ] `INFRACOST_API_KEY` configured

### 4. Infracost Setup
- [ ] Account created at infracost.io
- [ ] API key obtained
- [ ] API key added to GitHub secrets

### 5. Terraform Configuration
- [ ] `terraform/terraform.tfvars` created
- [ ] `app_name` set (globally unique)
- [ ] `location` set
- [ ] `resource_group_name` set

## ✅ First Deployment Checklist

### 1. Create Test Branch
```bash
git checkout -b test-pipeline
```

### 2. Make Small Change
```bash
echo "# Test" >> test.md
git add test.md
git commit -m "Test pipeline"
```

### 3. Push and Create PR
```bash
git push origin test-pipeline
```
- [ ] Create pull request on GitHub
- [ ] Wait for pipeline to run

### 4. Verify Pipeline Stages
- [ ] Validation stage passes
- [ ] Terraform stage passes
- [ ] Infracost comment appears on PR
- [ ] No errors in logs

### 5. Review Infracost Output
- [ ] Cost breakdown visible
- [ ] Costs are as expected
- [ ] No unexpected resources

### 6. Merge to Main
- [ ] PR approved (if required)
- [ ] All checks passing
- [ ] Merge pull request

### 7. Verify Deployment
- [ ] Validation stage passes
- [ ] Terraform stage passes
- [ ] Terraform apply completes (if changes)
- [ ] Deploy stage completes
- [ ] Health check passes
- [ ] Application accessible at Azure URL

## ✅ Post-Deployment Checklist

### 1. Verify Application
- [ ] Home page loads: `https://your-app.azurewebsites.net/`
- [ ] Curriculum page works
- [ ] Faculty page works
- [ ] Contact page works
- [ ] Language switcher works

### 2. Check Azure Resources
- [ ] Resource group exists
- [ ] App Service Plan created
- [ ] App Service created
- [ ] Logs enabled

### 3. Monitor Pipeline
- [ ] GitHub Actions tab shows successful run
- [ ] Deployment summary visible
- [ ] No warnings in logs

### 4. Test Future Changes
- [ ] Create new branch
- [ ] Make a change
- [ ] Create PR
- [ ] Verify pipeline runs
- [ ] Merge and deploy

## 🔧 Troubleshooting Checklist

If something fails, check:

### Validation Failures
- [ ] Run tests locally: `composer test`
- [ ] Check PHP syntax: `php -l filename.php`
- [ ] Review error messages in Actions log

### Terraform Failures
- [ ] Verify all secrets are set
- [ ] Check Terraform format: `terraform fmt -check`
- [ ] Validate locally: `terraform validate`
- [ ] Review Azure credentials

### Deployment Failures
- [ ] Check Azure App Service logs
- [ ] Verify app name is unique
- [ ] Check service principal permissions
- [ ] Review deployment logs in Actions

### Infracost Issues
- [ ] Verify API key is valid
- [ ] Check Infracost account is active
- [ ] Review Infracost step logs

## 📚 Documentation Reference

- **Testing**: [docs/testing-guide.md](docs/testing-guide.md)
- **Pipeline**: [docs/ci-cd-pipeline.md](docs/ci-cd-pipeline.md)
- **Secrets**: [docs/github-secrets-setup.md](docs/github-secrets-setup.md)
- **Troubleshooting**: [docs/troubleshooting.md](docs/troubleshooting.md)

## 🎯 Quick Commands

```bash
# Run tests
composer test

# Check PHP syntax
find includes public -name "*.php" -exec php -l {} \;

# Format Terraform
cd terraform && terraform fmt -recursive

# Validate Terraform
cd terraform && terraform validate

# Create test branch
git checkout -b test-pipeline

# Push changes
git add . && git commit -m "Your message" && git push
```

## ✨ Success Criteria

Your setup is complete when:
- ✅ All tests pass locally
- ✅ Pull request pipeline runs successfully
- ✅ Infracost comment appears on PR
- ✅ Merge to main triggers deployment
- ✅ Application is accessible on Azure
- ✅ Health check passes

---

**Need Help?** Check the documentation in `docs/` or review [PIPELINE-SETUP-SUMMARY.md](PIPELINE-SETUP-SUMMARY.md)
