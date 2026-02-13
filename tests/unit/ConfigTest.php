<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Configuration Test
 * Tests the configuration file structure and initialization
 */
class ConfigTest extends TestCase
{
    private string $configPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->configPath = __DIR__ . '/../../includes/config.php';
    }

    public function testConfigFileExists(): void
    {
        $this->assertFileExists($this->configPath);
    }

    public function testConfigIncludesLanguageSystem(): void
    {
        $content = file_get_contents($this->configPath);
        
        $this->assertStringContainsString('language.php', $content);
        $this->assertStringContainsString('require_once', $content);
    }

    public function testConfigStartsSession(): void
    {
        $content = file_get_contents($this->configPath);
        
        $this->assertStringContainsString('session_start()', $content);
    }

    public function testConfigInitializesLanguage(): void
    {
        $content = file_get_contents($this->configPath);
        
        $this->assertStringContainsString('initLanguage()', $content);
    }

    public function testConfigDefinesRequiredConstants(): void
    {
        $content = file_get_contents($this->configPath);
        
        $this->assertStringContainsString("define('SITE_NAME'", $content);
        $this->assertStringContainsString("define('CONTACT_EMAIL'", $content);
        $this->assertStringContainsString("define('COURSE_DURATION'", $content);
    }

    public function testConfigDefinesCurriculumArray(): void
    {
        $content = file_get_contents($this->configPath);
        
        $this->assertStringContainsString('$curriculum', $content);
        $this->assertStringContainsString('Cloud Fundamentals', $content);
        $this->assertStringContainsString('Cloud Architecture', $content);
    }

    public function testConfigDefinesFacultyArray(): void
    {
        $content = file_get_contents($this->configPath);
        
        $this->assertStringContainsString('$faculty', $content);
    }
}
