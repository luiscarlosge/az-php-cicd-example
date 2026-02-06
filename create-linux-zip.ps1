# PowerShell script to create Linux-compatible zip
# This script ensures forward slashes in zip file paths

Write-Host "Creating Linux-compatible deployment package..." -ForegroundColor Green

# Remove old zip if exists
if (Test-Path "azure-deploy-linux.zip") {
    Remove-Item "azure-deploy-linux.zip" -Force
    Write-Host "Removed old zip file" -ForegroundColor Yellow
}

# Create temporary directory
$tempDir = "temp-deploy"
if (Test-Path $tempDir) {
    Remove-Item $tempDir -Recurse -Force
}
New-Item -ItemType Directory -Path $tempDir | Out-Null

# Copy files to temp directory
Write-Host "Copying files..." -ForegroundColor Cyan
Copy-Item -Path "public" -Destination "$tempDir/public" -Recurse
Copy-Item -Path "includes" -Destination "$tempDir/includes" -Recurse
Copy-Item -Path "index.php" -Destination "$tempDir/"
Copy-Item -Path ".htaccess" -Destination "$tempDir/"
Copy-Item -Path "nginx.conf" -Destination "$tempDir/"
Copy-Item -Path "startup.sh" -Destination "$tempDir/"
Copy-Item -Path ".deployment" -Destination "$tempDir/"
Copy-Item -Path "composer.json" -Destination "$tempDir/"

# Change to temp directory and create zip
Push-Location $tempDir

# Use .NET compression with Unix-style paths
Add-Type -Assembly System.IO.Compression.FileSystem
$compressionLevel = [System.IO.Compression.CompressionLevel]::Optimal
$zipPath = Join-Path $PSScriptRoot "azure-deploy-linux.zip"

# Create zip with forward slashes
$zip = [System.IO.Compression.ZipFile]::Open($zipPath, 'Create')

Get-ChildItem -Recurse -File | ForEach-Object {
    $relativePath = $_.FullName.Substring((Get-Location).Path.Length + 1)
    # Convert backslashes to forward slashes
    $entryName = $relativePath -replace '\\', '/'
    
    Write-Host "Adding: $entryName" -ForegroundColor Gray
    [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($zip, $_.FullName, $entryName, $compressionLevel) | Out-Null
}

$zip.Dispose()

Pop-Location

# Clean up temp directory
Remove-Item $tempDir -Recurse -Force

Write-Host "`nDeployment package created successfully!" -ForegroundColor Green
Write-Host "File: azure-deploy-linux.zip" -ForegroundColor Cyan

# Show file info
$zipFile = Get-Item "azure-deploy-linux.zip"
Write-Host "Size: $([math]::Round($zipFile.Length / 1KB, 2)) KB" -ForegroundColor Cyan
Write-Host "`nReady to deploy to Azure Linux App Service!" -ForegroundColor Green
Write-Host "See docs/deploy-instructions.md for deployment steps" -ForegroundColor Yellow
