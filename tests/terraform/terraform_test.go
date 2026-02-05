package test

import (
	"os"
	"path/filepath"
	"testing"

	"github.com/gruntwork-io/terratest/modules/terraform"
	"github.com/stretchr/testify/assert"
)

// TestTerraformValidate tests that terraform validate passes without errors
func TestTerraformValidate(t *testing.T) {
	t.Parallel()

	terraformDir := getTerraformDir()

	terraformOptions := &terraform.Options{
		TerraformDir: terraformDir,
		NoColor:      true,
	}

	// Initialize and validate
	terraform.Init(t, terraformOptions)
	terraform.Validate(t, terraformOptions)
}

// TestTerraformFormat tests that terraform fmt -check passes (code is formatted)
func TestTerraformFormat(t *testing.T) {
	t.Parallel()

	terraformDir := getTerraformDir()

	terraformOptions := &terraform.Options{
		TerraformDir: terraformDir,
		NoColor:      true,
	}

	// Check formatting
	output := terraform.RunTerraformCommand(t, terraformOptions, "fmt", "-check", "-recursive")
	assert.Empty(t, output, "Terraform files should be properly formatted")
}

// TestAppServicePlanSKU tests that App Service Plan uses F1 SKU
func TestAppServicePlanSKU(t *testing.T) {
	t.Parallel()

	terraformDir := getTerraformDir()

	terraformOptions := &terraform.Options{
		TerraformDir: terraformDir,
		NoColor:      true,
		Vars: map[string]interface{}{
			"app_name": "test-app-terraform",
		},
	}

	// Initialize and plan
	terraform.Init(t, terraformOptions)
	planStruct := terraform.InitAndPlanAndShowWithStruct(t, terraformOptions)

	// Find the App Service Plan resource
	var appServicePlan map[string]interface{}
	for _, resource := range planStruct.ResourceChangesMap {
		if resource.Type == "azurerm_service_plan" {
			appServicePlan = resource.Change.After.(map[string]interface{})
			break
		}
	}

	assert.NotNil(t, appServicePlan, "App Service Plan resource should exist in plan")
	assert.Equal(t, "F1", appServicePlan["sku_name"], "App Service Plan should use F1 SKU")
	assert.Equal(t, "Linux", appServicePlan["os_type"], "App Service Plan should use Linux OS")
}

// TestAppServicePHPVersion tests that App Service uses PHP 8.0+ runtime
func TestAppServicePHPVersion(t *testing.T) {
	t.Parallel()

	terraformDir := getTerraformDir()

	terraformOptions := &terraform.Options{
		TerraformDir: terraformDir,
		NoColor:      true,
		Vars: map[string]interface{}{
			"app_name": "test-app-php-version",
		},
	}

	// Initialize and plan
	terraform.Init(t, terraformOptions)
	planStruct := terraform.InitAndPlanAndShowWithStruct(t, terraformOptions)

	// Find the App Service resource
	var appService map[string]interface{}
	for _, resource := range planStruct.ResourceChangesMap {
		if resource.Type == "azurerm_linux_web_app" {
			appService = resource.Change.After.(map[string]interface{})
			break
		}
	}

	assert.NotNil(t, appService, "App Service resource should exist in plan")

	// Check site_config for PHP version
	siteConfig := appService["site_config"].([]interface{})[0].(map[string]interface{})
	appStack := siteConfig["application_stack"].([]interface{})[0].(map[string]interface{})
	phpVersion := appStack["php_version"].(string)

	assert.NotEmpty(t, phpVersion, "PHP version should be configured")
	assert.Contains(t, []string{"8.0", "8.1", "8.2", "8.3"}, phpVersion, "PHP version should be 8.0 or higher")
}

// TestHTTPSEnforced tests that HTTPS is enforced on App Service
func TestHTTPSEnforced(t *testing.T) {
	t.Parallel()

	terraformDir := getTerraformDir()

	terraformOptions := &terraform.Options{
		TerraformDir: terraformDir,
		NoColor:      true,
		Vars: map[string]interface{}{
			"app_name": "test-app-https",
		},
	}

	// Initialize and plan
	terraform.Init(t, terraformOptions)
	planStruct := terraform.InitAndPlanAndShowWithStruct(t, terraformOptions)

	// Find the App Service resource
	var appService map[string]interface{}
	for _, resource := range planStruct.ResourceChangesMap {
		if resource.Type == "azurerm_linux_web_app" {
			appService = resource.Change.After.(map[string]interface{})
			break
		}
	}

	assert.NotNil(t, appService, "App Service resource should exist in plan")
	assert.True(t, appService["https_only"].(bool), "HTTPS should be enforced on App Service")
}

// getTerraformDir returns the path to the terraform directory
func getTerraformDir() string {
	// Get current working directory
	cwd, err := os.Getwd()
	if err != nil {
		panic(err)
	}

	// Navigate to terraform directory (../../terraform from tests/terraform)
	terraformDir := filepath.Join(cwd, "..", "..", "terraform")
	return terraformDir
}
