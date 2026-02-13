<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Translation Files Test
 * Tests that translation files exist and have consistent structure
 */
class TranslationFilesTest extends TestCase
{
    private string $langDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->langDir = __DIR__ . '/../../lang';
    }

    public function testSpanishTranslationFileExists(): void
    {
        $this->assertFileExists($this->langDir . '/es.php');
    }

    public function testEnglishTranslationFileExists(): void
    {
        $this->assertFileExists($this->langDir . '/en.php');
    }

    public function testSpanishTranslationFileReturnsArray(): void
    {
        $translations = require $this->langDir . '/es.php';
        
        $this->assertIsArray($translations);
        $this->assertNotEmpty($translations);
    }

    public function testEnglishTranslationFileReturnsArray(): void
    {
        $translations = require $this->langDir . '/en.php';
        
        $this->assertIsArray($translations);
        $this->assertNotEmpty($translations);
    }

    public function testBothLanguageFilesHaveSameStructure(): void
    {
        $esTranslations = require $this->langDir . '/es.php';
        $enTranslations = require $this->langDir . '/en.php';
        
        $esKeys = $this->getArrayKeys($esTranslations);
        $enKeys = $this->getArrayKeys($enTranslations);
        
        sort($esKeys);
        sort($enKeys);
        
        $this->assertEquals($esKeys, $enKeys, 
            'Spanish and English translation files should have the same keys');
    }

    public function testTranslationFilesContainRequiredSections(): void
    {
        $esTranslations = require $this->langDir . '/es.php';
        $enTranslations = require $this->langDir . '/en.php';
        
        $requiredSections = ['nav', 'site', 'course', 'contact'];
        
        foreach ($requiredSections as $section) {
            $this->assertArrayHasKey($section, $esTranslations, 
                "Spanish translations should have '{$section}' section");
            $this->assertArrayHasKey($section, $enTranslations, 
                "English translations should have '{$section}' section");
        }
    }

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
