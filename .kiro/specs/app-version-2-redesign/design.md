# Design Document: App Version 2 Redesign

## Overview

This design document outlines the technical approach for implementing Version 2 of the PHP educational website. The redesign transforms the existing single-language website into a bilingual (Spanish/English) platform with updated visual design, restructured content, and modernized contact information.

The implementation will leverage PHP's session management for language persistence, CSS variables for theming, and a modular architecture that separates content from presentation. The design maintains backward compatibility with the existing file structure while introducing new capabilities for internationalization.

## Architecture

### High-Level Architecture

The website follows a traditional server-side rendered architecture with PHP:

```
┌─────────────────────────────────────────────────────────┐
│                    User Browser                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │   HTML/CSS   │  │  JavaScript  │  │   Images     │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│                    Web Server (Apache/Nginx)             │
│  ┌──────────────────────────────────────────────────┐   │
│  │              PHP Runtime                          │   │
│  │  ┌────────────┐  ┌────────────┐  ┌────────────┐ │   │
│  │  │  Language  │  │  Content   │  │  Template  │ │   │
│  │  │  Manager   │  │  Provider  │  │  Renderer  │ │   │
│  │  └────────────┘  └────────────┘  └────────────┘ │   │
│  └──────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│                  File System                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │  Language    │  │    Assets    │  │   Includes   │  │
│  │  Files       │  │  (CSS/JS)    │  │   (PHP)      │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
└─────────────────────────────────────────────────────────┘
```

### Language Management System

The language system uses a three-tier approach:

1. **Session Layer**: Stores user's language preference in PHP session
2. **Content Layer**: Provides translated strings via language files
3. **Presentation Layer**: Renders content in the selected language

### File Organization

```
public/
├── index.php                 # Home page
├── contact.php              # Contact page
├── curriculum.php           # Curriculum page
├── faculty.php              # Faculty page (updated)
├── language-switch.php      # Language switching endpoint
├── includes/
│   ├── config.php          # Configuration and language initialization
│   ├── header.php          # Header with language switcher
│   ├── footer.php          # Footer
│   ├── navigation.php      # Navigation menu
│   └── language.php        # Language management functions
├── lang/
│   ├── es.php              # Spanish translations
│   └── en.php              # English translations
└── assets/
    ├── css/
    │   ├── main.css        # Main styles with CSS variables
    │   └── colors.css      # Color scheme definitions
    ├── js/
    │   └── main.js         # Client-side interactions
    └── images/
        └── faculty/
            └── placeholder.jpg  # Faculty placeholder image
```

## Components and Interfaces

### 1. Language Manager Component

**Purpose**: Manages language selection, persistence, and content retrieval.

**Functions**:

```php
// Initialize language system
function initLanguage(): void
// Sets up session and determines current language

// Get current language
function getCurrentLanguage(): string
// Returns 'es' or 'en'

// Set language preference
function setLanguage(string $lang): bool
// Validates and sets language, returns success status

// Get translated string
function t(string $key): string
// Returns translated string for current language

// Get all translations
function getTranslations(): array
// Returns complete translation array for current language
```

**Interface**:
- Input: Language code ('es' or 'en'), translation keys
- Output: Translated strings, language state
- Side effects: Modifies PHP session

### 2. Content Provider Component

**Purpose**: Supplies translated content for all pages.

**Structure**:

```php
// Spanish translations (lang/es.php)
return [
    'nav' => [
        'home' => 'Inicio',
        'curriculum' => 'Currículo',
        'faculty' => 'Profesores',
        'contact' => 'Contacto'
    ],
    'course' => [
        'title' => 'Paradigmas Emergentes en Computación en la Nube',
        'description' => 'Clase opcional del programa de Maestría en Tecnologías de la Información',
        'duration' => '12 sesiones',
        'mode' => 'Híbrido'
    ],
    'faculty' => [
        'name' => 'Luis Carlos Galvis Espitia',
        'title' => 'Profesor de Computación en la Nube',
        'credentials' => [
            'Ingeniero de Sistemas de la Escuela Colombiana de Ingeniería',
            'Magíster en Negocios y Tecnologías de la Información de la Universidad de los Andes',
            'Certificación AWS Solution Architect Associate'
        ]
    ],
    'contact' => [
        'email' => 'luis.galvis-e@escuelaing.edu.co',
        'phone' => '+57 3017859109',
        'email_label' => 'Correo electrónico',
        'phone_label' => 'Teléfono'
    ],
    'social' => [
        'linkedin' => 'LinkedIn',
        'github' => 'GitHub',
        'instagram' => 'Instagram'
    ]
];

// English translations (lang/en.php)
return [
    'nav' => [
        'home' => 'Home',
        'curriculum' => 'Curriculum',
        'faculty' => 'Faculty',
        'contact' => 'Contact'
    ],
    'course' => [
        'title' => 'Emerging Paradigms on Cloud Computing',
        'description' => 'Optional class from Master in Information Technologies program',
        'duration' => '12 sessions',
        'mode' => 'Hybrid'
    ],
    // ... similar structure for English
];
```

### 3. Theme Manager Component

**Purpose**: Applies color scheme based on reference website.

**CSS Variables** (extracted from reference site):

```css
:root {
    /* Primary colors */
    --color-primary: #003366;      /* Deep blue */
    --color-primary-light: #0066cc;
    --color-primary-dark: #002244;
    
    /* Secondary colors */
    --color-secondary: #ff6600;    /* Orange accent */
    --color-secondary-light: #ff8833;
    --color-secondary-dark: #cc5200;
    
    /* Neutral colors */
    --color-background: #ffffff;
    --color-surface: #f5f5f5;
    --color-text: #333333;
    --color-text-light: #666666;
    
    /* Semantic colors */
    --color-success: #28a745;
    --color-error: #dc3545;
    --color-warning: #ffc107;
    
    /* Spacing */
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
}
```

### 4. Language Switcher Component

**Purpose**: Provides UI for language selection.

**HTML Structure**:

```html
<div class="language-switcher">
    <button class="lang-btn <?php echo $currentLang === 'es' ? 'active' : ''; ?>" 
            data-lang="es">ES</button>
    <button class="lang-btn <?php echo $currentLang === 'en' ? 'active' : ''; ?>" 
            data-lang="en">EN</button>
</div>
```

**JavaScript Behavior**:

```javascript
// Handle language switch
document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        const lang = e.target.dataset.lang;
        const response = await fetch('language-switch.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `lang=${lang}`
        });
        if (response.ok) {
            window.location.reload();
        }
    });
});
```

### 5. Faculty Display Component

**Purpose**: Renders faculty information with credentials.

**HTML Structure**:

```html
<div class="faculty-member">
    <div class="faculty-photo">
        <img src="assets/images/faculty/placeholder.jpg" 
             alt="<?php echo t('faculty.name'); ?>">
    </div>
    <div class="faculty-info">
        <h2><?php echo t('faculty.name'); ?></h2>
        <p class="faculty-title"><?php echo t('faculty.title'); ?></p>
        <ul class="credentials">
            <?php foreach (t('faculty.credentials') as $credential): ?>
                <li><?php echo $credential; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
```

### 6. Contact Information Component

**Purpose**: Displays contact details with clickable links.

**HTML Structure**:

```html
<div class="contact-info">
    <div class="contact-item">
        <span class="contact-label"><?php echo t('contact.email_label'); ?>:</span>
        <a href="mailto:<?php echo t('contact.email'); ?>" class="contact-link">
            <?php echo t('contact.email'); ?>
        </a>
    </div>
    <div class="contact-item">
        <span class="contact-label"><?php echo t('contact.phone_label'); ?>:</span>
        <a href="tel:<?php echo t('contact.phone'); ?>" class="contact-link">
            <?php echo t('contact.phone'); ?>
        </a>
    </div>
</div>
```

### 7. Social Media Links Component

**Purpose**: Displays social media profile links with icons.

**HTML Structure**:

```html
<div class="social-links">
    <a href="https://www.linkedin.com/in/luiscarlosgalvisespitia/" 
       target="_blank" 
       rel="noopener noreferrer"
       class="social-link linkedin">
        <i class="icon-linkedin"></i>
        <span><?php echo t('social.linkedin'); ?></span>
    </a>
    <a href="https://github.com/luiscarlosge/" 
       target="_blank" 
       rel="noopener noreferrer"
       class="social-link github">
        <i class="icon-github"></i>
        <span><?php echo t('social.github'); ?></span>
    </a>
    <a href="https://www.instagram.com/luchogalvis/" 
       target="_blank" 
       rel="noopener noreferrer"
       class="social-link instagram">
        <i class="icon-instagram"></i>
        <span><?php echo t('social.instagram'); ?></span>
    </a>
</div>
```

## Data Models

### Language Preference Model

```php
class LanguagePreference {
    private string $code;           // 'es' or 'en'
    private string $name;           // 'Español' or 'English'
    private bool $isDefault;        // true for Spanish
    
    public function __construct(string $code) {
        $this->code = $code;
        $this->name = $this->getLanguageName($code);
        $this->isDefault = ($code === 'es');
    }
    
    public function isValid(): bool {
        return in_array($this->code, ['es', 'en']);
    }
    
    private function getLanguageName(string $code): string {
        return match($code) {
            'es' => 'Español',
            'en' => 'English',
            default => ''
        };
    }
}
```

### Translation Model

```php
class Translation {
    private string $key;            // Translation key (e.g., 'nav.home')
    private string $language;       // Language code
    private string $value;          // Translated text
    
    public function __construct(string $key, string $language, string $value) {
        $this->key = $key;
        $this->language = $language;
        $this->value = $value;
    }
    
    public function getValue(): string {
        return $this->value;
    }
}
```

### Faculty Member Model

```php
class FacultyMember {
    private string $name;
    private string $title;
    private array $credentials;     // Array of credential strings
    private string $photoPath;
    private ContactInfo $contact;
    private array $socialLinks;     // Array of SocialLink objects
    
    public function __construct(
        string $name,
        string $title,
        array $credentials,
        string $photoPath,
        ContactInfo $contact,
        array $socialLinks
    ) {
        $this->name = $name;
        $this->title = $title;
        $this->credentials = $credentials;
        $this->photoPath = $photoPath;
        $this->contact = $contact;
        $this->socialLinks = $socialLinks;
    }
}
```

### Contact Information Model

```php
class ContactInfo {
    private string $email;
    private string $phone;
    
    public function __construct(string $email, string $phone) {
        $this->email = $email;
        $this->phone = $phone;
    }
    
    public function getEmailLink(): string {
        return "mailto:{$this->email}";
    }
    
    public function getPhoneLink(): string {
        return "tel:{$this->phone}";
    }
}
```

### Social Media Link Model

```php
class SocialLink {
    private string $platform;       // 'linkedin', 'github', 'instagram'
    private string $url;
    private string $iconClass;
    
    public function __construct(string $platform, string $url) {
        $this->platform = $platform;
        $this->url = $url;
        $this->iconClass = "icon-{$platform}";
    }
    
    public function isValid(): bool {
        return filter_var($this->url, FILTER_VALIDATE_URL) !== false;
    }
}
```

### Course Information Model

```php
class CourseInfo {
    private string $title;
    private string $description;
    private string $duration;
    private string $mode;
    
    public function __construct(
        string $title,
        string $description,
        string $duration,
        string $mode
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->duration = $duration;
        $this->mode = $mode;
    }
}
```


## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Color Contrast Accessibility

*For any* foreground and background color pair used in the website's design, the contrast ratio SHALL meet WCAG AA standards (minimum 4.5:1 for normal text, 3:1 for large text).

**Validates: Requirements 1.4**

### Property 2: Language Switcher Presence

*For any* page in the website, the HTML output SHALL contain a language switcher component with buttons for both Spanish and English.

**Validates: Requirements 2.4**

### Property 3: Language Toggle Behavior

*For any* current language state (Spanish or English), activating the language switcher SHALL change the language to the opposite language.

**Validates: Requirements 2.5**

### Property 4: Language Persistence

*For any* language selection made by a user, navigating to any other page in the website SHALL maintain that same language selection.

**Validates: Requirements 2.6**

### Property 5: Translation Completeness

*For any* translation key used in any template file, both the Spanish (es.php) and English (en.php) language files SHALL contain that key with a non-empty value.

**Validates: Requirements 2.7**

### Property 6: Language-Invariant Structure

*For any* page in the website, the HTML DOM structure (element hierarchy and classes) SHALL be identical when rendered in Spanish versus English, with only text content differing.

**Validates: Requirements 2.8**

### Property 7: Email Link Format

*For any* email address displayed on the website, the corresponding link SHALL use the mailto: protocol with the email address as the href value.

**Validates: Requirements 5.5**

### Property 8: Phone Link Format

*For any* phone number displayed on the website, the corresponding link SHALL use the tel: protocol with the phone number as the href value.

**Validates: Requirements 5.6**

### Property 9: External Link Behavior

*For any* social media link on the website, the anchor element SHALL include target="_blank" and rel="noopener noreferrer" attributes to open in a new tab securely.

**Validates: Requirements 6.4**

### Property 10: Language Switch File Structure Invariance

*For any* language switch operation, the file system structure and file paths SHALL remain unchanged before and after the switch.

**Validates: Requirements 8.6**

## Error Handling

### Language System Errors

**Invalid Language Code**:
- Detection: Language code not in ['es', 'en']
- Response: Fall back to default language (Spanish)
- Logging: Log warning with attempted language code
- User feedback: None (silent fallback)

**Missing Translation Key**:
- Detection: Translation key not found in language file
- Response: Return the key itself as fallback (e.g., "nav.home")
- Logging: Log warning with missing key and language
- User feedback: Display key (developer will notice)

**Session Initialization Failure**:
- Detection: session_start() returns false
- Response: Continue without session (use default language)
- Logging: Log error with session failure details
- User feedback: Language preference won't persist (acceptable degradation)

**Language File Load Failure**:
- Detection: Language file doesn't exist or has syntax errors
- Response: Fall back to hardcoded default translations
- Logging: Log critical error with file path
- User feedback: Display error message to user

### Contact Link Errors

**Invalid Email Format**:
- Detection: Email doesn't match RFC 5322 pattern
- Response: Display email as plain text without mailto: link
- Logging: Log warning with invalid email
- User feedback: Email displayed but not clickable

**Invalid Phone Format**:
- Detection: Phone doesn't match E.164 pattern
- Response: Display phone as plain text without tel: link
- Logging: Log warning with invalid phone
- User feedback: Phone displayed but not clickable

### Social Media Link Errors

**Invalid URL**:
- Detection: URL fails filter_var() with FILTER_VALIDATE_URL
- Response: Don't render the link
- Logging: Log warning with invalid URL
- User feedback: Link not displayed

**Network Timeout** (for icon loading):
- Detection: Icon font/image fails to load
- Response: Display text label without icon
- Logging: Log warning about missing resource
- User feedback: Text-only link (still functional)

### File System Errors

**Missing Asset File**:
- Detection: CSS/JS/image file returns 404
- Response: Continue rendering without that asset
- Logging: Log error with missing file path
- User feedback: Degraded styling but functional content

**Missing Include File**:
- Detection: require/include fails
- Response: PHP fatal error (cannot continue)
- Logging: PHP error log captures failure
- User feedback: Display PHP error page

## Testing Strategy

### Dual Testing Approach

This feature requires both **unit tests** and **property-based tests** for comprehensive coverage:

- **Unit tests**: Verify specific examples, edge cases, and error conditions
- **Property tests**: Verify universal properties across all inputs

Together, these approaches ensure both concrete correctness (unit tests catch specific bugs) and general correctness (property tests verify rules hold universally).

### Property-Based Testing

**Framework**: Use [Pest PHP](https://pestphp.com/) with the [Pest Property Testing Plugin](https://pestphp.com/docs/plugins#property-testing) for PHP property-based testing.

**Configuration**:
- Each property test MUST run minimum 100 iterations
- Each test MUST include a comment tag referencing the design property
- Tag format: `// Feature: app-version-2-redesign, Property {number}: {property_text}`

**Property Test Examples**:

```php
// Feature: app-version-2-redesign, Property 1: Color Contrast Accessibility
test('all color pairs meet WCAG AA contrast requirements', function () {
    $colorPairs = getColorPairsFromCSS();
    
    foreach ($colorPairs as $pair) {
        $ratio = calculateContrastRatio($pair['foreground'], $pair['background']);
        $minRatio = $pair['isLargeText'] ? 3.0 : 4.5;
        
        expect($ratio)->toBeGreaterThanOrEqual($minRatio);
    }
})->repeat(100);

// Feature: app-version-2-redesign, Property 3: Language Toggle Behavior
test('language toggle switches to opposite language', function () {
    $languages = ['es', 'en'];
    
    foreach ($languages as $currentLang) {
        $_SESSION['language'] = $currentLang;
        $newLang = toggleLanguage();
        $expectedLang = $currentLang === 'es' ? 'en' : 'es';
        
        expect($newLang)->toBe($expectedLang);
    }
})->repeat(100);

// Feature: app-version-2-redesign, Property 5: Translation Completeness
test('all translation keys exist in both language files', function () {
    $spanishKeys = array_keys(require 'lang/es.php');
    $englishKeys = array_keys(require 'lang/en.php');
    
    expect($spanishKeys)->toEqual($englishKeys);
    
    foreach ($spanishKeys as $key) {
        expect(t($key, 'es'))->not->toBeEmpty();
        expect(t($key, 'en'))->not->toBeEmpty();
    }
})->repeat(100);
```

### Unit Testing

**Framework**: Use Pest PHP for unit tests as well.

**Focus Areas**:
1. **Specific Content Verification**: Test that specific text appears correctly
2. **Edge Cases**: Empty strings, special characters, very long text
3. **Error Conditions**: Missing files, invalid inputs, session failures
4. **Integration Points**: Language switching, session management, file includes

**Unit Test Examples**:

```php
test('course title displays correctly in English', function () {
    $_SESSION['language'] = 'en';
    $title = t('course.title');
    expect($title)->toBe('Emerging Paradigms on Cloud Computing');
});

test('faculty member displays correct credentials', function () {
    $credentials = t('faculty.credentials');
    expect($credentials)->toHaveCount(3);
    expect($credentials[0])->toContain('Systems Engineering');
    expect($credentials[1])->toContain('Business and Information Technology');
    expect($credentials[2])->toContain('AWS Solution Architect');
});

test('admissions page does not exist', function () {
    $response = file_get_contents('http://localhost/admissions.php');
    expect($response)->toBeFalse();
});

test('language switcher appears on all pages', function () {
    $pages = ['index.php', 'curriculum.php', 'faculty.php', 'contact.php'];
    
    foreach ($pages as $page) {
        $html = file_get_contents("http://localhost/{$page}");
        expect($html)->toContain('class="language-switcher"');
        expect($html)->toContain('data-lang="es"');
        expect($html)->toContain('data-lang="en"');
    }
});
```

### Integration Testing

**Manual Testing Checklist**:
1. Visual verification of color scheme against reference site
2. Language switching across all pages
3. Social media links open in new tabs
4. Contact links work on mobile devices
5. Responsive design on different screen sizes
6. Browser compatibility (Chrome, Firefox, Safari, Edge)

### Test Coverage Goals

- **Unit tests**: 80%+ code coverage
- **Property tests**: 100% of correctness properties implemented
- **Integration tests**: All user workflows tested
- **Manual tests**: All visual and UX requirements verified

### Continuous Testing

- Run unit tests on every commit
- Run property tests before merging to main branch
- Perform manual testing before deployment
- Monitor error logs for production issues
