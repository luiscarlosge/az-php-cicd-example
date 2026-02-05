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
                    <h3>Follow Us</h3>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Facebook">Facebook</a>
                        <a href="#" class="social-link" aria-label="Twitter">Twitter</a>
                        <a href="#" class="social-link" aria-label="LinkedIn">LinkedIn</a>
                        <a href="#" class="social-link" aria-label="YouTube">YouTube</a>
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
