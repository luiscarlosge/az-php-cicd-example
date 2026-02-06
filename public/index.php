<?php
/**
 * Home Page
 * Displays course overview, key highlights, and call-to-action for admissions
 */

// Include configuration
require_once __DIR__ . '/../includes/config.php';

// Include header
require_once __DIR__ . '/../includes/header.php';

// Include navigation
require_once __DIR__ . '/../includes/navigation.php';
?>

<main>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Welcome to <?php echo SITE_NAME; ?></h1>
            <p class="hero-subtitle">Transform Your Career with Advanced Cloud Computing Skills</p>
        </div>
    </section>

    <!-- Course Overview Section -->
    <section class="course-overview">
        <div class="container">
            <h2>Course Overview</h2>
            <article class="course-details">
                <p>Our comprehensive Post Graduate Course in Cloud Computing is designed to equip you with the knowledge and skills needed to excel in the rapidly evolving cloud technology landscape. This program combines theoretical foundations with hands-on practical experience across major cloud platforms.</p>
                
                <div class="course-info-grid">
                    <div class="info-card">
                        <h3>Duration</h3>
                        <p><?php echo COURSE_DURATION; ?></p>
                    </div>
                    <div class="info-card">
                        <h3>Start Date</h3>
                        <p><?php echo COURSE_START_DATE; ?></p>
                    </div>
                    <div class="info-card">
                        <h3>Mode</h3>
                        <p><?php echo COURSE_MODE; ?></p>
                    </div>
                </div>
            </article>
        </div>
    </section>

    <!-- Key Highlights Section -->
    <section class="highlights">
        <div class="container">
            <h2>Key Highlights</h2>
            <div class="highlights-grid">
                <article class="highlight-card">
                    <h3>Industry-Relevant Curriculum</h3>
                    <p>Learn from a curriculum designed in collaboration with industry experts, covering AWS, Azure, and Google Cloud Platform.</p>
                </article>
                <article class="highlight-card">
                    <h3>Hands-On Projects</h3>
                    <p>Apply your knowledge through real-world projects and a comprehensive capstone project.</p>
                </article>
                <article class="highlight-card">
                    <h3>Expert Faculty</h3>
                    <p>Learn from experienced professors and industry practitioners with extensive cloud computing expertise.</p>
                </article>
                <article class="highlight-card">
                    <h3>Career Support</h3>
                    <p>Benefit from career guidance, industry connections, and placement assistance throughout your journey.</p>
                </article>
                <article class="highlight-card">
                    <h3>Flexible Learning</h3>
                    <p>Choose between full-time and part-time options to fit your schedule and career goals.</p>
                </article>
                <article class="highlight-card">
                    <h3>Industry Certifications</h3>
                    <p>Prepare for major cloud certifications including AWS, Azure, and Google Cloud credentials.</p>
                </article>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta">
        <div class="container">
            <h2>Ready to Start Your Cloud Computing Journey?</h2>
            <p>Join our next cohort and become a cloud computing expert. Applications are now open!</p>
            <div class="cta-buttons">
                <a href="/public/admissions.php" class="btn btn-primary">Apply Now</a>
                <a href="/public/curriculum.php" class="btn btn-secondary">View Curriculum</a>
            </div>
        </div>
    </section>
</main>

<?php
// Include footer
require_once __DIR__ . '/../includes/footer.php';
?>
