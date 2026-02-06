<?php
/**
 * Language Switch Endpoint
 * Handles POST requests to change the user's language preference
 * 
 * Requirements: 2.5
 */

// Include configuration and language management
require_once __DIR__ . '/../includes/config.php';

/**
 * Process language switch request
 * 
 * @param string $method HTTP request method
 * @param array $postData POST data
 * @return array Response array with success status and data/error
 */
function processLanguageSwitch(string $method, array $postData): array
{
    // Only accept POST requests
    if ($method !== 'POST') {
        return [
            'status_code' => 405,
            'success' => false,
            'error' => 'Method not allowed. Only POST requests are accepted.'
        ];
    }

    // Get language parameter from POST data
    $lang = $postData['lang'] ?? '';

    // Validate language code
    if (empty($lang)) {
        return [
            'status_code' => 400,
            'success' => false,
            'error' => 'Language parameter is required.'
        ];
    }

    // Validate that language is either 'es' or 'en'
    if (!in_array($lang, ['es', 'en'])) {
        return [
            'status_code' => 400,
            'success' => false,
            'error' => 'Invalid language code. Must be "es" or "en".'
        ];
    }

    // Attempt to set the language
    $success = setLanguage($lang);

    if ($success) {
        return [
            'status_code' => 200,
            'success' => true,
            'language' => $lang,
            'message' => 'Language updated successfully.'
        ];
    } else {
        return [
            'status_code' => 500,
            'success' => false,
            'error' => 'Failed to update language preference.'
        ];
    }
}

// Only execute if not in test mode
if (!defined('PHPUNIT_RUNNING')) {
    // Set JSON response header
    header('Content-Type: application/json');
    
    // Process the request
    $response = processLanguageSwitch($_SERVER['REQUEST_METHOD'], $_POST);
    
    // Set HTTP response code
    http_response_code($response['status_code']);
    
    // Remove status_code from response before outputting
    unset($response['status_code']);
    
    // Output JSON response
    echo json_encode($response);
}
