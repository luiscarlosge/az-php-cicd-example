<?php
/**
 * Root Index File
 * Redirects to the public directory
 * This file is needed for Azure App Service Linux which serves from /home/site/wwwroot
 */

// Check if we're in the root directory
if (file_exists(__DIR__ . '/public/index.php')) {
    // Redirect to public directory
    header('Location: /public/index.php');
    exit;
} else {
    // If public directory doesn't exist, show error
    http_response_code(500);
    echo '<!DOCTYPE html>
<html>
<head>
    <title>Configuration Error</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; padding: 20px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="error">
        <h1>Configuration Error</h1>
        <p>The application is not properly configured. The public directory is missing.</p>
        <p>Please ensure the application is deployed correctly.</p>
    </div>
</body>
</html>';
}
