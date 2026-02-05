<?php
/**
 * Contact Page
 * Displays contact form and contact information
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
            <h1>Contact Us</h1>
            <p>Get in touch with us for any inquiries about the program</p>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-form-section">
        <div class="container">
            <div class="contact-content">
                <article class="form-container">
                    <h2>Send Us a Message</h2>
                    <p class="form-note"><strong>Note:</strong> This is a demonstration form. For actual inquiries, please email us directly at <a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a></p>
                    
                    <form class="contact-form" method="post" action="#">
                        <div class="form-group">
                            <label for="full_name">Full Name <span class="required">*</span></label>
                            <input 
                                type="text" 
                                id="full_name" 
                                name="full_name" 
                                required 
                                placeholder="Enter your full name"
                                aria-required="true">
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                required 
                                placeholder="your.email@example.com"
                                aria-required="true">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                placeholder="+1 (555) 123-4567"
                                pattern="[\+]?[0-9\s\-\(\)]+"
                                aria-required="false">
                            <small class="form-help">Optional - Include country code if international</small>
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject <span class="required">*</span></label>
                            <select id="subject" name="subject" required aria-required="true">
                                <option value="">Select a subject</option>
                                <option value="admissions">Admissions Inquiry</option>
                                <option value="curriculum">Curriculum Questions</option>
                                <option value="financial">Financial Aid</option>
                                <option value="technical">Technical Support</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message">Your Message <span class="required">*</span></label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="6" 
                                required 
                                placeholder="Please provide details about your inquiry..."
                                aria-required="true"></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </div>
                    </form>
                </article>

                <aside class="contact-info-sidebar">
                    <h2>Contact Information</h2>
                    
                    <div class="info-block">
                        <h3>Email</h3>
                        <p><a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a></p>
                    </div>

                    <div class="info-block">
                        <h3>Phone</h3>
                        <p>+1 (555) 123-4567</p>
                        <p class="info-detail">Monday - Friday: 9:00 AM - 5:00 PM EST</p>
                    </div>

                    <div class="info-block">
                        <h3>Office Address</h3>
                        <p>Cloud Computing Department<br>
                        University Campus<br>
                        123 Education Street<br>
                        Tech City, TC 12345</p>
                    </div>

                    <div class="info-block">
                        <h3>Response Time</h3>
                        <p>We typically respond to inquiries within 1-2 business days.</p>
                    </div>

                    <div class="info-block">
                        <h3>Follow Us</h3>
                        <div class="social-links">
                            <a href="#" aria-label="LinkedIn">LinkedIn</a>
                            <a href="#" aria-label="Twitter">Twitter</a>
                            <a href="#" aria-label="Facebook">Facebook</a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2>Frequently Asked Questions</h2>
            <div class="faq-grid">
                <article class="faq-item">
                    <h3>What are the admission requirements?</h3>
                    <p>Visit our <a href="admissions.php">Admissions page</a> for detailed information about requirements and the application process.</p>
                </article>
                <article class="faq-item">
                    <h3>When does the program start?</h3>
                    <p>The program starts in <?php echo COURSE_START_DATE; ?>. Applications are reviewed on a rolling basis.</p>
                </article>
                <article class="faq-item">
                    <h3>Is financial aid available?</h3>
                    <p>Yes, we offer various financial aid options. Contact our admissions office for more information.</p>
                </article>
                <article class="faq-item">
                    <h3>Can I study part-time?</h3>
                    <p>Yes, we offer both full-time and part-time study options to accommodate working professionals.</p>
                </article>
            </div>
        </div>
    </section>
</main>

<?php
// Include footer
require_once __DIR__ . '/../includes/footer.php';
?>
