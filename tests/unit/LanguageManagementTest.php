<?php
/**
 * Language Management Test
 * Tests language management functions for bilingual support
 * 
 * Requirements: 2.3, 2.6
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class LanguageManagementTest extends TestCase
{
    private string $includesDir;
    private string $langDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->includesDir = __DIR__ . '/../../includes';
        $this->langDir = __DIR__ . '/../../lang';
        
        // Clear any existing session
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        
        // Clear session superglobal
        $_SESSION = [];
        
        // Include language management functions
        require_once $this->includesDir . '/language.php';
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
     * Test language.php file exists
     */
    public function testLanguageFileExists(): void
    {
        $languagePath = $this->includesDir . '/language.php';
        $this->assertFileExists($languagePath, 'Language management file should exist');
    }

    /**
     * Test initLanguage() starts session and sets default language to Spanish
     * Requirements: 2.3
     */
    public function testInitLanguageSetsDefaultToSpanish(): void
    {
        // Call initLanguage
        initLanguage();
        
        // Verify session is started
        $this->assertEquals(PHP_SESSION_ACTIVE, session_status(), 'Session should be active');
        
        // Verify default language is Spanish
        $this->assertEquals('es', $_SESSION['language'], 'Default language should be Spanish');
    }

    /**
     * Test initLanguage() preserves existing language preference
     * Requirements: 2.6
     */
    public function testInitLanguagePreservesExistingPreference(): void
    {
        // Start session and set language to English
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['language'] = 'en';
        
        // Call initLanguage
        initLanguage();
        
        // Verify language is still English
        $this->assertEquals('en', $_SESSION['language'], 'Language preference should be preserved');
    }

    /**
     * Test getCurrentLanguage() returns Spanish by default
     * Requirements: 2.3
     */
    public function testGetCurrentLanguageReturnsSpanishByDefault(): void
    {
        // Initialize language system
        initLanguage();
        
        // Get current language
        $currentLang = getCurrentLanguage();
        
        // Verify it's Spanish
        $this->assertEquals('es', $currentLang, 'Current language should be Spanish by default');
    }

    /**
     * Test getCurrentLanguage() returns correct language after setting
     * Requirements: 2.6
     */
    public function testGetCurrentLanguageReturnsSetLanguage(): void
    {
        // Initialize and set language to English
        initLanguage();
        setLanguage('en');
        
        // Get current language
        $currentLang = getCurrentLanguage();
        
        // Verify it's English
        $this->assertEquals('en', $currentLang, 'Current language should be English after setting');
    }

    /**
     * Test setLanguage() accepts valid language codes
     * Requirements: 2.3
     */
    public function testSetLanguageAcceptsValidCodes(): void
    {
        initLanguage();
        
        // Test Spanish
        $result = setLanguage('es');
        $this->assertTrue($result, 'setLanguage should return true for valid code "es"');
        $this->assertEquals('es', getCurrentLanguage(), 'Language should be set to Spanish');
        
        // Test English
        $result = setLanguage('en');
        $this->assertTrue($result, 'setLanguage should return true for valid code "en"');
        $this->assertEquals('en', getCurrentLanguage(), 'Language should be set to English');
    }

    /**
     * Test setLanguage() rejects invalid language codes
     * Requirements: 2.3
     */
    public function testSetLanguageRejectsInvalidCodes(): void
    {
        initLanguage();
        $originalLang = getCurrentLanguage();
        
        // Test invalid codes
        $invalidCodes = ['fr', 'de', 'pt', 'invalid', '', '123', 'ES', 'EN'];
        
        foreach ($invalidCodes as $code) {
            $result = setLanguage($code);
            $this->assertFalse($result, "setLanguage should return false for invalid code '{$code}'");
            $this->assertEquals($originalLang, getCurrentLanguage(), 
                "Language should not change for invalid code '{$code}'");
        }
    }

    /**
     * Test t() function returns translated strings for Spanish
     * Requirements: 2.3
     */
    public function testTranslationFunctionReturnsSpanishStrings(): void
    {
        initLanguage();
        setLanguage('es');
        
        // Test navigation translations
        $this->assertEquals('Inicio', t('nav.home'), 'Should return Spanish translation for nav.home');
        $this->assertEquals('Currículo', t('nav.curriculum'), 'Should return Spanish translation for nav.curriculum');
        $this->assertEquals('Profesores', t('nav.faculty'), 'Should return Spanish translation for nav.faculty');
        $this->assertEquals('Contacto', t('nav.contact'), 'Should return Spanish translation for nav.contact');
        
        // Test course translations
        $this->assertEquals('Paradigmas Emergentes en Computación en la Nube', t('course.title'), 
            'Should return Spanish course title');
        $this->assertEquals('12 sesiones', t('course.duration'), 
            'Should return Spanish course duration');
        $this->assertEquals('Híbrido', t('course.mode'), 
            'Should return Spanish course mode');
    }

    /**
     * Test t() function returns translated strings for English
     * Requirements: 2.3
     */
    public function testTranslationFunctionReturnsEnglishStrings(): void
    {
        initLanguage();
        setLanguage('en');
        
        // Test navigation translations
        $this->assertEquals('Home', t('nav.home'), 'Should return English translation for nav.home');
        $this->assertEquals('Curriculum', t('nav.curriculum'), 'Should return English translation for nav.curriculum');
        $this->assertEquals('Faculty', t('nav.faculty'), 'Should return English translation for nav.faculty');
        $this->assertEquals('Contact', t('nav.contact'), 'Should return English translation for nav.contact');
        
        // Test course translations
        $this->assertEquals('Emerging Paradigms on Cloud Computing', t('course.title'), 
            'Should return English course title');
        $this->assertEquals('12 sessions', t('course.duration'), 
            'Should return English course duration');
        $this->assertEquals('Hybrid', t('course.mode'), 
            'Should return English course mode');
    }

    /**
     * Test t() function returns arrays for nested translations
     * Requirements: 2.3
     */
    public function testTranslationFunctionReturnsArrays(): void
    {
        initLanguage();
        setLanguage('en');
        
        // Test faculty credentials (array)
        $credentials = t('faculty.credentials');
        $this->assertIsArray($credentials, 'Should return array for faculty.credentials');
        $this->assertCount(3, $credentials, 'Should have 3 credentials');
        $this->assertStringContainsString('Systems Engineering', $credentials[0], 
            'First credential should mention Systems Engineering');
        $this->assertStringContainsString('AWS Solution Architect', $credentials[2], 
            'Third credential should mention AWS certification');
    }

    /**
     * Test t() function fallback for missing translation keys
     * Requirements: 2.3
     */
    public function testTranslationFunctionFallbackForMissingKeys(): void
    {
        initLanguage();
        
        // Test non-existent keys
        $result = t('nonexistent.key');
        $this->assertEquals('nonexistent.key', $result, 
            'Should return key itself when translation not found');
        
        $result = t('nav.nonexistent');
        $this->assertEquals('nav.nonexistent', $result, 
            'Should return key itself for missing nested key');
    }

    /**
     * Test t() function handles deeply nested keys
     * Requirements: 2.3
     */
    public function testTranslationFunctionHandlesDeeplyNestedKeys(): void
    {
        initLanguage();
        setLanguage('en');
        
        // Test deeply nested keys
        $this->assertEquals('Home', t('nav.home'), 'Should handle 2-level nesting');
        $this->assertEquals('Email', t('contact.email_label'), 'Should handle 2-level nesting');
    }

    /**
     * Test getTranslations() returns complete translation array
     * Requirements: 2.3
     */
    public function testGetTranslationsReturnsCompleteArray(): void
    {
        initLanguage();
        setLanguage('en');
        
        $translations = getTranslations();
        
        $this->assertIsArray($translations, 'Should return an array');
        $this->assertNotEmpty($translations, 'Should not be empty');
        
        // Verify main sections exist
        $this->assertArrayHasKey('nav', $translations, 'Should have nav section');
        $this->assertArrayHasKey('course', $translations, 'Should have course section');
        $this->assertArrayHasKey('faculty', $translations, 'Should have faculty section');
        $this->assertArrayHasKey('contact', $translations, 'Should have contact section');
        $this->assertArrayHasKey('social', $translations, 'Should have social section');
    }

    /**
     * Test language persistence across multiple function calls
     * Requirements: 2.6
     */
    public function testLanguagePersistenceAcrossMultipleCalls(): void
    {
        // Initialize and set to English
        initLanguage();
        setLanguage('en');
        
        // Verify English is set
        $this->assertEquals('en', getCurrentLanguage());
        $this->assertEquals('Home', t('nav.home'));
        
        // Call getCurrentLanguage multiple times
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals('en', getCurrentLanguage(), 
                'Language should persist across multiple calls');
        }
        
        // Switch to Spanish
        setLanguage('es');
        
        // Verify Spanish is set and persists
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals('es', getCurrentLanguage(), 
                'Language should persist after switching');
            $this->assertEquals('Inicio', t('nav.home'), 
                'Translations should update after language switch');
        }
    }

    /**
     * Test contact information translations
     * Requirements: 2.3
     */
    public function testContactInformationTranslations(): void
    {
        initLanguage();
        
        // Test Spanish
        setLanguage('es');
        $this->assertEquals('luis.galvis-e@escuelaing.edu.co', t('contact.email'), 
            'Should return correct email');
        $this->assertEquals('+57 3017859109', t('contact.phone'), 
            'Should return correct phone');
        $this->assertEquals('Correo Electrónico', t('contact.email_label'), 
            'Should return Spanish email label');
        $this->assertEquals('Teléfono', t('contact.phone_label'), 
            'Should return Spanish phone label');
        
        // Test English
        setLanguage('en');
        $this->assertEquals('luis.galvis-e@escuelaing.edu.co', t('contact.email'), 
            'Should return correct email');
        $this->assertEquals('+57 3017859109', t('contact.phone'), 
            'Should return correct phone');
        $this->assertEquals('Email', t('contact.email_label'), 
            'Should return English email label');
        $this->assertEquals('Phone', t('contact.phone_label'), 
            'Should return English phone label');
    }

    /**
     * Test social media translations
     * Requirements: 2.3
     */
    public function testSocialMediaTranslations(): void
    {
        initLanguage();
        setLanguage('en');
        
        // Test social media platform names
        $this->assertEquals('LinkedIn', t('social.linkedin'), 'Should return LinkedIn');
        $this->assertEquals('GitHub', t('social.github'), 'Should return GitHub');
        $this->assertEquals('Instagram', t('social.instagram'), 'Should return Instagram');
        
        // Test social media URLs
        $this->assertEquals('https://www.linkedin.com/in/luiscarlosgalvisespitia/', 
            t('social.linkedin_url'), 'Should return LinkedIn URL');
        $this->assertEquals('https://github.com/luiscarlosge/', 
            t('social.github_url'), 'Should return GitHub URL');
        $this->assertEquals('https://www.instagram.com/luchogalvis/', 
            t('social.instagram_url'), 'Should return Instagram URL');
    }

    /**
     * Test faculty information translations
     * Requirements: 2.3
     */
    public function testFacultyInformationTranslations(): void
    {
        initLanguage();
        
        // Test Spanish
        setLanguage('es');
        $this->assertEquals('Luis Carlos Galvis Espitia', t('faculty.name'), 
            'Should return faculty name');
        $this->assertEquals('Profesor de Computación en la Nube', t('faculty.position'), 
            'Should return Spanish faculty position');
        
        // Test English
        setLanguage('en');
        $this->assertEquals('Luis Carlos Galvis Espitia', t('faculty.name'), 
            'Should return faculty name');
        $this->assertEquals('Cloud Computing Lecturer', t('faculty.position'), 
            'Should return English faculty position');
    }

    /**
     * Test that both language files have the same structure
     * Requirements: 2.7
     */
    public function testBothLanguageFilesHaveSameStructure(): void
    {
        // Load both language files
        $esTranslations = require $this->langDir . '/es.php';
        $enTranslations = require $this->langDir . '/en.php';
        
        // Get keys from both
        $esKeys = $this->getArrayKeys($esTranslations);
        $enKeys = $this->getArrayKeys($enTranslations);
        
        // Sort for comparison
        sort($esKeys);
        sort($enKeys);
        
        // Verify they have the same keys
        $this->assertEquals($esKeys, $enKeys, 
            'Spanish and English translation files should have the same keys');
    }

    /**
     * Helper method to get all keys from nested array
     */
    private function getArrayKeys(array $array, string $prefix = ''): array
    {
        $keys = [];
        foreach ($array as $key => $value) {
            $fullKey = $prefix ? $prefix . '.' . $key : $key;
            $keys[] = $fullKey;
            if (is_array($value)) {
                $keys = array_merge($keys, $this->getArrayKeys($value, $fullKey));
            }
        }
        return $keys;
    }
}
