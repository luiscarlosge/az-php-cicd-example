<?php
/**
 * Curriculum Page
 * Displays course modules and topics
 */

// Include configuration
require_once __DIR__ . '/../includes/config.php';

// Include header
require_once __DIR__ . '/../includes/header.php';

// Include navigation
require_once __DIR__ . '/../includes/navigation.php';
?>

<main>
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1><?php echo t('curriculum.title'); ?></h1>
            <p><?php echo t('curriculum.description'); ?></p>
        </div>
    </section>

    <!-- Curriculum Modules Section -->
    <section class="curriculum-modules">
        <div class="container">
            <h2><?php echo t('curriculum.modules'); ?></h2>
            <p class="curriculum-intro"><?php echo t('curriculum.description'); ?></p>
            
            <div class="modules-list">
                <!-- Module 1: Cloud Fundamentals -->
                <article class="module-card">
                    <div class="module-header">
                        <h3><?php echo t('curriculum.module'); ?> 1: <?php echo t('curriculum.cloud_fundamentals'); ?></h3>
                    </div>
                    <div class="module-content">
                        <h4><?php echo t('curriculum.topics'); ?>:</h4>
                        <ul class="topics-list">
                            <?php foreach (t('curriculum.cloud_fundamentals_topics') as $topic): ?>
                                <li><?php echo htmlspecialchars($topic); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </article>

                <!-- Module 2: Cloud Architecture -->
                <article class="module-card">
                    <div class="module-header">
                        <h3><?php echo t('curriculum.module'); ?> 2: <?php echo t('curriculum.cloud_architecture'); ?></h3>
                    </div>
                    <div class="module-content">
                        <h4><?php echo t('curriculum.topics'); ?>:</h4>
                        <ul class="topics-list">
                            <?php foreach (t('curriculum.cloud_architecture_topics') as $topic): ?>
                                <li><?php echo htmlspecialchars($topic); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </article>

                <!-- Module 3: DevOps and CI/CD -->
                <article class="module-card">
                    <div class="module-header">
                        <h3><?php echo t('curriculum.module'); ?> 3: <?php echo t('curriculum.devops_cicd'); ?></h3>
                    </div>
                    <div class="module-content">
                        <h4><?php echo t('curriculum.topics'); ?>:</h4>
                        <ul class="topics-list">
                            <?php foreach (t('curriculum.devops_cicd_topics') as $topic): ?>
                                <li><?php echo htmlspecialchars($topic); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </article>

                <!-- Module 4: Cloud Platforms -->
                <article class="module-card">
                    <div class="module-header">
                        <h3><?php echo t('curriculum.module'); ?> 4: <?php echo t('curriculum.cloud_platforms'); ?></h3>
                    </div>
                    <div class="module-content">
                        <h4><?php echo t('curriculum.topics'); ?>:</h4>
                        <ul class="topics-list">
                            <?php foreach (t('curriculum.cloud_platforms_topics') as $topic): ?>
                                <li><?php echo htmlspecialchars($topic); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </article>

                <!-- Module 5: Cloud Security -->
                <article class="module-card">
                    <div class="module-header">
                        <h3><?php echo t('curriculum.module'); ?> 5: <?php echo t('curriculum.cloud_security'); ?></h3>
                    </div>
                    <div class="module-content">
                        <h4><?php echo t('curriculum.topics'); ?>:</h4>
                        <ul class="topics-list">
                            <?php foreach (t('curriculum.cloud_security_topics') as $topic): ?>
                                <li><?php echo htmlspecialchars($topic); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </article>

                <!-- Module 6: Capstone Project -->
                <article class="module-card">
                    <div class="module-header">
                        <h3><?php echo t('curriculum.module'); ?> 6: <?php echo t('curriculum.capstone_project'); ?></h3>
                    </div>
                    <div class="module-content">
                        <h4><?php echo t('curriculum.topics'); ?>:</h4>
                        <ul class="topics-list">
                            <?php foreach (t('curriculum.capstone_project_topics') as $topic): ?>
                                <li><?php echo htmlspecialchars($topic); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </article>

                <!-- Module 7: Generative AI in the Cloud -->
                <article class="module-card">
                    <div class="module-header">
                        <h3><?php echo t('curriculum.module'); ?> 7: <?php echo t('curriculum.genai_cloud'); ?></h3>
                    </div>
                    <div class="module-content">
                        <h4><?php echo t('curriculum.topics'); ?>:</h4>
                        <ul class="topics-list">
                            <?php foreach (t('curriculum.genai_cloud_topics') as $topic): ?>
                                <li><?php echo htmlspecialchars($topic); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Program Summary Section -->
    <section class="program-summary">
        <div class="container">
            <h2>Program Summary</h2>
            <div class="summary-grid">
                <div class="summary-card">
                    <h3>Total Modules</h3>
                    <p class="summary-value">7</p>
                </div>
                <div class="summary-card">
                    <h3><?php echo t('course.duration_label'); ?></h3>
                    <p class="summary-value"><?php echo t('course.duration'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-secondary">
        <div class="container">
            <h2>Interested in Learning More?</h2>
            <p>Explore our faculty profiles or get in touch with us.</p>
            <div class="cta-buttons">
                <a href="faculty.php" class="btn btn-primary">Meet Our Faculty</a>
                <a href="contact.php" class="btn btn-secondary">Contact Us</a>
            </div>
        </div>
    </section>
</main>

<?php
// Include footer
require_once __DIR__ . '/../includes/footer.php';
?>
