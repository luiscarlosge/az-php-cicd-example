/**
 * Language Switcher JavaScript
 * Handles language switching functionality via AJAX
 * 
 * Requirements: 2.5
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get all language buttons
    const languageButtons = document.querySelectorAll('.lang-btn');
    
    if (languageButtons.length === 0) {
        console.warn('Language switcher buttons not found');
        return;
    }
    
    // Add click handler to each language button
    languageButtons.forEach(button => {
        button.addEventListener('click', async function(event) {
            event.preventDefault();
            
            // Get the language code from data attribute
            const lang = this.getAttribute('data-lang');
            
            if (!lang) {
                console.error('Language code not found on button');
                return;
            }
            
            // Validate language code
            if (!['es', 'en'].includes(lang)) {
                console.error('Invalid language code:', lang);
                return;
            }
            
            // Don't switch if already active
            if (this.classList.contains('active')) {
                return;
            }
            
            // Disable all buttons during the request
            languageButtons.forEach(btn => btn.disabled = true);
            
            try {
                // Make AJAX call to language-switch.php
                const response = await fetch('/public/language-switch.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `lang=${encodeURIComponent(lang)}`
                });
                
                // Parse JSON response
                const data = await response.json();
                
                // Check if the request was successful
                if (response.ok && data.success) {
                    // Reload the page to show content in new language
                    window.location.reload();
                } else {
                    // Handle error response
                    const errorMessage = data.error || 'Failed to switch language';
                    console.error('Language switch failed:', errorMessage);
                    alert('Error: ' + errorMessage);
                    
                    // Re-enable buttons on error
                    languageButtons.forEach(btn => btn.disabled = false);
                }
            } catch (error) {
                // Handle network or other errors
                console.error('Error switching language:', error);
                alert('An error occurred while switching language. Please try again.');
                
                // Re-enable buttons on error
                languageButtons.forEach(btn => btn.disabled = false);
            }
        });
    });
});
