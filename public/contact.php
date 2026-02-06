<?php
/**
 * Contact Page
 * Displays contact information
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
            <h1><?php echo t('contact.title'); ?></h1>
            <p><?php echo t('contact.description'); ?></p>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="contact-info-section">
        <div class="container">
            <div class="contact-content">
                <div class="contact-info">
                    <h2><?php echo t('contact.get_in_touch'); ?></h2>
                    
                    <div class="contact-item">
                        <h3><?php echo t('contact.email_label'); ?></h3>
                        <p>
                            <a href="mailto:<?php echo t('contact.email'); ?>" class="contact-link">
                                <?php echo t('contact.email'); ?>
                            </a>
                        </p>
                    </div>

                    <div class="contact-item">
                        <h3><?php echo t('contact.phone_label'); ?></h3>
                        <p>
                            <a href="tel:<?php echo t('contact.phone'); ?>" class="contact-link">
                                <?php echo t('contact.phone'); ?>
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="social-media-section">
                    <h2><?php echo t('social.title'); ?></h2>
                    <p><?php echo t('social.follow_us'); ?></p>
                    <div class="social-links">
                        <a href="<?php echo t('social.linkedin_url'); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="social-link linkedin"
                           aria-label="<?php echo t('social.linkedin'); ?>">
                            <span class="social-icon">💼</span>
                            <span><?php echo t('social.linkedin'); ?></span>
                        </a>
                        <a href="<?php echo t('social.github_url'); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="social-link github"
                           aria-label="<?php echo t('social.github'); ?>">
                            <span class="social-icon">🐙</span>
                            <span><?php echo t('social.github'); ?></span>
                        </a>
                        <a href="<?php echo t('social.instagram_url'); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="social-link instagram"
                           aria-label="<?php echo t('social.instagram'); ?>">
                            <span class="social-icon">📷</span>
                            <span><?php echo t('social.instagram'); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
// Include footer
require_once __DIR__ . '/../includes/footer.php';
?>
