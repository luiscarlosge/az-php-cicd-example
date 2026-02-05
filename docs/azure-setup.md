# Azure Setup Guide

This guide walks you through setting up a Microsoft Azure account and subscription to deploy the PHP CI/CD Portal.

## Prerequisites

- A valid email address
- A credit/debit card (for identity verification - you won't be charged for free tier services)
- A phone number for verification

## Step 1: Create Azure Free Account

1. Navigate to the Azure Free Account page:
   - Visit: https://azure.microsoft.com/free/

2. Click the "Start free" button

3. Sign in with a Microsoft account:
   - If you have an existing Microsoft account (Outlook, Hotmail, Xbox, etc.), sign in with those credentials
   - If you don't have one, click "Create one" and follow the prompts to create a new Microsoft account

4. Complete the identity verification:
   - Enter your phone number and verify via SMS or call
   - Enter your credit/decard information (for identity verification only)
   - Note: You will NOT be charged unless you explicitly upgrade to a paid subscription

5. Review and accept the agreement:
   - Read the subscription agreement and privacy statement
   - Check the agreement box
   - Click "Sign up"

6. Wait for account provisioning:
   - Azure will set up your account (this may take a few minutes)
   - You'll be redirected to the Azure Portal once complete

## Step 2: Understand Azure Free Tier Benefits

Your free account includes:

- **12 months of free services**: Including App Service (F1 tier), which we'll use for this project
- **Always free services**: 25+ services that are always free within certain limits
- **$200 credit**: For the first 30 days to explore any Azure service

For this project, we'll use:
- **Azure App Service (F1 tier)**: Free tier for hosting web applications
  - 1 GB disk space
  - 60 CPU minutes/day
  - 165 MB data out/day
  - Custom domain support (optional)

## Step 3: Navigate the Azure Portal

Once logged in, you'll see the Azure Portal dashboard:

### Key Portal Sections

1. **Home**: Dashboard with quick access to resources and services
2. **All services**: Complete list of Azure services (left sidebar)
3. **Resource groups**: Containers for organizing related resources
4. **Subscriptions**: View and manage your Azure subscriptions
5. **Cost Management + Billing**: Monitor usage and costs

### Important Portal Navigation Tips

- Use the search bar at the top to quickly find services or resources
- Pin frequently used services to your dashboard for quick access
- Use the "Cloud Shell" icon (>_) in the top menu for Azure CLI access
- Access notifications via the bell icon in the top-right corner

## Step 4: Verify Your Subscription

1. In the Azure Portal, click on "Subscriptions" in the left menu
   - Or search for "Subscriptions" in the top search bar

2. You should see your subscription listed:
   - Name: Usually "Azure subscription 1" or "Free Trial"
   - Status: Should be "Active"
   - Subscription ID: A GUID (e.g., `12345678-1234-1234-1234-123456789abc`)

3. Click on your subscription to view details:
   - Overview: Shows usage and spending
   - Resource groups: Lists all resource groups in this subscription
   - Access control (IAM): Manage permissions

4. **Important**: Copy your Subscription ID
   - You'll need this for creating the service principal
   - Click the copy icon next to the Subscription ID
   - Save it in a secure location

## Step 5: Understanding Resource Groups

Resource groups are logical containers for Azure resources. For this project, we'll create a resource group to hold:
- App Service Plan
- App Service (Web App)

Resource groups help with:
- Organization: Group related resources together
- Access control: Apply permissions at the resource group level
- Cost tracking: View costs for all resources in a group
- Lifecycle management: Delete all resources in a group at once

Note: The Terraform configuration will create the resource group automatically, so you don't need to create it manually.

## Step 6: Enable Required Resource Providers

Azure uses resource providers to manage different types of resources. For this project, ensure the following providers are registered:

1. In the Azure Portal, go to "Subscriptions"
2. Click on your subscription
3. In the left menu, click "Resource providers"
4. Search for and ensure these providers are "Registered":
   - `Microsoft.Web` (for App Service)
   - `Microsoft.Storage` (for Terraform state storage, if using remote state)

If any provider shows "NotRegistered":
- Click on the provider
- Click "Register" at the top
- Wait for the status to change to "Registered" (may take a few minutes)

## Step 7: Set Up Azure CLI (Optional but Recommended)

The Azure CLI allows you to manage Azure resources from the command line. This is useful for:
- Creating service principals
- Viewing logs
- Troubleshooting deployments

Installation instructions are covered in the [Service Principal documentation](./service-principal.md).

## Free Tier Limitations and Quotas

Be aware of these limitations when using the F1 (Free) tier:

### App Service F1 Tier Limits:
- **CPU**: 60 minutes per day
- **Memory**: 1 GB
- **Storage**: 1 GB
- **Bandwidth**: 165 MB outbound per day
- **Always On**: Not available (app may sleep after inactivity)
- **Deployment slots**: Not available
- **Custom domains**: Supported (but requires DNS configuration)
- **SSL**: Free SSL certificate for *.azurewebsites.net domain

### What Happens When Limits Are Exceeded:
- **CPU quota exceeded**: App will stop responding until the quota resets (daily)
- **Bandwidth exceeded**: App will be throttled or stopped until the quota resets
- **Storage exceeded**: Deployment will fail

### Monitoring Your Usage:
1. Go to your App Service in the Azure Portal
2. Click "Metrics" in the left menu
3. View CPU time, memory usage, and data transfer
4. Set up alerts to notify you when approaching limits

## Troubleshooting

### Issue: Can't create free account
**Solution**: 
- Ensure you haven't previously used a free trial with this Microsoft account
- Each person is eligible for one free trial per Microsoft account
- Try using a different email address if needed

### Issue: Credit card declined
**Solution**:
- Ensure your card supports international transactions
- Try a different card
- Contact your bank to authorize the verification charge

### Issue: Subscription not showing as active
**Solution**:
- Wait a few minutes and refresh the page
- Sign out and sign back in to the Azure Portal
- Contact Azure support if the issue persists

### Issue: Can't find a service in the portal
**Solution**:
- Use the search bar at the top of the portal
- Check that you're in the correct subscription (dropdown at the top)
- Ensure the service is available in your region

## Next Steps

Once your Azure account is set up:

1. **Create a Service Principal**: Follow the [Service Principal Setup Guide](./service-principal.md)
2. **Configure GitHub Secrets**: Follow the [GitHub Setup Guide](./github-setup.md)
3. **Deploy Infrastructure**: Use Terraform to create Azure resources
4. **Deploy Application**: Push code to trigger the CI/CD pipeline

## Additional Resources

- [Azure Free Account FAQ](https://azure.microsoft.com/free/free-account-faq/)
- [Azure Portal Documentation](https://docs.microsoft.com/azure/azure-portal/)
- [Azure App Service Documentation](https://docs.microsoft.com/azure/app-service/)
- [Azure Pricing Calculator](https://azure.microsoft.com/pricing/calculator/)
- [Azure Support](https://azure.microsoft.com/support/options/)

## Cost Management Tips

To avoid unexpected charges:

1. **Set up cost alerts**:
   - Go to "Cost Management + Billing" in the portal
   - Set up budget alerts to notify you when spending exceeds thresholds

2. **Monitor usage regularly**:
   - Check the "Cost Management" section weekly
   - Review the cost analysis to see where spending occurs

3. **Clean up unused resources**:
   - Delete resource groups you're no longer using
   - Use `terraform destroy` to remove all infrastructure when done testing

4. **Stay within free tier limits**:
   - Use F1 tier for App Service
   - Avoid creating multiple instances
   - Monitor CPU and bandwidth usage

Remember: As long as you use only the F1 tier App Service and stay within the free tier limits, you should not incur any charges.
