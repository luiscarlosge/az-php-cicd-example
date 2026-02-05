<?php
/**
 * Page Render Test
 * Tests that all portal pages load without PHP errors and contain expected content
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PageRenderTest extends TestCase
{
    private string $publicDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->publicDir = __DIR__ . '/../../public';
    }

    /**
     * Helper method to capture page output
     */
    private function renderPage(string $pagePath): string
    {
        ob_start();
        include $pagePath;
        $output = ob_get_clean();
        return $output;
    }

    /**
     * Test home page loads without PHP errors and contains expected content
     * Requirements: 1.1
     */
    public function testHomePageLoadsWithoutErrors(): void
    {
        $pagePath = $this->publicDir . '/index.php';
        $this->assertFileExists($pagePath, 'Home page file should exist');

        $output = $this->renderPage($pagePath);

        // Verify page loaded successfully
        $this->assertNotEmpty($output, 'Home page should produce output');
        
        // Verify expected content is present
        $this->assertStringContainsString('Post Graduate Course in Cloud Computing', $output);
        $this->assertStringContainsString('Welcome to', $output);
        $this->assertStringContainsString('Course Overview', $output);
        $this->assertStringContainsString('Key Highlights', $output);
        $this->assertStringContainsString('Duration', $output);
        $this->assertStringContainsString('Start Date', $output);
        $this->assertStringContainsString('Mode', $output);
    }

    /**
     * Test curriculum page loads and displays module information
     * Requirements: 1.2
     */
    public function testCurriculumPageLoadsAndDisplaysModules(): void
    {
        $pagePath = $this->publicDir . '/curriculum.php';
        $this->assertFileExists($pagePath, 'Curriculum page file should exist');

        $output = $this->renderPage($pagePath);

        // Verify page loaded successfully
        $this->assertNotEmpty($output, 'Curriculum page should produce output');
        
        // Verify curriculum content is present
        $this->assertStringContainsString('Curriculum', $output);
        $this->assertStringContainsString('Course Modules', $output);
        
        // Verify specific modules are displayed
        $this->assertStringContainsString('Cloud Fundamentals', $output);
        $this->assertStringContainsString('Cloud Architecture', $output);
        $this->assertStringContainsString('DevOps and CI/CD', $output);
        $this->assertStringContainsString('Cloud Platforms', $output);
        $this->assertStringContainsString('Cloud Security', $output);
        $this->assertStringContainsString('Capstone Project', $output);
        
        // Verify credits are displayed
        $this->assertStringContainsString('Credits', $output);
        
        // Verify topics are displayed
        $this->assertStringContainsString('Topics Covered:', $output);
    }

    /**
     * Test faculty page loads and displays faculty profiles
     * Requirements: 1.3
     */
    public function testFacultyPageLoadsAndDisplaysProfiles(): void
    {
        $pagePath = $this->publicDir . '/faculty.php';
        $this->assertFileExists($pagePath, 'Faculty page file should exist');

        $output = $this->renderPage($pagePath);

        // Verify page loaded successfully
        $this->assertNotEmpty($output, 'Faculty page should produce output');
        
        // Verify faculty content is present
        $this->assertStringContainsString('Our Faculty', $output);
        $this->assertStringContainsString('Meet Our Expert Faculty', $output);
        
        // Verify specific faculty members are displayed
        $this->assertStringContainsString('Dr. Jane Smith', $output);
        $this->assertStringContainsString('Prof. John Doe', $output);
        $this->assertStringContainsString('Dr. Sarah Johnson', $output);
        $this->assertStringContainsString('Prof. Michael Chen', $output);
        
        // Verify faculty details are displayed
        $this->assertStringContainsString('Credentials', $output);
        $this->assertStringContainsString('Specialization', $output);
        
        // Verify at least one credential is shown
        $this->assertStringContainsString('PhD in Computer Science', $output);
    }

    /**
     * Test admissions page loads and displays requirements
     * Requirements: 1.4
     */
    public function testAdmissionsPageLoadsAndDisplaysRequirements(): void
    {
        $pagePath = $this->publicDir . '/admissions.php';
        $this->assertFileExists($pagePath, 'Admissions page file should exist');

        $output = $this->renderPage($pagePath);

        // Verify page loaded successfully
        $this->assertNotEmpty($output, 'Admissions page should produce output');
        
        // Verify admissions content is present
        $this->assertStringContainsString('Admissions', $output);
        $this->assertStringContainsString('Enrollment Requirements', $output);
        
        // Verify requirements sections are displayed
        $this->assertStringContainsString('Educational Background', $output);
        $this->assertStringContainsString('Prerequisites', $output);
        $this->assertStringContainsString('Additional Requirements', $output);
        
        // Verify application process is displayed
        $this->assertStringContainsString('Application Process', $output);
        
        // Verify important dates section is present
        $this->assertStringContainsString('Important Dates', $output);
        
        // Verify contact information is present
        $this->assertStringContainsString('Admissions Inquiries', $output);
    }

    /**
     * Test contact page loads and displays contact form
     * Requirements: 1.5
     */
    public function testContactPageLoadsAndDisplaysForm(): void
    {
        $pagePath = $this->publicDir . '/contact.php';
        $this->assertFileExists($pagePath, 'Contact page file should exist');

        $output = $this->renderPage($pagePath);

        // Verify page loaded successfully
        $this->assertNotEmpty($output, 'Contact page should produce output');
        
        // Verify contact content is present
        $this->assertStringContainsString('Contact Us', $output);
        $this->assertStringContainsString('Send Us a Message', $output);
        
        // Verify form fields are present
        $this->assertStringContainsString('Full Name', $output);
        $this->assertStringContainsString('Email Address', $output);
        $this->assertStringContainsString('Phone Number', $output);
        $this->assertStringContainsString('Your Message', $output);
        
        // Verify form elements exist
        $this->assertStringContainsString('<form', $output);
        $this->assertStringContainsString('type="text"', $output);
        $this->assertStringContainsString('type="email"', $output);
        $this->assertStringContainsString('type="tel"', $output);
        $this->assertStringContainsString('<textarea', $output);
        $this->assertStringContainsString('type="submit"', $output);
        
        // Verify contact information is displayed
        $this->assertStringContainsString('Contact Information', $output);
        $this->assertStringContainsString('info@cloudcomputing.edu', $output);
        
        // Verify demo form note is present
        $this->assertStringContainsString('demonstration form', $output);
    }
}
