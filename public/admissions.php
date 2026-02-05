<?php
/**
 * Admissions Page
 * Displays enrollment requirements, application process, and important dates
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
            <h1>Admissions</h1>
            <p>Start your journey to becoming a cloud computing expert</p>
        </div>
    </section>

    <!-- Enrollment Requirements Section -->
    <section class="requirements">
        <div class="container">
            <h2>Enrollment Requirements</h2>
            
            <article class="requirements-content">
                <h3>Educational Background</h3>
                <ul>
                    <li>Bachelor's degree in Computer Science, Information Technology, Engineering, or related field</li>
                    <li>Minimum GPA of 3.0 on a 4.0 scale (or equivalent)</li>
                    <li>Transcripts from all previously attended institutions</li>
                </ul>

                <h3>Prerequisites</h3>
                <ul>
                    <li>Basic understanding of computer networks and operating systems</li>
                    <li>Familiarity with at least one programming language (Python, Java, JavaScript, or similar)</li>
                    <li>Understanding of basic database concepts</li>
                    <li>Prior experience with Linux/Unix command line (recommended but not required)</li>
                </ul>

                <h3>Additional Requirements</h3>
                <ul>
                    <li>Statement of purpose (500-1000 words)</li>
                    <li>Two letters of recommendation (academic or professional)</li>
                    <li>Resume or CV highlighting relevant experience</li>
                    <li>English proficiency test scores (for international students)</li>
                </ul>
            </article>
        </div>
    </section>

    <!-- Application Process Section -->
    <section class="application-process">
        <div class="container">
            <h2>Application Process</h2>
            <p class="process-intro">Follow these steps to complete your application:</p>
            
            <div class="process-steps">
                <article class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Submit Online Application</h3>
                        <p>Complete the online application form with your personal and educational information.</p>
                    </div>
                </article>

                <article class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>Upload Required Documents</h3>
                        <p>Submit transcripts, statement of purpose, letters of recommendation, and resume.</p>
                    </div>
                </article>

                <article class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Application Review</h3>
                        <p>Our admissions committee will review your application materials (typically 2-3 weeks).</p>
                    </div>
                </article>

                <article class="step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h3>Interview (if selected)</h3>
                        <p>Selected candidates will be invited for a virtual or in-person interview with faculty.</p>
                    </div>
                </article>

                <article class="step">
                    <div class="step-number">5</div>
                    <div class="step-content">
                        <h3>Admission Decision</h3>
                        <p>Receive your admission decision via email and postal mail.</p>
                    </div>
                </article>

                <article class="step">
                    <div class="step-number">6</div>
                    <div class="step-content">
                        <h3>Enrollment Confirmation</h3>
                        <p>Accept your offer and complete enrollment formalities to secure your seat.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Important Dates Section -->
    <section class="important-dates">
        <div class="container">
            <h2>Important Dates & Deadlines</h2>
            
            <div class="dates-grid">
                <article class="date-card">
                    <h3>Application Opens</h3>
                    <p class="date">January 1, 2024</p>
                </article>
                <article class="date-card">
                    <h3>Early Decision Deadline</h3>
                    <p class="date">March 15, 2024</p>
                </article>
                <article class="date-card">
                    <h3>Regular Decision Deadline</h3>
                    <p class="date">June 30, 2024</p>
                </article>
                <article class="date-card">
                    <h3>Final Admission Decisions</h3>
                    <p class="date">July 31, 2024</p>
                </article>
                <article class="date-card">
                    <h3>Enrollment Confirmation</h3>
                    <p class="date">August 15, 2024</p>
                </article>
                <article class="date-card">
                    <h3>Program Starts</h3>
                    <p class="date"><?php echo COURSE_START_DATE; ?></p>
                </article>
            </div>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="admissions-contact">
        <div class="container">
            <h2>Admissions Inquiries</h2>
            <p>Have questions about the admissions process? We're here to help!</p>
            
            <div class="contact-info">
                <div class="contact-item">
                    <h3>Email</h3>
                    <p><a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a></p>
                </div>
                <div class="contact-item">
                    <h3>Office Hours</h3>
                    <p>Monday - Friday: 9:00 AM - 5:00 PM</p>
                </div>
                <div class="contact-item">
                    <h3>Response Time</h3>
                    <p>We typically respond within 1-2 business days</p>
                </div>
            </div>
            
            <div class="cta-buttons">
                <a href="contact.php" class="btn btn-primary">Contact Admissions</a>
                <a href="curriculum.php" class="btn btn-secondary">View Curriculum</a>
            </div>
        </div>
    </section>
</main>

<?php
// Include footer
require_once __DIR__ . '/../includes/footer.php';
?>
