<?php
/**
 * Faculty Page
 * Displays single faculty member profile with credentials and specialization
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
            <h1><?php echo t('faculty.title'); ?></h1>
        </div>
    </section>

    <!-- Faculty Profile Section -->
    <section class="faculty-profile">
        <div class="container">
            <article class="faculty-member">
                <div class="faculty-photo">
                    <img src="assets/images/faculty/placeholder.svg" 
                         alt="<?php echo t('faculty.image_alt'); ?>">
                </div>
                <div class="faculty-info">
                    <h2><?php echo t('faculty.name'); ?></h2>
                    <p class="faculty-position"><?php echo t('faculty.position'); ?></p>
                    
                    <div class="faculty-credentials">
                        <h3><?php echo t('faculty.credentials_title'); ?></h3>
                        <ul class="credentials-list">
                            <?php foreach (t('faculty.credentials') as $credential): ?>
                                <li><?php echo htmlspecialchars($credential); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <div class="faculty-specialization">
                        <h3><?php echo t('faculty.specialization'); ?></h3>
                        <p><?php echo t('faculty.specialization_text'); ?></p>
                    </div>
                </div>
            </article>
        </div>
    </section>
</main>

<?php
// Include footer
require_once __DIR__ . '/../includes/footer.php';
?>
