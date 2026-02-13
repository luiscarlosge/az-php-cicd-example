<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Page Structure Test
 * Tests that all required pages exist and have proper structure
 */
class PageStructureTest extends TestCase
{
    private string $publicDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->publicDir = __DIR__ . '/../../public';
    }

    public function testIndexPageExists(): void
    {
        $this->assertFileExists($this->publicDir . '/index.php');
    }

    public function testCurriculumPageExists(): void
    {
        $this->assertFileExists($this->publicDir . '/curriculum.php');
    }

    public function testFacultyPageExists(): void
    {
        $this->assertFileExists($this->publicDir . '/faculty.php');
    }

    public function testContactPageExists(): void
    {
        $this->assertFileExists($this->publicDir . '/contact.php');
    }

    public function testLanguageSwitchEndpointExists(): void
    {
        $this->assertFileExists($this->publicDir . '/language-switch.php');
    }

    public function testIndexPageIncludesConfig(): void
    {
        $content = file_get_contents($this->publicDir . '/index.php');
        
        $this->assertStringContainsString('config.php', $content);
    }

    public function testIndexPageIncludesHeader(): void
    {
        $content = file_get_contents($this->publicDir . '/index.php');
        
        $this->assertStringContainsString('header.php', $content);
    }

    public function testIndexPageIncludesNavigation(): void
    {
        $content = file_get_contents($this->publicDir . '/index.php');
        
        $this->assertStringContainsString('navigation.php', $content);
    }

    public function testIndexPageIncludesFooter(): void
    {
        $content = file_get_contents($this->publicDir . '/index.php');
        
        $this->assertStringContainsString('footer.php', $content);
    }

    public function testIndexPageUsesTranslationFunction(): void
    {
        $content = file_get_contents($this->publicDir . '/index.php');
        
        $this->assertStringContainsString('t(', $content);
    }

    public function testCurriculumPageUsesTranslations(): void
    {
        $content = file_get_contents($this->publicDir . '/curriculum.php');
        
        $this->assertStringContainsString("t('curriculum", $content);
    }

    public function testFacultyPageUsesTranslations(): void
    {
        $content = file_get_contents($this->publicDir . '/faculty.php');
        
        $this->assertStringContainsString("t('faculty", $content);
    }
}
