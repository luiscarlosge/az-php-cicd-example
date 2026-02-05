variable "resource_group_name" {
  description = "Name of the Azure resource group"
  type        = string
  default     = "azure-php-portal-rg"
}

variable "location" {
  description = "Azure region for resources"
  type        = string
  default     = "East US"
}

variable "app_service_plan_name" {
  description = "Name of the Azure App Service Plan"
  type        = string
  default     = "azure-php-portal-plan"
}

variable "app_name" {
  description = "Name of the Azure App Service (must be globally unique)"
  type        = string

  validation {
    condition     = can(regex("^[a-z0-9][a-z0-9-]*[a-z0-9]$", var.app_name))
    error_message = "App name must contain only lowercase letters, numbers, and hyphens. It must start and end with a letter or number."
  }

  validation {
    condition     = length(var.app_name) >= 2 && length(var.app_name) <= 60
    error_message = "App name must be between 2 and 60 characters long."
  }
}
