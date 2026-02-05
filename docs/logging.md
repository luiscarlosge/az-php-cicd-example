# Logging and Monitoring Guide

This guide explains how to access, configure, and analyze logs for the Azure PHP CI/CD Portal application.

## Table of Contents

1. [Overview](#overview)
2. [Log Types](#log-types)
3. [Accessing Logs via Azure Portal](#accessing-logs-via-azure-portal)
4. [Accessing Logs via Azure CLI](#accessing-logs-via-azure-cli)
5. [Enabling Different Log Types](#enabling-different-log-types)
6. [Log Retention and Storage](#log-retention-and-storage)
7. [Analyzing Logs](#analyzing-logs)
8. [Common Log Patterns](#common-log-patterns)
9. [Troubleshooting with Logs](#troubleshooting-with-logs)

---

## Overview

Azure App Service provides comprehensive logging capabilities to help you monitor application health, debug issues, and track usage patterns. Logs are essential for:

- **Debugging**: Identifying and fixing errors
- **Monitoring**: Tracking application performance
- **Security**: Detecting suspicious activity
- **Compliance**: Meeting audit requirements
- **Optimization**: Understanding usage patterns

---

## Log Types

Azure App Service supports several types of logs:

### 1. Application Logging

**Purpose**: Captures output from your PHP application

**Content**:
- PHP errors and warnings
- Custom log messages (via `error_log()`)
- Stack traces
- Application-specific events

**Use Cases**:
- Debugging PHP errors
- Tracking application behavior
- Monitoring custom events

### 2. Web Server Logging

**Purpose**: Captures HTTP requests handled by the web server

**Content**:
- Request URL and method
- Response status code
- Response time
- Client IP address
- User agent
- Referrer

**Use Cases**:
- Analyzing traffic patterns
- Identifying slow requests
- Detecting bot activity
- Monitoring HTTP errors

### 3. Detailed Error Messages

**Purpose**: Captures detailed error information for failed requests

**Content**:
- Full error details
- Request headers
- Server variables
- Stack traces

**Use Cases**:
- Debugging HTTP 500 errors
- Investigating request failures
- Analyzing server configuration issues

### 4. Deployment Logging

**Purpose**: Captures deployment activity and results

**Content**:
- Deployment start/end times
- Deployment status (success/failure)
- Files deployed
- Deployment errors

**Use Cases**:
- Verifying deployments
- Troubleshooting deployment failures
- Tracking deployment history

---

## Accessing Logs via Azure Portal

### Step 1: Navigate to Your App Service

1. Open the [Azure Portal](https://portal.azure.com)
2. Search for your App Service name in the top search bar
3. Click on your App Service from the results

### Step 2: Access Log Stream

**For Real-Time Logs:**

1. In the left sidebar, scroll to "Monitoring"
2. Click "Log stream"
3. Select log type:
   - **Application Logs**: PHP application output
   - **Web Server Logs**: HTTP request logs

You'll see logs streaming in real-time as they're generated.

**Example Output:**
```
2024-01-15 10:30:45.123 [Info] Application started
2024-01-15 10:30:46.456 [Info] GET /curriculum.php - 200 OK - 45ms
2024-01-15 10:30:47.789 [Error] PHP Warning: Undefined variable in /home/site/wwwroot/public/index.php on line 42
```

### Step 3: Access Historical Logs

**For Past Logs:**

1. In the left sidebar, click "Diagnose and solve problems"
2. Click "Diagnostic Tools"
3. Select "Application Logs" or "Web Server Logs"
4. Choose time range to view
5. Click "Download" to save logs locally

### Step 4: View Metrics

**For Performance Metrics:**

1. In the left sidebar, click "Metrics"
2. Select metric to view:
   - CPU Time
   - Memory Usage
   - Data In/Out
   - HTTP Requests
   - Response Time
   - HTTP Status Codes

3. Adjust time range and granularity
4. Add filters or split by dimension

**Example Metrics Dashboard:**
```
CPU Time:        45 minutes / 60 minutes (75% of daily quota)
Memory Usage:    512 MB / 1024 MB (50%)
HTTP Requests:   1,234 requests in last hour
Avg Response:    125ms
HTTP 200:        1,180 (95.6%)
HTTP 404:        50 (4.1%)
HTTP 500:        4 (0.3%)
```

---

## Accessing Logs via Azure CLI

The Azure CLI provides powerful command-line access to logs.

### Prerequisites

Ensure Azure CLI is installed and you're logged in:

```bash
az login
```

### Stream Application Logs

**Real-time streaming:**

```bash
az webapp log tail \
  --name <app-name> \
  --resource-group <resource-group-name>
```

**Example:**
```bash
az webapp log tail \
  --name my-php-portal \
  --resource-group php-portal-rg
```

**Output:**
```
2024-01-15T10:30:45.123Z [Info] Application started
2024-01-15T10:30:46.456Z [Info] GET /curriculum.php - 200 OK
2024-01-15T10:30:47.789Z [Error] PHP Warning: Undefined variable
```

**Stop streaming:** Press `Ctrl+C`

### Download Application Logs

**Download logs to local file:**

```bash
az webapp log download \
  --name <app-name> \
  --resource-group <resource-group-name> \
  --log-file logs.zip
```

This downloads a ZIP file containing all available logs.

**Extract and view:**
```bash
unzip logs.zip
cat LogFiles/Application/*.txt
```

### View Deployment Logs

**Show recent deployments:**

```bash
az webapp deployment list \
  --name <app-name> \
  --resource-group <resource-group-name> \
  --output table
```

**Show specific deployment details:**

```bash
az webapp deployment show \
  --name <app-name> \
  --resource-group <resource-group-name> \
  --deployment-id <deployment-id>
```

### Query Logs with Azure Monitor

**For advanced log queries:**

```bash
az monitor log-analytics query \
  --workspace <workspace-id> \
  --analytics-query "AppServiceHTTPLogs | where TimeGenerated > ago(1h) | project TimeGenerated, CsHost, CsUriStem, ScStatus, TimeTaken"
```

**Note:** Requires Application Insights or Log Analytics workspace configured.

---

## Enabling Different Log Types

### Enable Application Logging

**Via Azure Portal:**

1. Go to your App Service
2. Click "App Service logs" in the left sidebar
3. Under "Application logging":
   - **Application Logging (Filesystem)**: Turn **On**
   - **Level**: Select level (Error, Warning, Information, Verbose)
   - **Quota (MB)**: Set quota (default: 35 MB)
   - **Retention Period (Days)**: Set retention (default: 0 = forever, max: 7 days for filesystem)

4. Click "Save"

**Via Azure CLI:**

```bash
az webapp log config \
  --name <app-name> \
  --resource-group <resource-group-name> \
  --application-logging filesystem \
  --level information
```

**Log Levels:**
- **Error**: Only errors (recommended for production)
- **Warning**: Errors and warnings
- **Information**: Errors, warnings, and info messages
- **Verbose**: All messages including debug (use for troubleshooting only)

### Enable Web Server Logging

**Via Azure Portal:**

1. Go to your App Service
2. Click "App Service logs"
3. Under "Web server logging":
   - **Web server logging**: Turn **On**
   - **Storage**: Select "File System"
   - **Retention Period (Days)**: Set retention (max: 7 days)
   - **Quota (MB)**: Set quota (default: 35 MB)

4. Click "Save"

**Via Azure CLI:**

```bash
az webapp log config \
  --name <app-name> \
  --resource-group <resource-group-name> \
  --web-server-logging filesystem
```

### Enable Detailed Error Messages

**Via Azure Portal:**

1. Go to your App Service
2. Click "App Service logs"
3. Under "Detailed error messages":
   - Toggle **On**

4. Click "Save"

**Via Azure CLI:**

```bash
az webapp log config \
  --name <app-name> \
  --resource-group <resource-group-name> \
  --detailed-error-messages true
```

### Enable Failed Request Tracing

**Via Azure Portal:**

1. Go to your App Service
2. Click "App Service logs"
3. Under "Failed request tracing":
   - Toggle **On**

4. Click "Save"

**Via Azure CLI:**

```bash
az webapp log config \
  --name <app-name> \
  --resource-group <resource-group-name> \
  --failed-request-tracing true
```

---

## Log Retention and Storage

### Filesystem Storage (Default)

**Characteristics:**
- Stored on App Service filesystem
- Maximum retention: 7 days
- Maximum quota: 35 MB per log type
- Automatically rotated when quota reached
- Deleted when retention period expires

**Location:**
- Application logs: `/home/LogFiles/Application/`
- Web server logs: `/home/LogFiles/http/RawLogs/`
- Detailed errors: `/home/LogFiles/DetailedErrors/`

**Limitations:**
- Limited retention (7 days max)
- Limited storage (35 MB per type)
- Lost if App Service is deleted
- Not suitable for long-term storage

### Blob Storage (Recommended for Production)

**Characteristics:**
- Stored in Azure Blob Storage
- Unlimited retention (you control)
- Unlimited storage (pay for what you use)
- Persists even if App Service is deleted
- Can be analyzed with external tools

**Setup via Azure Portal:**

1. Create Azure Storage Account (if not exists):
   ```bash
   az storage account create \
     --name <storage-account-name> \
     --resource-group <resource-group-name> \
     --location <location> \
     --sku Standard_LRS
   ```

2. Go to App Service → App Service logs
3. Under "Application logging":
   - Select **Blob** instead of Filesystem
   - Click "Storage Settings"
   - Select storage account and container
   - Set retention period (0 = unlimited)

4. Click "Save"

**Setup via Azure CLI:**

```bash
# Get storage account connection string
STORAGE_CONNECTION=$(az storage account show-connection-string \
  --name <storage-account-name> \
  --resource-group <resource-group-name> \
  --query connectionString -o tsv)

# Enable blob logging
az webapp log config \
  --name <app-name> \
  --resource-group <resource-group-name> \
  --application-logging azureblobstorage \
  --level information \
  --docker-container-logging filesystem
```

**Benefits:**
- Long-term log retention
- Centralized log storage for multiple apps
- Integration with log analysis tools
- Cost-effective for large volumes

---

## Analyzing Logs

### Using Azure Portal

**Search Logs:**

1. Go to App Service → Log stream
2. Use browser search (Ctrl+F) to find specific text
3. Filter by log level or timestamp

**View Metrics:**

1. Go to App Service → Metrics
2. Create custom charts
3. Set up alerts for anomalies

### Using Azure CLI

**Search for specific errors:**

```bash
az webapp log tail \
  --name <app-name> \
  --resource-group <resource-group-name> \
  | grep "Error"
```

**Count HTTP status codes:**

```bash
az webapp log download \
  --name <app-name> \
  --resource-group <resource-group-name> \
  --log-file logs.zip

unzip logs.zip
grep -o "HTTP/[0-9]\.[0-9]\" [0-9]*" LogFiles/http/RawLogs/*.log | \
  cut -d'"' -f2 | cut -d' ' -f2 | sort | uniq -c
```

### Using Log Analysis Tools

**Application Insights (Recommended):**

1. Enable Application Insights for your App Service
2. Use Kusto Query Language (KQL) for advanced queries
3. Create custom dashboards and alerts

**Example KQL Query:**
```kql
requests
| where timestamp > ago(1h)
| summarize count() by resultCode, bin(timestamp, 5m)
| render timechart
```

**Third-Party Tools:**
- **Splunk**: Enterprise log management
- **ELK Stack**: Elasticsearch, Logstash, Kibana
- **Datadog**: Cloud monitoring platform
- **New Relic**: Application performance monitoring

---

## Common Log Patterns

### Successful Request

```
2024-01-15 10:30:46.456 [Info] GET /curriculum.php - 200 OK - 45ms
```

**Interpretation:**
- Timestamp: 2024-01-15 10:30:46.456
- Log level: Info
- HTTP method: GET
- Request path: /curriculum.php
- Status code: 200 (success)
- Response time: 45ms

### PHP Error

```
2024-01-15 10:30:47.789 [Error] PHP Warning: Undefined variable $course_name in /home/site/wwwroot/public/index.php on line 42
```

**Interpretation:**
- Timestamp: 2024-01-15 10:30:47.789
- Log level: Error
- Error type: PHP Warning
- Issue: Undefined variable
- File: /home/site/wwwroot/public/index.php
- Line: 42

**Action:** Fix the undefined variable in the code.

### HTTP 404 Not Found

```
2024-01-15 10:31:00.123 [Info] GET /nonexistent.php - 404 Not Found - 12ms
```

**Interpretation:**
- User requested a page that doesn't exist
- Could be broken link or typo
- Fast response (12ms) indicates server is healthy

**Action:** Check for broken links or update navigation.

### HTTP 500 Internal Server Error

```
2024-01-15 10:31:15.456 [Error] GET /faculty.php - 500 Internal Server Error - 234ms
2024-01-15 10:31:15.457 [Error] PHP Fatal error: Call to undefined function get_faculty() in /home/site/wwwroot/public/faculty.php on line 15
```

**Interpretation:**
- Server error occurred
- Caused by calling undefined function
- Slower response (234ms) due to error processing

**Action:** Fix the undefined function call.

### Deployment Success

```
2024-01-15 10:00:00.000 [Info] Deployment started
2024-01-15 10:00:15.123 [Info] Copying files to /home/site/wwwroot
2024-01-15 10:00:30.456 [Info] Deployment successful
```

**Interpretation:**
- Deployment completed successfully
- Took approximately 30 seconds
- Files copied to correct location

### Deployment Failure

```
2024-01-15 10:00:00.000 [Info] Deployment started
2024-01-15 10:00:15.123 [Error] Failed to copy file: permission denied
2024-01-15 10:00:15.124 [Error] Deployment failed
```

**Interpretation:**
- Deployment failed due to permission issue
- Files not copied successfully

**Action:** Check deployment credentials and permissions.

---

## Troubleshooting with Logs

### Scenario 1: Application Not Loading

**Symptoms:** White screen or HTTP 500 error

**Steps:**

1. **Check application logs:**
   ```bash
   az webapp log tail --name <app-name> --resource-group <rg-name>
   ```

2. **Look for PHP errors:**
   - Syntax errors
   - Fatal errors
   - Missing includes

3. **Check recent deployments:**
   ```bash
   az webapp deployment list --name <app-name> --resource-group <rg-name>
   ```

4. **Verify files deployed correctly:**
   - Check deployment logs
   - Verify file permissions

### Scenario 2: Slow Performance

**Symptoms:** Pages loading slowly

**Steps:**

1. **Check response times in web server logs:**
   ```bash
   az webapp log download --name <app-name> --resource-group <rg-name> --log-file logs.zip
   unzip logs.zip
   grep "GET" LogFiles/http/RawLogs/*.log | awk '{print $NF}' | sort -n
   ```

2. **Check CPU and memory metrics:**
   - Go to Azure Portal → Metrics
   - View CPU Time and Memory Usage

3. **Identify slow requests:**
   - Look for requests taking >1000ms
   - Optimize those pages

4. **Check for quota limits:**
   - Verify CPU quota not exceeded
   - Check bandwidth usage

### Scenario 3: Intermittent Errors

**Symptoms:** Errors occur occasionally

**Steps:**

1. **Enable verbose logging:**
   ```bash
   az webapp log config \
     --name <app-name> \
     --resource-group <rg-name> \
     --application-logging filesystem \
     --level verbose
   ```

2. **Monitor logs continuously:**
   ```bash
   az webapp log tail --name <app-name> --resource-group <rg-name>
   ```

3. **Look for patterns:**
   - Time of day
   - Specific pages
   - User agents (bots?)

4. **Correlate with metrics:**
   - Check if errors coincide with high traffic
   - Check if errors occur when quota limits approached

### Scenario 4: Deployment Issues

**Symptoms:** Deployment fails or old code still running

**Steps:**

1. **Check deployment logs:**
   ```bash
   az webapp deployment show \
     --name <app-name> \
     --resource-group <rg-name> \
     --deployment-id <deployment-id>
   ```

2. **Verify GitHub Actions workflow:**
   - Check workflow logs in GitHub
   - Verify secrets are configured

3. **Check App Service status:**
   ```bash
   az webapp show \
     --name <app-name> \
     --resource-group <rg-name> \
     --query state
   ```

4. **Restart App Service if needed:**
   ```bash
   az webapp restart \
     --name <app-name> \
     --resource-group <rg-name>
   ```

---

## Best Practices

1. **Enable appropriate log levels:**
   - Production: Error or Warning
   - Development: Information or Verbose

2. **Use blob storage for production:**
   - Longer retention
   - Better for compliance
   - Easier to analyze

3. **Set up alerts:**
   - Alert on high error rates
   - Alert on quota limits
   - Alert on deployment failures

4. **Regularly review logs:**
   - Check for errors daily
   - Analyze trends weekly
   - Review metrics monthly

5. **Rotate logs appropriately:**
   - Balance retention vs. cost
   - Keep critical logs longer
   - Archive old logs if needed

6. **Protect sensitive data:**
   - Don't log passwords or secrets
   - Sanitize user input in logs
   - Comply with privacy regulations

7. **Use structured logging:**
   - Include timestamps
   - Include request IDs
   - Include user context (if applicable)

8. **Monitor log volume:**
   - Watch for log spam
   - Optimize verbose logging
   - Use sampling for high-volume logs

---

## Additional Resources

- [Azure App Service Logging Documentation](https://docs.microsoft.com/azure/app-service/troubleshoot-diagnostic-logs)
- [Azure Monitor Documentation](https://docs.microsoft.com/azure/azure-monitor/)
- [Application Insights Documentation](https://docs.microsoft.com/azure/azure-monitor/app/app-insights-overview)
- [Azure CLI Logging Commands](https://docs.microsoft.com/cli/azure/webapp/log)
- [Kusto Query Language (KQL) Reference](https://docs.microsoft.com/azure/data-explorer/kusto/query/)
