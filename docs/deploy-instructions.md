# ✅ Fixed: Linux Deployment Package Ready

## Problem Solved

The original zip had **Windows-style backslashes** (`\`) which caused rsync errors on Linux.

The new package uses **Unix-style forward slashes** (`/`) and is fully compatible with Azure Linux App Service.

## 📦 Deployment Package

**File**: `azure-deploy-linux.zip` (19.24 KB)

✅ **Verified**: All paths use forward slashes  
✅ **Compatible**: Azure Linux App Service  
✅ **Ready**: Deploy immediately

## 🚀 Deploy Now

### Method 1: Azure CLI (Recommended)

```bash
az webapp deployment source config-zip \
  --resource-group rg-cloud-course-portal \
  --name YOUR-APP-NAME \
  --src azure-deploy-linux.zip
```

### Method 2: Azure Portal

1. Go to Azure Portal → Your App Service
2. **Deployment Center** → **ZIP Deploy**
3. Upload `azure-deploy-linux.zip`
4. Click **Deploy**

## 🔄 Recreate Package (If Needed)

If you need to recreate the package after making changes:

```powershell
# Run the PowerShell script (from project root)
powershell -ExecutionPolicy Bypass -File create-linux-zip.ps1
```

This script:
- ✅ Copies all necessary files
- ✅ Creates zip with Unix paths (forward slashes)
- ✅ Verifies compatibility
- ✅ Ready for Linux deployment

## 📋 What's in the Package

```
azure-deploy-linux.zip
├── .deployment          # Azure deployment config
├── .htaccess           # Apache configuration
├── composer.json       # PHP dependencies
├── index.php           # Root redirect
├── nginx.conf          # Nginx configuration
├── startup.sh          # Container startup
├── includes/           # PHP components
│   ├── config.php
│   ├── footer.php
│   ├── header.php
│   └── navigation.php
└── public/             # Web root
    ├── index.php
    ├── curriculum.php
    ├── faculty.php
    ├── admissions.php
    ├── contact.php
    └── assets/
        ├── css/style.css
        ├── js/main.js
        └── images/
            ├── logo.svg
            └── placeholder-faculty.svg
```

## 🎯 After Deployment

1. **Verify URL**: `https://YOUR-APP-NAME.azurewebsites.net`
2. **Check logs**:
   ```bash
   az webapp log tail \
     --resource-group rg-cloud-course-portal \
     --name YOUR-APP-NAME
   ```
3. **Test pages**:
   - Home: `/` or `/public/index.php`
   - Curriculum: `/public/curriculum.php`
   - Faculty: `/public/faculty.php`
   - Admissions: `/public/admissions.php`
   - Contact: `/public/contact.php`

## 🐛 No More Rsync Errors!

The previous error:
```
rsync: [generator] recv_generator: failed to stat 
"/home/site/wwwroot/assets\images\placeholder-faculty.svg": 
Invalid argument (22)
```

Is now **FIXED** because paths use `/` instead of `\`.

## 📚 Additional Documentation

- **Quick Start**: [linux-deployment-quickstart.md](linux-deployment-quickstart.md)
- **Full Guide**: [deploy-linux.md](deploy-linux.md)
- **Troubleshooting**: [troubleshooting.md](troubleshooting.md)

## ✨ Ready to Deploy!

Your package is now **100% Linux-compatible** with proper Unix-style paths.

Deploy with confidence! 🚀
