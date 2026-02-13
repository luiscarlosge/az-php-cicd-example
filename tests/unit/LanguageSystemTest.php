<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Language System Test
 * Tests the language management functionality
 */
class LanguageSystemTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear session
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $_SESSION = [];
        
        // Include language system
        require_once __DIR__ . '/../../includes/language.php';
    }

    protected function tearDown(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $_SESSION = [];
        parent::tearDown();
    }

    public function testInitLanguageSetsDefaultToSpanish(): void
    {
        initLanguage();
        
        $this->assertEquals(PHP_SESSION_ACTIVE, session_status());
        $this->assertEquals('es', $_SESSION['language']);
    }

    public function testGetCurrentLanguageReturnsCorrectLanguage(): void
    {
        initLanguage();
        $this->assertEquals('es', getCurrentLanguage());
        
        setLanguage('en');
        $this->assertEquals('en', getCurrentLanguage());
    }

    public function testSetLanguageAcceptsValidCodes(): void
    {
        initLanguage();
        
        $this->assertTrue(setLanguage('es'));
        $this->assertEquals('es', getCurrentLanguage());
        
        $this->assertTrue(setLanguage('en'));
        $this->assertEquals('en', getCurrentLanguage());
    }

    public function testSetLanguageRejectsInvalidCodes(): void
    {
        initLanguage();
        
        $this->assertFalse(setLanguage('fr'));
        $this->assertFalse(setLanguage('invalid'));
        $this->assertFalse(setLanguage(''));
    }

    public function testTranslationFunctionReturnsSpanishStrings(): void
    {
        initLanguage();
        setLanguage('es');
        
        $this->assertEquals('Inicio', t('nav.home'));
        $this->assertEquals('Currículo', t('nav.curriculum'));
        $this->assertEquals('Profesores', t('nav.faculty'));
    }

    public function testTranslationFunctionReturnsEnglishStrings(): void
    {
        initLanguage();
        setLanguage('en');
        
        $this->assertEquals('Home', t('nav.home'));
        $this->assertEquals('Curriculum', t('nav.curriculum'));
        $this->assertEquals('Faculty', t('nav.faculty'));
    }

    public function testTranslationFunctionFallbackForMissingKeys(): void
    {
        initLanguage();
        
        $result = t('nonexistent.key');
        $this->assertEquals('nonexistent.key', $result);
    }

    public function testGetTranslationsReturnsCompleteArray(): void
    {
        initLanguage();
        setLanguage('en');
        
        $translations = getTranslations();
        
        $this->assertIsArray($translations);
        $this->assertArrayHasKey('nav', $translations);
        $this->assertArrayHasKey('course', $translations);
    }
}
