<?php
/**
 * Footer Component
 * Contains copyright information, contact links, and social media placeholders
 */
?>
    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <p>
                        <a href="mailto:<?php echo CONTACT_EMAIL; ?>" class="footer-link">
                            <?php echo CONTACT_EMAIL; ?>
                        </a>
                    </p>
                </div>
                
                <div class="footer-section">
                    <h3><?php echo t('social.follow_us'); ?></h3>
                    <div class="social-links">
                        <a href="<?php echo t('social.linkedin_url'); ?>" class="social-link" aria-label="<?php echo t('social.linkedin'); ?>" target="_blank" rel="noopener noreferrer"><?php echo t('social.linkedin'); ?></a>
                        <a href="<?php echo t('social.github_url'); ?>" class="social-link" aria-label="<?php echo t('social.github'); ?>" target="_blank" rel="noopener noreferrer"><?php echo t('social.github'); ?></a>
                        <a href="<?php echo t('social.instagram_url'); ?>" class="social-link" aria-label="<?php echo t('social.instagram'); ?>" target="_blank" rel="noopener noreferrer"><?php echo t('social.instagram'); ?></a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p class="copyright">
                    &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
