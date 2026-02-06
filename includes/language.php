<?php
/**
 * Language Management System
 * Handles language selection, persistence, and translation retrieval
 * 
 * Requirements: 2.3, 2.6
 */

// Global variable to store loaded translations
$translations = [];

/**
 * Initialize the language system
 * Sets up session and determines current language
 * 
 * @return void
 */
function initLanguage(): void {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Set default language to Spanish if not set
    if (!isset($_SESSION['language'])) {
        $_SESSION['language'] = 'es';
    }
    
    // Load translations for current language
    loadTranslations(getCurrentLanguage());
}

/**
 * Get the current language code
 * 
 * @return string Language code ('es' or 'en')
 */
function getCurrentLanguage(): string {
    // Ensure session is started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Return current language from session, default to Spanish
    return $_SESSION['language'] ?? 'es';
}

/**
 * Set the language preference
 * Validates and sets language in session
 * 
 * @param string $lang Language code ('es' or 'en')
 * @return bool True if language was set successfully, false otherwise
 */
function setLanguage(string $lang): bool {
    // Validate language code
    if (!in_array($lang, ['es', 'en'])) {
        error_log("Invalid language code attempted: {$lang}");
        return false;
    }
    
    // Ensure session is started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Set language in session
    $_SESSION['language'] = $lang;
    
    // Reload translations for new language
    loadTranslations($lang);
    
    return true;
}

/**
 * Get translated string for a given key
 * Supports nested keys using dot notation (e.g., 'nav.home')
 * 
 * @param string $key Translation key
 * @return string|array Translated string or array, or the key itself if not found
 */
function t(string $key) {
    global $translations;
    
    // If translations not loaded, load them
    if (empty($translations)) {
        loadTranslations(getCurrentLanguage());
    }
    
    // Split key by dots for nested access
    $keys = explode('.', $key);
    $value = $translations;
    
    // Navigate through nested array
    foreach ($keys as $k) {
        if (is_array($value) && isset($value[$k])) {
            $value = $value[$k];
        } else {
            // Key not found - log warning and return key as fallback
            error_log("Translation key not found: {$key} for language: " . getCurrentLanguage());
            return $key;
        }
    }
    
    return $value;
}

/**
 * Load translations from language file
 * 
 * @param string $lang Language code
 * @return void
 */
function loadTranslations(string $lang): void {
    global $translations;
    
    // Construct path to language file
    $langFile = __DIR__ . '/../lang/' . $lang . '.php';
    
    // Check if file exists
    if (!file_exists($langFile)) {
        error_log("Language file not found: {$langFile}");
        // Load default language as fallback
        if ($lang !== 'es') {
            $langFile = __DIR__ . '/../lang/es.php';
            if (!file_exists($langFile)) {
                error_log("Default language file not found: {$langFile}");
                $translations = [];
                return;
            }
        } else {
            $translations = [];
            return;
        }
    }
    
    // Load translations from file
    try {
        $translations = require $langFile;
        
        // Validate that translations is an array
        if (!is_array($translations)) {
            error_log("Language file did not return an array: {$langFile}");
            $translations = [];
        }
    } catch (Exception $e) {
        error_log("Error loading language file {$langFile}: " . $e->getMessage());
        $translations = [];
    }
}

/**
 * Get all translations for current language
 * 
 * @return array Complete translation array
 */
function getTranslations(): array {
    global $translations;
    
    // If translations not loaded, load them
    if (empty($translations)) {
        loadTranslations(getCurrentLanguage());
    }
    
    return $translations;
}
