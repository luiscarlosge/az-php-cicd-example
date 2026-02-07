<?php
/**
 * Component Test
 * Tests that common components (header, navigation, footer, config) work correctly
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ComponentTest extends TestCase
{
    private string $includesDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->includesDir = __DIR__ . '/../../includes';
    }

    /**
     * Helper method to capture component output
     */
    private function renderComponent(string $componentPath): string
    {
        ob_start();
        include $componentPath;
        $output = ob_get_clean();
        return $output;
    }

    /**
     * Test header component includes required meta tags and CSS link
     * Requirements: 2.2
     */
    public function testHeaderIncludesRequiredMetaTagsAndCss(): void
    {
        $headerPath = $this->includesDir . '/header.php';
        $this->assertFileExists($headerPath, 'Header component file should exist');

        $output = $this->renderComponent($headerPath);

        // Verify HTML5 doctype
        $this->assertStringContainsString('<!DOCTYPE html>', $output);
        
        // Verify required meta tags
        $this->assertStringContainsString('<meta charset="UTF-8">', $output);
        $this->assertStringContainsString('name="viewport"', $output);
        $this->assertStringContainsString('width=device-width, initial-scale=1.0', $output);
        $this->assertStringContainsString('name="description"', $output);
        
        // Verify CSS stylesheet link
        $this->assertStringContainsString('<link rel="stylesheet"', $output);
        $this->assertStringContainsString('href="/public/assets/css/style.css"', $output);
        
        // Verify header element
        $this->assertStringContainsString('<header>', $output);
        
        // Verify site title is present
        $this->assertStringContainsString('Post Graduate Course in Cloud Computing', $output);
        
        // Verify logo image
        $this->assertStringContainsString('/public/assets/images/logo.svg', $output);
    }

    /**
     * Test header includes language switcher component
     * Requirements: 2.4
     */
    public function testHeaderIncludesLanguageSwitcher(): void
    {
        $headerPath = $this->includesDir . '/header.php';
        $this->assertFileExists($headerPath, 'Header component file should exist');

        $output = $this->renderComponent($headerPath);

        // Verify language switcher container is present
        $this->assertStringContainsString('class="language-switcher"', $output);
        
        // Verify ES button is present
        $this->assertStringContainsString('data-lang="es"', $output);
        $this->assertMatchesRegularExpression('/ES\s*<\/button>/', $output, 'ES button should be present');
        
        // Verify EN button is present
        $this->assertStringContainsString('data-lang="en"', $output);
        $this->assertMatchesRegularExpression('/EN\s*<\/button>/', $output, 'EN button should be present');
        
        // Verify lang-btn class is present
        $this->assertStringContainsString('class="lang-btn', $output);
        
        // Verify aria labels for accessibility
        $this->assertStringContainsString('aria-label="Switch to Spanish"', $output);
        $this->assertStringContainsString('aria-label="Switch to English"', $output);
    }

    /**
     * Test language switcher shows active state for current language
     * Requirements: 2.4
     */
    public function testLanguageSwitcherShowsActiveState(): void
    {
        $headerPath = $this->includesDir . '/header.php';
        
        // Test with Spanish as current language
        $_SESSION['language'] = 'es';
        $output = $this->renderComponent($headerPath);
        
        // Verify ES button has active class (class comes before data-lang in the HTML)
        $this->assertMatchesRegularExpression('/class="lang-btn[^"]*active[^"]*"[^>]*data-lang="es"/', $output, 'ES button should have active class when Spanish is selected');
        
        // Test with English as current language
        $_SESSION['language'] = 'en';
        $output = $this->renderComponent($headerPath);
        
        // Verify EN button has active class
        $this->assertMatchesRegularExpression('/class="lang-btn[^"]*active[^"]*"[^>]*data-lang="en"/', $output, 'EN button should have active class when English is selected');
    }

    /**
     * Test navigation component includes all page links
     * Requirements: 2.2
     */
    public function testNavigationIncludesAllPageLinks(): void
    {
        $navPath = $this->includesDir . '/navigation.php';
        $this->assertFileExists($navPath, 'Navigation component file should exist');

        // Set PHP_SELF to simulate being on a page
        $_SERVER['PHP_SELF'] = '/index.php';
        
        $output = $this->renderComponent($navPath);

        // Verify nav element
        $this->assertStringContainsString('<nav', $output);
        
        // Verify all required page links are present
        $this->assertStringContainsString('Home', $output);
        $this->assertStringContainsString('Curriculum', $output);
        $this->assertStringContainsString('Faculty', $output);
        $this->assertStringContainsString('Admissions', $output);
        $this->assertStringContainsString('Contact', $output);
        
        // Verify links point to correct pages
        $this->assertStringContainsString('href="index.php"', $output);
        $this->assertStringContainsString('href="curriculum.php"', $output);
        $this->assertStringContainsString('href="faculty.php"', $output);
        $this->assertStringContainsString('href="admissions.php"', $output);
        $this->assertStringContainsString('href="contact.php"', $output);
        
        // Verify mobile menu toggle button
        $this->assertStringContainsString('mobile-menu-toggle', $output);
        $this->assertStringContainsString('hamburger-icon', $output);
        
        // Verify active page highlighting (should have 'active' class on current page)
        $this->assertStringContainsString('class="nav-link active"', $output);
    }

    /**
     * Test footer component includes copyright and contact information
     * Requirements: 2.2
     */
    public function testFooterIncludesCopyrightAndContact(): void
    {
        $footerPath = $this->includesDir . '/footer.php';
        $this->assertFileExists($footerPath, 'Footer component file should exist');

        $output = $this->renderComponent($footerPath);

        // Verify footer element
        $this->assertStringContainsString('<footer>', $output);
        
        // Verify copyright information with dynamic year
        $currentYear = date('Y');
        $this->assertStringContainsString('&copy;', $output);
        $this->assertStringContainsString($currentYear, $output);
        $this->assertStringContainsString('Post Graduate Course in Cloud Computing', $output);
        $this->assertStringContainsString('All rights reserved', $output);
        
        // Verify contact email link
        $this->assertStringContainsString('luis.galvis-e@escuelaing.edu.co', $output);
        $this->assertStringContainsString('mailto:luis.galvis-e@escuelaing.edu.co', $output);
        
        // Verify contact section
        $this->assertStringContainsString('Contact Us', $output);
        
        // Verify social media links section
        $this->assertStringContainsString('Follow Us', $output);
        $this->assertStringContainsString('social-link', $output);
        
        // Verify closing body and html tags
        $this->assertStringContainsString('</body>', $output);
        $this->assertStringContainsString('</html>', $output);
    }

    /**
     * Test configuration file defines all required constants
     * Requirements: 2.3
     */
    public function testConfigurationDefinesRequiredConstants(): void
    {
        $configPath = $this->includesDir . '/config.php';
        $this->assertFileExists($configPath, 'Configuration file should exist');

        // Include the configuration file
        require_once $configPath;

        // Verify site configuration constants
        $this->assertTrue(defined('SITE_NAME'), 'SITE_NAME constant should be defined');
        $this->assertTrue(defined('SITE_URL'), 'SITE_URL constant should be defined');
        $this->assertTrue(defined('CONTACT_EMAIL'), 'CONTACT_EMAIL constant should be defined');
        
        // Verify course information constants
        $this->assertTrue(defined('COURSE_DURATION'), 'COURSE_DURATION constant should be defined');
        $this->assertTrue(defined('COURSE_START_DATE'), 'COURSE_START_DATE constant should be defined');
        $this->assertTrue(defined('COURSE_MODE'), 'COURSE_MODE constant should be defined');
        
        // Verify constant values are not empty
        $this->assertNotEmpty(SITE_NAME, 'SITE_NAME should not be empty');
        $this->assertNotEmpty(SITE_URL, 'SITE_URL should not be empty');
        $this->assertNotEmpty(CONTACT_EMAIL, 'CONTACT_EMAIL should not be empty');
        $this->assertNotEmpty(COURSE_DURATION, 'COURSE_DURATION should not be empty');
        $this->assertNotEmpty(COURSE_START_DATE, 'COURSE_START_DATE should not be empty');
        $this->assertNotEmpty(COURSE_MODE, 'COURSE_MODE should not be empty');
        
        // Verify expected values
        $this->assertEquals('Post Graduate Course in Cloud Computing', SITE_NAME);
        $this->assertEquals('luis.galvis-e@escuelaing.edu.co', CONTACT_EMAIL);
        $this->assertEquals('2 years', COURSE_DURATION);
        $this->assertEquals('September 2024', COURSE_START_DATE);
        $this->assertEquals('Full-time / Part-time', COURSE_MODE);
    }

    /**
     * Test configuration file defines curriculum array
     * Requirements: 2.3
     */
    public function testConfigurationDefinesCurriculumArray(): void
    {
        $configPath = $this->includesDir . '/config.php';
        
        // Include the configuration file
        require $configPath;

        // Verify curriculum array exists
        $this->assertIsArray($curriculum, 'Curriculum should be an array');
        $this->assertNotEmpty($curriculum, 'Curriculum should not be empty');
        
        // Verify curriculum has expected number of modules
        $this->assertCount(6, $curriculum, 'Curriculum should have 6 modules');
        
        // Verify each module has required keys
        foreach ($curriculum as $module) {
            $this->assertArrayHasKey('module', $module, 'Module should have "module" key');
            $this->assertArrayHasKey('topics', $module, 'Module should have "topics" key');
            $this->assertArrayHasKey('credits', $module, 'Module should have "credits" key');
            
            $this->assertIsString($module['module'], 'Module name should be a string');
            $this->assertIsArray($module['topics'], 'Topics should be an array');
            $this->assertIsInt($module['credits'], 'Credits should be an integer');
            
            $this->assertNotEmpty($module['module'], 'Module name should not be empty');
            $this->assertNotEmpty($module['topics'], 'Topics should not be empty');
            $this->assertGreaterThan(0, $module['credits'], 'Credits should be greater than 0');
        }
    }

    /**
     * Test configuration file defines faculty array
     * Requirements: 2.3
     */
    public function testConfigurationDefinesFacultyArray(): void
    {
        $configPath = $this->includesDir . '/config.php';
        
        // Include the configuration file
        require $configPath;

        // Verify faculty array exists
        $this->assertIsArray($faculty, 'Faculty should be an array');
        $this->assertNotEmpty($faculty, 'Faculty should not be empty');
        
        // Verify faculty has at least 2 members
        $this->assertGreaterThanOrEqual(2, count($faculty), 'Faculty should have at least 2 members');
        
        // Verify each faculty member has required keys
        foreach ($faculty as $member) {
            $this->assertArrayHasKey('name', $member, 'Faculty member should have "name" key');
            $this->assertArrayHasKey('title', $member, 'Faculty member should have "title" key');
            $this->assertArrayHasKey('credentials', $member, 'Faculty member should have "credentials" key');
            $this->assertArrayHasKey('specialization', $member, 'Faculty member should have "specialization" key');
            $this->assertArrayHasKey('image', $member, 'Faculty member should have "image" key');
            
            $this->assertIsString($member['name'], 'Name should be a string');
            $this->assertIsString($member['title'], 'Title should be a string');
            $this->assertIsString($member['credentials'], 'Credentials should be a string');
            $this->assertIsString($member['specialization'], 'Specialization should be a string');
            $this->assertIsString($member['image'], 'Image should be a string');
            
            $this->assertNotEmpty($member['name'], 'Name should not be empty');
            $this->assertNotEmpty($member['title'], 'Title should not be empty');
            $this->assertNotEmpty($member['credentials'], 'Credentials should not be empty');
            $this->assertNotEmpty($member['specialization'], 'Specialization should not be empty');
            $this->assertNotEmpty($member['image'], 'Image path should not be empty');
        }
    }
}
