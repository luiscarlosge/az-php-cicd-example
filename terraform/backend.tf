# Remote state storage configuration
# Backend configuration for Azure Storage
# Values are provided via environment variables or command-line flags

terraform {
  backend "azurerm" {
    # These values will be provided via:
    # - GitHub Actions: environment variables
    # - Local: terraform init -backend-config flags
    # 
    # Required values:
    # - resource_group_name
    # - storage_account_name
    # - container_name
    # - key
  }
}
