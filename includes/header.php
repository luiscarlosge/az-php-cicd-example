<?php
/**
 * Header Component
 * Contains HTML5 doctype, meta tags, CSS links, and site header
 */
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo SITE_NAME; ?> - Comprehensive program covering cloud architecture, DevOps, and modern cloud platforms">
    <meta name="keywords" content="cloud computing, post graduate, AWS, Azure, GCP, DevOps, CI/CD">
    <meta name="author" content="<?php echo SITE_NAME; ?>">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link rel="stylesheet" href="/public/assets/css/style.css">
    <script src="/public/assets/js/main.js" defer></script>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-section">
                <img src="/public/assets/images/logo.svg" alt="<?php echo SITE_NAME; ?> Logo" class="site-logo" onerror="this.style.display='none'">
                <h1 class="site-title"><?php echo SITE_NAME; ?></h1>
            </div>
        </div>
    </header>
