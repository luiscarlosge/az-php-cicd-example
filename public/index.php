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
            <h1><?php echo t('site.welcome'); ?> <?php echo t('site.name'); ?></h1>
            <p class="hero-subtitle"><?php echo t('site.hero_subtitle'); ?></p>
        </div>
    </section>

    <!-- Executive Summary Section -->
    <section class="executive-summary">
        <div class="container">
            <h2><?php echo t('executive.title'); ?></h2>
            <div class="summary-content">
                <p class="summary-intro"><?php echo t('executive.intro'); ?></p>
                
                <div class="market-stats">
                    <div class="stat-card">
                        <span class="stat-number"><?php echo t('executive.stat1_number'); ?></span>
                        <span class="stat-label"><?php echo t('executive.stat1_label'); ?></span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number"><?php echo t('executive.stat2_number'); ?></span>
                        <span class="stat-label"><?php echo t('executive.stat2_label'); ?></span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number"><?php echo t('executive.stat3_number'); ?></span>
                        <span class="stat-label"><?php echo t('executive.stat3_label'); ?></span>
                    </div>
                </div>

                <div class="reasons-grid">
                    <article class="reason-card">
                        <h3>💼 <?php echo t('executive.reason1_title'); ?></h3>
                        <p><?php echo t('executive.reason1_desc'); ?></p>
                    </article>
                    <article class="reason-card">
                        <h3>📈 <?php echo t('executive.reason2_title'); ?></h3>
                        <p><?php echo t('executive.reason2_desc'); ?></p>
                    </article>
                    <article class="reason-card">
                        <h3>🤖 <?php echo t('executive.reason3_title'); ?></h3>
                        <p><?php echo t('executive.reason3_desc'); ?></p>
                    </article>
                    <article class="reason-card">
                        <h3>🌎 <?php echo t('executive.reason4_title'); ?></h3>
                        <p><?php echo t('executive.reason4_desc'); ?></p>
                    </article>
                </div>

                <p class="summary-conclusion"><?php echo t('executive.conclusion'); ?></p>
            </div>
        </div>
    </section>

    <!-- Course Overview Section -->
    <section class="course-overview">
        <div class="container">
            <h2><?php echo t('course.overview'); ?></h2>
            <article class="course-details">
                <p><?php echo t('course.overview_text'); ?></p>
                
                <div class="course-info-grid">
                    <div class="info-card">
                        <h3><?php echo t('course.duration_label'); ?></h3>
                        <p><?php echo t('course.duration'); ?></p>
                    </div>
                    <div class="info-card">
                        <h3><?php echo t('course.mode_label'); ?></h3>
                        <p><?php echo t('course.mode'); ?></p>
                    </div>
                </div>
            </article>
        </div>
    </section>

    <!-- Key Highlights Section -->
    <section class="highlights">
        <div class="container">
            <h2><?php echo t('highlights.title'); ?></h2>
            <div class="highlights-grid">
                <article class="highlight-card">
                    <h3><?php echo t('highlights.industry_curriculum'); ?></h3>
                    <p><?php echo t('highlights.industry_curriculum_desc'); ?></p>
                </article>
                <article class="highlight-card">
                    <h3><?php echo t('highlights.hands_on_projects'); ?></h3>
                    <p><?php echo t('highlights.hands_on_projects_desc'); ?></p>
                </article>
                <article class="highlight-card">
                    <h3><?php echo t('highlights.expert_faculty'); ?></h3>
                    <p><?php echo t('highlights.expert_faculty_desc'); ?></p>
                </article>
                <article class="highlight-card">
                    <h3><?php echo t('highlights.career_support'); ?></h3>
                    <p><?php echo t('highlights.career_support_desc'); ?></p>
                </article>
                <article class="highlight-card">
                    <h3><?php echo t('highlights.flexible_learning'); ?></h3>
                    <p><?php echo t('highlights.flexible_learning_desc'); ?></p>
                </article>
                <article class="highlight-card">
                    <h3><?php echo t('highlights.industry_certifications'); ?></h3>
                    <p><?php echo t('highlights.industry_certifications_desc'); ?></p>
                </article>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta">
        <div class="container">
            <h2><?php echo t('cta.title'); ?></h2>
            <p><?php echo t('cta.description'); ?></p>
            <div class="cta-buttons">
                <a href="/public/curriculum.php" class="btn btn-primary"><?php echo t('cta.view_curriculum'); ?></a>
            </div>
        </div>
    </section>
</main>

<?php
// Include footer
require_once __DIR__ . '/../includes/footer.php';
?>
