<?php

/**
 * Unit Tests for Language Switch Endpoint
 * Tests the language-switch.php endpoint functionality
 * 
 * Requirements: 2.5
 */

use PHPUnit\Framework\TestCase;

// Define constant to prevent endpoint from executing during tests
define('PHPUNIT_RUNNING', true);

class LanguageSwitchEndpointTest extends TestCase
{
    private string $endpointPath;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->endpointPath = __DIR__ . '/../../public/language-switch.php';
        
        // Ensure session is clean for each test
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        
        // Include the endpoint file to get access to processLanguageSwitch function
        require_once $this->endpointPath;
    }
    
    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Clean up session after each test
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        
        // Reset superglobals
        $_POST = [];
        $_SERVER = [];
    }
    
    /**
     * Test that endpoint exists
     */
    public function testEndpointFileExists(): void
    {
        $this->assertFileExists($this->endpointPath, 'language-switch.php endpoint file should exist');
    }
    
    /**
     * Test that endpoint rejects GET requests
     */
    public function testRejectsGetRequests(): void
    {
        $response = processLanguageSwitch('GET', []);
        
        $this->assertEquals(405, $response['status_code'], 'GET request should return 405 status');
        $this->assertFalse($response['success'], 'GET request should fail');
        $this->assertArrayHasKey('error', $response, 'Error message should be present');
        $this->assertStringContainsString('Method not allowed', $response['error']);
    }
    
    /**
     * Test that endpoint requires language parameter
     */
    public function testRequiresLanguageParameter(): void
    {
        $response = processLanguageSwitch('POST', []); // No language parameter
        
        $this->assertEquals(400, $response['status_code'], 'Missing parameter should return 400 status');
        $this->assertFalse($response['success'], 'Request without language parameter should fail');
        $this->assertArrayHasKey('error', $response, 'Error message should be present');
        $this->assertStringContainsString('required', $response['error']);
    }
    
    /**
     * Test that endpoint validates language code
     */
    public function testValidatesLanguageCode(): void
    {
        $response = processLanguageSwitch('POST', ['lang' => 'fr']); // Invalid language code
        
        $this->assertEquals(400, $response['status_code'], 'Invalid language should return 400 status');
        $this->assertFalse($response['success'], 'Invalid language code should fail');
        $this->assertArrayHasKey('error', $response, 'Error message should be present');
        $this->assertStringContainsString('Invalid language code', $response['error']);
    }
    
    /**
     * Test that endpoint accepts Spanish language code
     */
    public function testAcceptsSpanishLanguageCode(): void
    {
        $response = processLanguageSwitch('POST', ['lang' => 'es']);
        
        $this->assertEquals(200, $response['status_code'], 'Valid Spanish request should return 200 status');
        $this->assertTrue($response['success'], 'Spanish language code should be accepted');
        $this->assertEquals('es', $response['language'], 'Response should confirm Spanish language');
        $this->assertArrayHasKey('message', $response, 'Success message should be present');
    }
    
    /**
     * Test that endpoint accepts English language code
     */
    public function testAcceptsEnglishLanguageCode(): void
    {
        $response = processLanguageSwitch('POST', ['lang' => 'en']);
        
        $this->assertEquals(200, $response['status_code'], 'Valid English request should return 200 status');
        $this->assertTrue($response['success'], 'English language code should be accepted');
        $this->assertEquals('en', $response['language'], 'Response should confirm English language');
        $this->assertArrayHasKey('message', $response, 'Success message should be present');
    }
    
    /**
     * Test that endpoint returns proper response structure
     */
    public function testReturnsProperResponseStructure(): void
    {
        $response = processLanguageSwitch('POST', ['lang' => 'es']);
        
        $this->assertIsArray($response, 'Response should be an array');
        $this->assertArrayHasKey('success', $response, 'Response should have success field');
        $this->assertArrayHasKey('status_code', $response, 'Response should have status_code field');
    }
    
    /**
     * Test that endpoint rejects empty language parameter
     */
    public function testRejectsEmptyLanguageParameter(): void
    {
        $response = processLanguageSwitch('POST', ['lang' => '']);
        
        $this->assertEquals(400, $response['status_code'], 'Empty parameter should return 400 status');
        $this->assertFalse($response['success'], 'Empty language parameter should fail');
        $this->assertArrayHasKey('error', $response, 'Error message should be present');
    }
    
    /**
     * Test that endpoint rejects invalid language codes
     */
    public function testRejectsVariousInvalidLanguageCodes(): void
    {
        $invalidCodes = ['fr', 'de', 'it', 'pt', 'zh', 'ja', 'ES', 'EN', 'español', 'english'];
        
        foreach ($invalidCodes as $code) {
            $response = processLanguageSwitch('POST', ['lang' => $code]);
            
            $this->assertEquals(
                400, 
                $response['status_code'], 
                "Language code '{$code}' should return 400 status"
            );
            $this->assertFalse(
                $response['success'], 
                "Language code '{$code}' should be rejected"
            );
        }
    }
    
    /**
     * Test that endpoint updates session with Spanish
     */
    public function testUpdatesSessionWithSpanish(): void
    {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $response = processLanguageSwitch('POST', ['lang' => 'es']);
        
        $this->assertTrue($response['success'], 'Language switch should succeed');
        $this->assertEquals('es', getCurrentLanguage(), 'Session should be updated to Spanish');
    }
    
    /**
     * Test that endpoint updates session with English
     */
    public function testUpdatesSessionWithEnglish(): void
    {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $response = processLanguageSwitch('POST', ['lang' => 'en']);
        
        $this->assertTrue($response['success'], 'Language switch should succeed');
        $this->assertEquals('en', getCurrentLanguage(), 'Session should be updated to English');
    }
    
    /**
     * Test that endpoint rejects PUT requests
     */
    public function testRejectsPutRequests(): void
    {
        $response = processLanguageSwitch('PUT', ['lang' => 'es']);
        
        $this->assertEquals(405, $response['status_code'], 'PUT request should return 405 status');
        $this->assertFalse($response['success'], 'PUT request should fail');
    }
    
    /**
     * Test that endpoint rejects DELETE requests
     */
    public function testRejectsDeleteRequests(): void
    {
        $response = processLanguageSwitch('DELETE', ['lang' => 'es']);
        
        $this->assertEquals(405, $response['status_code'], 'DELETE request should return 405 status');
        $this->assertFalse($response['success'], 'DELETE request should fail');
    }
}
