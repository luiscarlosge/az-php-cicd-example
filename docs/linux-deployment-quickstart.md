# Linux Deployment Quick Start

## ✅ What's Been Fixed

Your application is now **fully configured for Azure Linux App Service**:

1. ✅ **All file paths** use forward slashes (Linux compatible)
2. ✅ **Absolute URLs** for assets (`/public/assets/...`)
3. ✅ **Root index.php** redirects to public directory
4. ✅ **Nginx configuration** for optimal performance
5. ✅ **Startup script** for container initialization
6. ✅ **Apache .htaccess** as fallback
7. ✅ **Security headers** and file protection
8. ✅ **Proper permissions** configuration

## 📦 Deployment Package Ready

**File**: `azure-deploy-linux.zip` (19.5 KB)

## 🚀 Deploy Now (3 Steps)

### Step 1: Login to Azure
```bash
az login
```

### Step 2: Deploy
```bash
az webapp deployment source config-zip \
  --resource-group rg-cloud-course-portal \
  --name YOUR-APP-NAME \
  --src azure-deploy-linux.zip
```

### Step 3: Verify
```bash
# Open in browser
https://YOUR-APP-NAME.azurewebsites.net
```

## 🔧 Post-Deployment (Optional)

### Set PHP Version
```bash
az webapp config set \
  --resource-group rg-cloud-course-portal \
  --name YOUR-APP-NAME \
  --linux-fx-version "PHP|8.1"
```

### Enable Logging
```bash
az webapp log config \
  --resource-group rg-cloud-course-portal \
  --name YOUR-APP-NAME \
  --application-logging filesystem \
  --level error \
  --web-server-logging filesystem
```

### View Logs
```bash
az webapp log tail \
  --resource-group rg-cloud-course-portal \
  --name YOUR-APP-NAME
```

## 📋 Files Changed for Linux

| File | Change |
|------|--------|
| `includes/header.php` | Updated asset paths to `/public/assets/...` |
| `includes/navigation.php` | Updated links to `/public/*.php` |
| `includes/config.php` | Updated faculty image paths |
| `public/index.php` | Updated CTA button links |
| `public/faculty.php` | Updated placeholder image path |
| **NEW** `index.php` | Root redirect handler |
| **NEW** `.htaccess` | Apache configuration |
| **NEW** `nginx.conf` | Nginx configuration |
| **NEW** `startup.sh` | Container startup script |
| **NEW** `.deployment` | Azure deployment config |

## 🌐 URL Structure

After deployment, your pages will be accessible at:

- Home: `https://YOUR-APP-NAME.azurewebsites.net/` or `/public/index.php`
- Curriculum: `/public/curriculum.php`
- Faculty: `/public/faculty.php`
- Admissions: `/public/admissions.php`
- Contact: `/public/contact.php`

## 🐛 Quick Troubleshooting

### 403 Forbidden
```bash
# SSH and fix permissions
az webapp ssh --resource-group rg-cloud-course-portal --name YOUR-APP-NAME
chmod -R 755 /home/site/wwwroot/public
```

### 500 Error
```bash
# Check PHP errors
az webapp log tail --resource-group rg-cloud-course-portal --name YOUR-APP-NAME
```

### Assets Not Loading
- Verify paths use `/public/assets/...`
- Check browser console for 404 errors
- Verify files exist in zip package

## 📚 Full Documentation

See `DEPLOY-LINUX.md` for complete deployment guide with:
- Detailed configuration steps
- Performance optimization
- Security checklist
- Advanced troubleshooting
- File structure reference

## ✨ Ready to Deploy!

Your application is now **100% Linux-compatible** and ready for Azure App Service deployment.
