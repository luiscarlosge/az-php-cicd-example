<?php
/**
 * Config Integration Test
 * Tests that config.php properly initializes the language system
 * 
 * Requirements: 2.3, 8.2
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ConfigIntegrationTest extends TestCase
{
    private string $includesDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->includesDir = __DIR__ . '/../../includes';
        
        // Clear any existing session
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        
        // Clear session superglobal
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Clean up session
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $_SESSION = [];
    }

    /**
     * Test config.php file exists
     * Requirements: 8.2
     */
    public function testConfigFileExists(): void
    {
        $configPath = $this->includesDir . '/config.php';
        $this->assertFileExists($configPath, 'Config file should exist');
    }

    /**
     * Test config.php includes language.php
     * Requirements: 2.3, 8.2
     */
    public function testConfigIncludesLanguageFile(): void
    {
        $configPath = $this->includesDir . '/config.php';
        $configContent = file_get_contents($configPath);
        
        $this->assertStringContainsString('language.php', $configContent, 
            'Config should include language.php');
        $this->assertStringContainsString('require_once', $configContent, 
            'Config should use require_once to include language.php');
    }

    /**
     * Test config.php starts session
     * Requirements: 2.3, 8.2
     */
    public function testConfigStartsSession(): void
    {
        $configPath = $this->includesDir . '/config.php';
        $configContent = file_get_contents($configPath);
        
        $this->assertStringContainsString('session_start()', $configContent, 
            'Config should call session_start()');
    }

    /**
     * Test config.php calls initLanguage()
     * Requirements: 2.3, 8.2
     */
    public function testConfigCallsInitLanguage(): void
    {
        $configPath = $this->includesDir . '/config.php';
        $configContent = file_get_contents($configPath);
        
        $this->assertStringContainsString('initLanguage()', $configContent, 
            'Config should call initLanguage()');
    }

    /**
     * Test that including config.php initializes language system
     * Requirements: 2.3, 8.2
     */
    public function testIncludingConfigInitializesLanguageSystem(): void
    {
        // Note: Session might already be active from previous tests
        $sessionWasActive = (session_status() === PHP_SESSION_ACTIVE);
        
        // Include config.php
        require_once $this->includesDir . '/config.php';
        
        // Verify session is now active
        $this->assertEquals(PHP_SESSION_ACTIVE, session_status(), 
            'Session should be active after including config');
        
        // Verify language is set (either to default Spanish or preserved from previous test)
        $this->assertArrayHasKey('language', $_SESSION, 
            'Session should have language key');
        $this->assertContains($_SESSION['language'], ['es', 'en'], 
            'Language should be either Spanish or English');
        
        // Verify language functions are available
        $this->assertTrue(function_exists('getCurrentLanguage'), 
            'getCurrentLanguage function should be available');
        $this->assertTrue(function_exists('setLanguage'), 
            'setLanguage function should be available');
        $this->assertTrue(function_exists('t'), 
            't function should be available');
        
        // Verify translations work
        $currentLang = getCurrentLanguage();
        $expectedHome = ($currentLang === 'es') ? 'Inicio' : 'Home';
        $this->assertEquals($expectedHome, t('nav.home'), 
            'Translation function should work after config initialization');
    }

    /**
     * Test that config.php preserves existing language preference
     * Requirements: 2.3, 2.6, 8.2
     */
    public function testConfigPreservesExistingLanguagePreference(): void
    {
        // Start session and set language to English before including config
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['language'] = 'en';
        
        // Include language.php first (since config uses require_once)
        require_once $this->includesDir . '/language.php';
        
        // Call initLanguage to simulate what config.php does
        initLanguage();
        
        // Verify language is still English
        $this->assertEquals('en', $_SESSION['language'], 
            'Config should preserve existing language preference');
        
        // Verify translations work in English
        $this->assertEquals('Home', t('nav.home'), 
            'Translation should work in preserved language');
    }

    /**
     * Test that config.php can be included multiple times safely
     * Requirements: 8.2
     */
    public function testConfigCanBeIncludedMultipleTimes(): void
    {
        // Include config.php first time
        require_once $this->includesDir . '/config.php';
        $firstLang = getCurrentLanguage();
        
        // Include config.php second time (should not cause errors)
        require_once $this->includesDir . '/config.php';
        $secondLang = getCurrentLanguage();
        
        // Verify language is consistent
        $this->assertEquals($firstLang, $secondLang, 
            'Language should be consistent across multiple includes');
        
        // Verify session is still active
        $this->assertEquals(PHP_SESSION_ACTIVE, session_status(), 
            'Session should still be active after multiple includes');
    }

    /**
     * Test that config.php initializes language before defining constants
     * Requirements: 2.3, 8.2
     */
    public function testConfigInitializesLanguageBeforeConstants(): void
    {
        $configPath = $this->includesDir . '/config.php';
        $configContent = file_get_contents($configPath);
        
        // Find positions of key elements
        $sessionPos = strpos($configContent, 'session_start()');
        $languageIncludePos = strpos($configContent, 'language.php');
        $initLanguagePos = strpos($configContent, 'initLanguage()');
        $siteNamePos = strpos($configContent, "define('SITE_NAME'");
        
        // Verify order
        $this->assertNotFalse($sessionPos, 'session_start() should be present');
        $this->assertNotFalse($languageIncludePos, 'language.php include should be present');
        $this->assertNotFalse($initLanguagePos, 'initLanguage() should be present');
        $this->assertNotFalse($siteNamePos, 'SITE_NAME constant should be present');
        
        $this->assertLessThan($languageIncludePos, $sessionPos, 
            'session_start() should come before language.php include');
        $this->assertLessThan($initLanguagePos, $languageIncludePos, 
            'language.php include should come before initLanguage()');
        $this->assertLessThan($siteNamePos, $initLanguagePos, 
            'initLanguage() should come before constant definitions');
    }
}
