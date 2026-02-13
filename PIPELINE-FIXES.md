# Pipeline Fixes - February 13, 2026

## Issues Fixed

### 1. Terraform Plan Exit Code Error

**Error:**
```
/home/runner/work/_temp/xxx.sh: line 3: [: -eq: unary operator expected
❌ Terraform plan failed!
```

**Root Cause:** 
The shell variable `$TF_EXIT` was not being set correctly when terraform plan succeeded (exit code 0). The `export` command in a subshell doesn't work as expected.

**Fix:**
Changed from:
```bash
terraform plan -out=tfplan -detailed-exitcode || export TF_EXIT=$?
if [ ${TF_EXIT} -eq 0 ]; then
```

To:
```bash
set +e
terraform plan -out=tfplan -detailed-exitcode
TF_EXIT=$?
set -e

if [ "$TF_EXIT" -eq 0 ]; then
```

This ensures the exit code is captured correctly in all scenarios.

### 2. App Service Name Not Found Error

**Error:**
```
Error: Deployment Failed, Error: app-name is a required input.
```

**Root Cause:**
The Terraform outputs were only being retrieved when infrastructure changes were detected. On subsequent deployments with no changes, the outputs weren't available to the deploy step.

**Fix:**

1. **Always retrieve outputs** in the Terraform stage (not just when changes detected):
```yaml
- name: Get Terraform Outputs
  id: terraform_output
  working-directory: ./terraform
  run: |
    # Always try to get outputs from current state
    APP_NAME=$(terraform output -raw app_service_name 2>/dev/null || echo "")
    APP_URL=$(terraform output -raw app_service_url 2>/dev/null || echo "")
    
    if [ -n "$APP_NAME" ]; then
      echo "app_service_name=$APP_NAME" >> $GITHUB_OUTPUT
      echo "app_service_url=$APP_URL" >> $GITHUB_OUTPUT
      echo "✅ Retrieved app service info: $APP_NAME"
    fi
```

2. **Improved deploy step logic** to handle both scenarios:
```yaml
- name: Determine App Service Name
  id: app_name
  run: |
    # Try terraform-apply outputs first, then fall back to terraform outputs
    if [ "${{ needs.terraform-apply.result }}" == "success" ] && [ -n "${{ needs.terraform-apply.outputs.app_service_name }}" ]; then
      echo "name=${{ needs.terraform-apply.outputs.app_service_name }}" >> $GITHUB_OUTPUT
      echo "✅ Using app service from terraform-apply"
    elif [ -n "${{ needs.terraform.outputs.app_service_name }}" ]; then
      echo "name=${{ needs.terraform.outputs.app_service_name }}" >> $GITHUB_OUTPUT
      echo "✅ Using app service from terraform"
    else
      echo "❌ No app service name found!"
      exit 1
    fi
```

3. **Added validation** in deploy job condition:
```yaml
if: |
  always() && 
  github.ref == 'refs/heads/main' && 
  github.event_name == 'push' &&
  needs.terraform.result == 'success' &&
  (needs.terraform-apply.result == 'success' || needs.terraform-apply.result == 'skipped')
```

## Testing the Fixes

### 1. Commit and Push Changes

```bash
git add .github/workflows/ci-cd.yml
git commit -m "Fix pipeline: Terraform exit code handling and app service name retrieval"
git push origin main
```

### 2. Verify Pipeline Execution

1. Go to GitHub Actions tab
2. Watch the workflow run
3. Verify each stage completes successfully:
   - ✅ Validation
   - ✅ Terraform (should show "No infrastructure changes detected")
   - ✅ Terraform Apply (should be skipped if no changes)
   - ✅ Deploy (should use existing app service)

### 3. Expected Behavior

**If infrastructure already exists:**
- Terraform Plan: "No infrastructure changes detected" (exit code 0)
- Terraform Apply: Skipped
- Deploy: Uses existing app service name from Terraform outputs
- Result: Application deployed successfully

**If infrastructure doesn't exist:**
- Terraform Plan: "Infrastructure changes detected" (exit code 2)
- Terraform Apply: Creates resources
- Deploy: Uses new app service name from Terraform Apply outputs
- Result: Infrastructure created and application deployed

## What Changed in Files

### `.github/workflows/ci-cd.yml`

**Lines changed:**
1. Terraform Plan step (lines ~95-110): Fixed exit code handling
2. Get Terraform Outputs step (lines ~112-125): Always retrieve outputs
3. Deploy job condition (line ~165): Added terraform.result check
4. Determine App Service Name step (lines ~175-195): Improved fallback logic

### `docs/ci-cd-pipeline.md`

**Sections added:**
1. "First-Time Deployment" section with instructions
2. Troubleshooting entries for both errors

## Verification Checklist

After pushing the fixes, verify:

- [ ] Pipeline runs without shell script errors
- [ ] Terraform plan completes successfully
- [ ] App service name is retrieved from outputs
- [ ] Deploy step receives the app service name
- [ ] Application deploys successfully
- [ ] Health check passes

## Additional Notes

### Why These Errors Occurred

1. **Exit Code Error**: The original script tried to use `export` in a subshell, which doesn't propagate the variable to the parent shell. The fix uses proper exit code capture.

2. **App Name Error**: The pipeline was designed to only get outputs when applying changes. This works for the first deployment but fails on subsequent deployments when there are no infrastructure changes. The fix always retrieves outputs from the current state.

### Future Improvements

Consider these enhancements:

1. **Cache Terraform outputs**: Store outputs in GitHub Actions cache for faster retrieval
2. **Validate outputs exist**: Add explicit check that required outputs are present
3. **Better error messages**: Provide more context when outputs are missing
4. **Separate infrastructure job**: Split infrastructure management into a separate workflow

## Support

If you still encounter issues:

1. Check the GitHub Actions logs for detailed error messages
2. Verify all secrets are configured correctly
3. Ensure Terraform backend is accessible
4. Check Azure portal to confirm resources exist
5. Review `docs/ci-cd-pipeline.md` for troubleshooting guide

## Summary

✅ Fixed Terraform exit code handling  
✅ Fixed app service name retrieval  
✅ Added better error handling  
✅ Updated documentation  
✅ Pipeline should now work for both first-time and subsequent deployments
