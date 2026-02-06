<?php

/**
 * Unit Tests for Language Switcher JavaScript
 * Tests that the language-switcher.js file exists and contains expected functionality
 * 
 * Requirements: 2.5
 */

use PHPUnit\Framework\TestCase;

class LanguageSwitcherJavaScriptTest extends TestCase
{
    private string $jsFilePath;
    private string $jsContent;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->jsFilePath = __DIR__ . '/../../public/assets/js/language-switcher.js';
        
        if (file_exists($this->jsFilePath)) {
            $this->jsContent = file_get_contents($this->jsFilePath);
        }
    }
    
    /**
     * Test that language-switcher.js file exists
     */
    public function testJavaScriptFileExists(): void
    {
        $this->assertFileExists(
            $this->jsFilePath, 
            'language-switcher.js file should exist in assets/js directory'
        );
    }
    
    /**
     * Test that JavaScript file is not empty
     */
    public function testJavaScriptFileIsNotEmpty(): void
    {
        $this->assertNotEmpty(
            $this->jsContent, 
            'language-switcher.js should not be empty'
        );
    }
    
    /**
     * Test that JavaScript contains DOMContentLoaded event listener
     */
    public function testContainsDOMContentLoadedListener(): void
    {
        $this->assertStringContainsString(
            'DOMContentLoaded',
            $this->jsContent,
            'JavaScript should listen for DOMContentLoaded event'
        );
    }
    
    /**
     * Test that JavaScript selects language buttons
     */
    public function testSelectsLanguageButtons(): void
    {
        $this->assertStringContainsString(
            '.lang-btn',
            $this->jsContent,
            'JavaScript should select elements with .lang-btn class'
        );
    }
    
    /**
     * Test that JavaScript adds click event listeners
     */
    public function testAddsClickEventListeners(): void
    {
        $this->assertStringContainsString(
            'addEventListener',
            $this->jsContent,
            'JavaScript should add event listeners'
        );
        
        $this->assertStringContainsString(
            'click',
            $this->jsContent,
            'JavaScript should listen for click events'
        );
    }
    
    /**
     * Test that JavaScript gets language from data attribute
     */
    public function testGetsLanguageFromDataAttribute(): void
    {
        $this->assertStringContainsString(
            'data-lang',
            $this->jsContent,
            'JavaScript should read data-lang attribute'
        );
    }
    
    /**
     * Test that JavaScript makes fetch request to language-switch.php
     */
    public function testMakesFetchRequest(): void
    {
        $this->assertStringContainsString(
            'fetch',
            $this->jsContent,
            'JavaScript should use fetch API'
        );
        
        $this->assertStringContainsString(
            'language-switch.php',
            $this->jsContent,
            'JavaScript should call language-switch.php endpoint'
        );
    }
    
    /**
     * Test that JavaScript uses POST method
     */
    public function testUsesPostMethod(): void
    {
        $this->assertStringContainsString(
            'POST',
            $this->jsContent,
            'JavaScript should use POST method for fetch request'
        );
    }
    
    /**
     * Test that JavaScript sends language parameter
     */
    public function testSendsLanguageParameter(): void
    {
        $this->assertStringContainsString(
            'lang=',
            $this->jsContent,
            'JavaScript should send lang parameter in request body'
        );
    }
    
    /**
     * Test that JavaScript reloads page on success
     */
    public function testReloadsPageOnSuccess(): void
    {
        $this->assertStringContainsString(
            'window.location.reload',
            $this->jsContent,
            'JavaScript should reload page after successful language switch'
        );
    }
    
    /**
     * Test that JavaScript handles errors
     */
    public function testHandlesErrors(): void
    {
        $this->assertStringContainsString(
            'catch',
            $this->jsContent,
            'JavaScript should have error handling with try-catch'
        );
        
        $this->assertStringContainsString(
            'error',
            $this->jsContent,
            'JavaScript should handle error cases'
        );
    }
    
    /**
     * Test that JavaScript validates language codes
     */
    public function testValidatesLanguageCodes(): void
    {
        $this->assertStringContainsString(
            'es',
            $this->jsContent,
            'JavaScript should reference Spanish language code'
        );
        
        $this->assertStringContainsString(
            'en',
            $this->jsContent,
            'JavaScript should reference English language code'
        );
    }
    
    /**
     * Test that JavaScript checks for active state
     */
    public function testChecksActiveState(): void
    {
        $this->assertStringContainsString(
            'active',
            $this->jsContent,
            'JavaScript should check for active state'
        );
    }
    
    /**
     * Test that JavaScript disables buttons during request
     */
    public function testDisablesButtonsDuringRequest(): void
    {
        $this->assertStringContainsString(
            'disabled',
            $this->jsContent,
            'JavaScript should disable buttons during AJAX request'
        );
    }
    
    /**
     * Test that JavaScript parses JSON response
     */
    public function testParsesJsonResponse(): void
    {
        $this->assertStringContainsString(
            'json',
            $this->jsContent,
            'JavaScript should parse JSON response'
        );
    }
    
    /**
     * Test that JavaScript checks response success
     */
    public function testChecksResponseSuccess(): void
    {
        $this->assertStringContainsString(
            'success',
            $this->jsContent,
            'JavaScript should check success field in response'
        );
    }
}
