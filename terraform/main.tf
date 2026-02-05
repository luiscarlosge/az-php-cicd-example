# Azure Resource Group
resource "azurerm_resource_group" "main" {
  name     = var.resource_group_name
  location = var.location

  tags = {
    environment = "production"
    project     = "azure-php-cicd-portal"
    managed_by  = "terraform"
  }
}

# Azure App Service Plan (Free Tier)
resource "azurerm_service_plan" "main" {
  name                = var.app_service_plan_name
  location            = azurerm_resource_group.main.location
  resource_group_name = azurerm_resource_group.main.name
  os_type             = "Linux"
  sku_name            = "F1"

  tags = {
    environment = "production"
    project     = "azure-php-cicd-portal"
    managed_by  = "terraform"
  }
}

# Azure App Service (Linux Web App)
resource "azurerm_linux_web_app" "main" {
  name                = var.app_name
  location            = azurerm_resource_group.main.location
  resource_group_name = azurerm_resource_group.main.name
  service_plan_id     = azurerm_service_plan.main.id
  https_only          = true

  site_config {
    always_on = false # Not available in F1 tier

    application_stack {
      php_version = "8.2"
    }
  }

  logs {
    application_logs {
      file_system_level = "Information"
    }

    http_logs {
      file_system {
        retention_in_days = 7
        retention_in_mb   = 35
      }
    }
  }

  tags = {
    environment = "production"
    project     = "azure-php-cicd-portal"
    managed_by  = "terraform"
  }
}
