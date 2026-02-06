# Azure Linux App Service Deployment Guide

## Overview

This application is configured for **Azure App Service on Linux** with PHP 8.0+. All paths and configurations have been optimized for Linux deployment.

## What's Been Fixed for Linux

✅ **File Paths**: All paths use forward slashes (`/`) compatible with Linux
✅ **Absolute URLs**: Asset and navigation links use absolute paths (`/public/...`)
✅ **Nginx Configuration**: Custom nginx.conf for optimal performance
✅ **Startup Script**: Bash script for container initialization
✅ **Apache Support**: .htaccess file for Apache-based deployments
✅ **Root Index**: Redirect handler for Azure's document root
✅ **Line Endings**: All files use LF (Unix) line endings

## Deployment Package

**File**: `azure-deploy-linux.zip`

**Contents**:
- `public/` - Web root with all PHP pages and assets
- `includes/` - Configuration and components
- `index.php` - Root redirect handler
- `.htaccess` - Apache configuration
- `nginx.conf` - Nginx configuration (optional)
- `startup.sh` - Container startup script
- `.deployment` - Azure deployment config
- `composer.json` - PHP dependencies

## Quick Deployment

### Option 1: Azure CLI (Recommended)

```bash
# Login to Azure
az login

# Deploy the zip package
az webapp deployment source config-zip \
  --resource-group rg-cloud-course-portal \
  --name your-app-name \
  --src azure-deploy-linux.zip
```

### Option 2: Azure Portal

1. Go to Azure Portal → Your App Service
2. Navigate to **Deployment Center**
3. Select **ZIP Deploy**
4. Upload `azure-deploy-linux.zip`
5. Click **Deploy**

### Option 3: FTP/FTPS

1. Get FTP credentials from Azure Portal → Deployment Center → FTPS credentials
2. Connect via FTP client
3. Upload contents to `/site/wwwroot/`
4. Ensure file permissions are correct (755 for directories, 644 for files)

## Post-Deployment Configuration

### 1. Configure Startup Command (Optional)

If you need custom startup behavior:

1. Go to Azure Portal → Your App Service
2. Navigate to **Configuration** → **General settings**
3. Set **Startup Command**:
   ```bash
   /home/site/wwwroot/startup.sh
   ```
4. Click **Save**

### 2. Set Application Settings

Configure environment-specific settings:

1. Go to **Configuration** → **Application settings**
2. Add settings:
   - `WEBSITE_DYNAMIC_CACHE` = `0` (disable cache during development)
   - `WEBSITES_ENABLE_APP_SERVICE_STORAGE` = `true`
   - `WEBSITE_HTTPLOGGING_RETENTION_DAYS` = `7`

### 3. Configure PHP Version

Ensure PHP 8.0 or higher:

1. Go to **Configuration** → **General settings**
2. Set **PHP version** to `8.0` or `8.1`
3. Click **Save**

### 4. Enable Logging

1. Go to **App Service logs**
2. Enable:
   - **Application Logging (Filesystem)** → Level: Error
   - **Web server logging** → File System
   - **Detailed error messages** → On
3. Set **Retention Period** to 7 days
4. Click **Save**

## Verify Deployment

### 1. Check Application URL

Visit your app:
```
https://your-app-name.azurewebsites.net
```

You should see the home page with course information.

### 2. Test All Pages

- Home: `/public/index.php` or `/`
- Curriculum: `/public/curriculum.php`
- Faculty: `/public/faculty.php`
- Admissions: `/public/admissions.php`
- Contact: `/public/contact.php`

### 3. Check Logs

View real-time logs:

```bash
# Via Azure CLI
az webapp log tail \
  --resource-group rg-cloud-course-portal \
  --name your-app-name

# Or via Portal
# App Service → Monitoring → Log stream
```

### 4. Verify File Structure

SSH into the container (if needed):

```bash
az webapp ssh \
  --resource-group rg-cloud-course-portal \
  --name your-app-name
```

Check file structure:
```bash
ls -la /home/site/wwwroot/
ls -la /home/site/wwwroot/public/
```

## Troubleshooting

### Issue: "403 Forbidden" Error

**Cause**: Incorrect file permissions or missing index file

**Solution**:
```bash
# SSH into container
az webapp ssh --resource-group rg-cloud-course-portal --name your-app-name

# Fix permissions
chmod -R 755 /home/site/wwwroot/public
chmod 644 /home/site/wwwroot/public/*.php
```

### Issue: "500 Internal Server Error"

**Cause**: PHP errors or configuration issues

**Solution**:
1. Check PHP error logs:
   ```bash
   cat /home/LogFiles/php_errors.log
   ```
2. Check nginx error logs:
   ```bash
   cat /home/LogFiles/nginx_error.log
   ```
3. Enable detailed errors temporarily in Azure Portal

### Issue: Assets Not Loading (CSS/JS/Images)

**Cause**: Incorrect paths or missing files

**Solution**:
1. Verify files exist:
   ```bash
   ls -la /home/site/wwwroot/public/assets/
   ```
2. Check browser console for 404 errors
3. Verify paths in HTML source use `/public/assets/...`

### Issue: "PHP Parse Error"

**Cause**: Syntax errors in PHP files

**Solution**:
1. Check syntax locally:
   ```bash
   php -l public/index.php
   ```
2. Review error logs in Azure Portal
3. Fix syntax errors and redeploy

### Issue: Redirect Loop

**Cause**: Misconfigured .htaccess or nginx.conf

**Solution**:
1. Remove custom nginx.conf if not needed
2. Check .htaccess rewrite rules
3. Verify HTTPS redirect configuration

## Performance Optimization

### 1. Enable Caching

Add to Application Settings:
```
WEBSITE_DYNAMIC_CACHE = 1
WEBSITE_LOCAL_CACHE_OPTION = Always
```

### 2. Enable Compression

Nginx automatically handles gzip compression (configured in nginx.conf).

### 3. Use CDN (Optional)

For static assets, consider Azure CDN:
1. Create Azure CDN profile
2. Add endpoint pointing to your App Service
3. Update asset URLs to use CDN

## Security Checklist

✅ HTTPS enforced (configured in Terraform)
✅ Hidden files protected (.htaccess)
✅ Directory browsing disabled
✅ Security headers configured
✅ Sensitive files blocked (composer.json, .git)
✅ PHP errors not displayed to users

## Updating the Application

To update after initial deployment:

1. **Make changes locally**
2. **Test locally**:
   ```bash
   php -S localhost:8000 -t public
   ```
3. **Create new zip**:
   ```bash
   powershell -Command "Compress-Archive -Path public\*, includes\*, index.php, .htaccess, nginx.conf, startup.sh, .deployment, composer.json -DestinationPath azure-deploy-linux.zip -Force"
   ```
4. **Deploy**:
   ```bash
   az webapp deployment source config-zip \
     --resource-group rg-cloud-course-portal \
     --name your-app-name \
     --src azure-deploy-linux.zip
   ```
5. **Verify**: Check application URL

## File Structure on Azure

```
/home/site/wwwroot/
├── index.php                 # Root redirect
├── .htaccess                 # Apache config
├── nginx.conf                # Nginx config (optional)
├── startup.sh                # Startup script
├── .deployment               # Deployment config
├── composer.json             # Dependencies
├── public/                   # Web root
│   ├── index.php
│   ├── curriculum.php
│   ├── faculty.php
│   ├── admissions.php
│   ├── contact.php
│   └── assets/
│       ├── css/
│       ├── js/
│       └── images/
└── includes/                 # Components
    ├── config.php
    ├── header.php
    ├── navigation.php
    └── footer.php
```

## Additional Resources

- [Azure App Service Linux Documentation](https://docs.microsoft.com/azure/app-service/overview)
- [PHP on Azure App Service](https://docs.microsoft.com/azure/app-service/quickstart-php)
- [Azure CLI Reference](https://docs.microsoft.com/cli/azure/webapp)
- [Troubleshooting Guide](docs/troubleshooting.md)

## Support

For issues or questions:
1. Check logs in Azure Portal
2. Review troubleshooting section above
3. Consult [docs/troubleshooting.md](docs/troubleshooting.md)
4. Check Azure service health: https://status.azure.com
