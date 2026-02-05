<?php
/**
 * Navigation Component
 * Contains responsive navigation menu with active page highlighting
 */

// Get current page filename
$current_page = basename($_SERVER['PHP_SELF']);

// Define navigation menu items
$nav_items = [
    'index.php' => 'Home',
    'curriculum.php' => 'Curriculum',
    'faculty.php' => 'Faculty',
    'admissions.php' => 'Admissions',
    'contact.php' => 'Contact'
];
?>
<nav class="main-navigation">
    <div class="nav-container">
        <!-- Mobile menu toggle button -->
        <button class="mobile-menu-toggle" aria-label="Toggle navigation menu" aria-expanded="false">
            <span class="hamburger-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </button>
        
        <!-- Navigation menu -->
        <ul class="nav-menu">
            <?php foreach ($nav_items as $page => $label): ?>
                <li class="nav-item">
                    <a href="<?php echo $page; ?>" 
                       class="nav-link<?php echo ($current_page === $page) ? ' active' : ''; ?>"
                       <?php echo ($current_page === $page) ? 'aria-current="page"' : ''; ?>>
                        <?php echo $label; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>
