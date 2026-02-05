# Remote state storage configuration (optional)
# Uncomment and configure the backend block below to use Azure Storage for remote state

# terraform {
#   backend "azurerm" {
#     resource_group_name  = "terraform-state-rg"
#     storage_account_name = "tfstateaccount"
#     container_name       = "tfstate"
#     key                  = "azure-php-cicd-portal.tfstate"
#   }
# }
