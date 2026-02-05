<?php
/**
 * Curriculum Page
 * Displays course modules, topics, and credits
 */

// Include configuration
require_once __DIR__ . '/../includes/config.php';

// Access global variables
global $curriculum;

// Include header
require_once __DIR__ . '/../includes/header.php';

// Include navigation
require_once __DIR__ . '/../includes/navigation.php';
?>

<main>
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Curriculum</h1>
            <p>Comprehensive course structure covering all aspects of cloud computing</p>
        </div>
    </section>

    <!-- Curriculum Modules Section -->
    <section class="curriculum-modules">
        <div class="container">
            <h2>Course Modules</h2>
            <p class="curriculum-intro">Our program consists of <?php echo count($curriculum); ?> comprehensive modules totaling <?php echo array_sum(array_column($curriculum, 'credits')); ?> credits, designed to provide you with a complete understanding of cloud computing technologies and practices.</p>
            
            <div class="modules-list">
                <?php foreach ($curriculum as $index => $module): ?>
                    <article class="module-card">
                        <div class="module-header">
                            <h3>Module <?php echo $index + 1; ?>: <?php echo htmlspecialchars($module['module']); ?></h3>
                            <span class="module-credits"><?php echo $module['credits']; ?> Credits</span>
                        </div>
                        <div class="module-content">
                            <h4>Topics Covered:</h4>
                            <ul class="topics-list">
                                <?php foreach ($module['topics'] as $topic): ?>
                                    <li><?php echo htmlspecialchars($topic); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </article>
                <?php endforeach; ?>
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
                    <p class="summary-value"><?php echo count($curriculum); ?></p>
                </div>
                <div class="summary-card">
                    <h3>Total Credits</h3>
                    <p class="summary-value"><?php echo array_sum(array_column($curriculum, 'credits')); ?></p>
                </div>
                <div class="summary-card">
                    <h3>Duration</h3>
                    <p class="summary-value"><?php echo COURSE_DURATION; ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-secondary">
        <div class="container">
            <h2>Interested in Learning More?</h2>
            <p>Explore our faculty profiles or get in touch with our admissions team.</p>
            <div class="cta-buttons">
                <a href="faculty.php" class="btn btn-primary">Meet Our Faculty</a>
                <a href="admissions.php" class="btn btn-secondary">Admissions Info</a>
            </div>
        </div>
    </section>
</main>

<?php
// Include footer
require_once __DIR__ . '/../includes/footer.php';
?>
