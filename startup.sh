#!/bin/bash
# Startup script for Azure Linux App Service
# This script runs when the container starts

echo "Starting Azure PHP App Service..."

# Set proper permissions
chmod -R 755 /home/site/wwwroot/public
chmod -R 755 /home/site/wwwroot/includes

# Create log directory if it doesn't exist
mkdir -p /home/LogFiles

# Set PHP configuration
echo "Setting PHP configuration..."
cat > /usr/local/etc/php/conf.d/azure.ini << EOF
display_errors = Off
log_errors = On
error_log = /home/LogFiles/php_errors.log
upload_max_filesize = 10M
post_max_size = 10M
memory_limit = 128M
max_execution_time = 30
date.timezone = UTC
EOF

# Start PHP-FPM (if not already started by Azure)
echo "PHP-FPM configuration complete"

# Output PHP version
php -v

echo "Startup complete. Application ready."
