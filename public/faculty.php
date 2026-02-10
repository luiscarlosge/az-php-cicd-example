<?php
/**
 * Faculty Page
 * Displays faculty members with credentials and specialization
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
            <?php foreach (t('faculty.members') as $member): ?>
            <article class="faculty-member">
                <div class="faculty-photo">
                    <img src="<?php echo htmlspecialchars($member['image']); ?>" 
                         alt="<?php echo htmlspecialchars($member['image_alt']); ?>">
                </div>
                <div class="faculty-info">
                    <h2><?php echo htmlspecialchars($member['name']); ?></h2>
                    <p class="faculty-position"><?php echo htmlspecialchars($member['position']); ?></p>
                    
                    <div class="faculty-credentials">
                        <h3><?php echo t('faculty.credentials_title'); ?></h3>
                        <ul class="credentials-list">
                            <?php foreach ($member['credentials'] as $credential): ?>
                                <li><?php echo htmlspecialchars($credential); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <div class="faculty-specialization">
                        <h3><?php echo t('faculty.specialization'); ?></h3>
                        <p><?php echo htmlspecialchars($member['specialization_text']); ?></p>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php
// Include footer
require_once __DIR__ . '/../includes/footer.php';
?>
