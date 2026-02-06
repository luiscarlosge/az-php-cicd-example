<?php
/**
 * Faculty Page
 * Displays faculty profiles with credentials and specializations
 */

// Include configuration
require_once __DIR__ . '/../includes/config.php';

// Access global variables
global $faculty;

// Include header
require_once __DIR__ . '/../includes/header.php';

// Include navigation
require_once __DIR__ . '/../includes/navigation.php';
?>

<main>
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Our Faculty</h1>
            <p>Learn from experienced professionals and academic experts in cloud computing</p>
        </div>
    </section>

    <!-- Faculty Profiles Section -->
    <section class="faculty-profiles">
        <div class="container">
            <h2>Meet Our Expert Faculty</h2>
            <p class="faculty-intro">Our faculty members bring a wealth of academic knowledge and industry experience to provide you with the best learning experience in cloud computing.</p>
            
            <div class="faculty-grid">
                <?php foreach ($faculty as $member): ?>
                    <article class="faculty-card">
                        <div class="faculty-image">
                            <img src="<?php echo htmlspecialchars($member['image']); ?>" 
                                 alt="Photo of <?php echo htmlspecialchars($member['name']); ?>" 
                                 onerror="this.src='/public/assets/images/placeholder-faculty.svg'">
                        </div>
                        <div class="faculty-info">
                            <h3><?php echo htmlspecialchars($member['name']); ?></h3>
                            <p class="faculty-title"><?php echo htmlspecialchars($member['title']); ?></p>
                            <div class="faculty-details">
                                <h4>Credentials</h4>
                                <p><?php echo htmlspecialchars($member['credentials']); ?></p>
                                
                                <h4>Specialization</h4>
                                <p><?php echo htmlspecialchars($member['specialization']); ?></p>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Faculty Expertise Section -->
    <section class="faculty-expertise">
        <div class="container">
            <h2>Collective Expertise</h2>
            <p>Our faculty team collectively brings expertise across all major cloud platforms and technologies:</p>
            <div class="expertise-grid">
                <div class="expertise-card">
                    <h3>Cloud Platforms</h3>
                    <p>AWS, Azure, Google Cloud Platform</p>
                </div>
                <div class="expertise-card">
                    <h3>Architecture & Design</h3>
                    <p>Distributed Systems, Microservices, Scalability</p>
                </div>
                <div class="expertise-card">
                    <h3>DevOps & Automation</h3>
                    <p>CI/CD, Infrastructure as Code, Configuration Management</p>
                </div>
                <div class="expertise-card">
                    <h3>Security & Compliance</h3>
                    <p>Cloud Security, IAM, Governance, Best Practices</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-secondary">
        <div class="container">
            <h2>Ready to Learn from the Best?</h2>
            <p>Join our program and benefit from expert guidance throughout your cloud computing journey.</p>
            <div class="cta-buttons">
                <a href="admissions.php" class="btn btn-primary">Apply Now</a>
                <a href="contact.php" class="btn btn-secondary">Contact Us</a>
            </div>
        </div>
    </section>
</main>

<?php
// Include footer
require_once __DIR__ . '/../includes/footer.php';
?>
