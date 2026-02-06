# Implementation Plan: App Version 2 Redesign

## Overview

This implementation plan breaks down the Version 2 redesign into discrete coding tasks. The approach follows an incremental strategy: first establishing the language system foundation, then updating visual design, restructuring content, and finally implementing comprehensive testing. Each task builds on previous work to ensure continuous integration and validation.

**Current Status**: Core implementation (tasks 1-9) is complete. Remaining work includes social media integration, file structure verification, and comprehensive testing.

## Tasks

- [x] 1. Set up language management system
  - [x] 1.1 Create language file structure and translation files
    - Create `lang/es.php` with complete Spanish translations for all content
    - Create `lang/en.php` with complete English translations for all content
    - Include translations for navigation, course info, faculty, contact, and social media
    - _Requirements: 2.1, 2.2, 2.7_
  
  - [x] 1.2 Implement language management functions
    - Create `includes/language.php` with `initLanguage()`, `getCurrentLanguage()`, `setLanguage()`, and `t()` functions
    - Implement session-based language persistence
    - Add fallback logic for missing translations
    - _Requirements: 2.3, 2.6_
  
  - [x] 1.3 Update config.php to initialize language system
    - Add session_start() call
    - Include language.php
    - Call initLanguage() on every page load
    - _Requirements: 2.3, 8.2_
  
  - [ ]* 1.4 Write property test for language toggle behavior
    - **Property 3: Language Toggle Behavior**
    - **Validates: Requirements 2.5**
  
  - [ ]* 1.5 Write property test for language persistence
    - **Property 4: Language Persistence**
    - **Validates: Requirements 2.6**
  
  - [ ]* 1.6 Write property test for translation completeness
    - **Property 5: Translation Completeness**
    - **Validates: Requirements 2.7**

- [x] 2. Implement language switcher UI component
  - [x] 2.1 Add language switcher to header.php
    - Create HTML structure with ES/EN buttons
    - Add active state styling based on current language
    - Position switcher in header navigation area
    - _Requirements: 2.4_
  
  - [x] 2.2 Create language-switch.php endpoint
    - Handle POST requests with language parameter
    - Validate language code (es or en)
    - Update session with new language
    - Return success/error response
    - _Requirements: 2.5_
  
  - [x] 2.3 Add JavaScript for language switching
    - Create `assets/js/language-switcher.js`
    - Add click handlers for language buttons
    - Implement AJAX call to language-switch.php
    - Reload page on successful language change
    - _Requirements: 2.5_
  
  - [ ]* 2.4 Write property test for language switcher presence
    - **Property 2: Language Switcher Presence**
    - **Validates: Requirements 2.4**
  
  - [ ]* 2.5 Write unit tests for language switcher
    - Test switcher appears on all pages
    - Test active state reflects current language
    - Test invalid language codes are rejected
    - _Requirements: 2.4, 2.5_

- [x] 3. Checkpoint - Verify language system works
  - Ensure all tests pass, ask the user if questions arise.

- [x] 4. Implement new color scheme
  - [x] 4.1 Create CSS color variables
    - Create `assets/css/colors.css` with CSS custom properties
    - Define primary, secondary, neutral, and semantic colors based on reference site
    - Include spacing variables
    - _Requirements: 1.1, 1.2_
  
  - [x] 4.2 Update main.css to use color variables
    - Replace all hardcoded colors with CSS variables
    - Apply new color scheme to all components
    - Ensure consistent usage across all elements
    - _Requirements: 1.2, 1.3_
  
  - [ ]* 4.3 Write property test for color contrast accessibility
    - **Property 1: Color Contrast Accessibility**
    - **Validates: Requirements 1.4**
  
  - [ ]* 4.4 Write unit tests for color scheme
    - Test CSS variables are defined
    - Test color values match reference site
    - _Requirements: 1.1_

- [x] 5. Update course information content
  - [x] 5.1 Update course info in translation files
    - Update Spanish translations with new course title, description, duration, and mode
    - Update English translations with new course title, description, duration, and mode
    - Remove start date from both language files
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5_
  
  - [x] 5.2 Update index.php and curriculum.php to display new course info
    - Replace hardcoded course information with translation keys
    - Remove start date display logic
    - Ensure course info displays correctly in both languages
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7_
  
  - [ ]* 5.3 Write unit tests for course information
    - Test course title displays correctly in both languages
    - Test course description displays correctly
    - Test duration shows "12 sessions"
    - Test mode shows "Hybrid"
    - Test start date field is not present
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5_

- [x] 6. Restructure faculty section
  - [x] 6.1 Create placeholder faculty image
    - Create `assets/images/faculty/placeholder.jpg`
    - Use appropriate dimensions (e.g., 300x300px)
    - Add alt text support in template
    - _Requirements: 4.2_
  
  - [x] 6.2 Update faculty information in translation files
    - Add Luis Carlos Galvis Espitia as faculty name
    - Add "Cloud Computing Lecturer" as title
    - Add three credentials (Bachelor, Master, AWS certification)
    - Include translations in both Spanish and English
    - _Requirements: 4.1, 4.3, 4.7, 4.8_
  
  - [x] 6.3 Rewrite faculty.php to display single faculty member
    - Remove all previous faculty member code
    - Create new layout for single faculty member
    - Display photo, name, title, and credentials
    - Ensure responsive design
    - _Requirements: 4.1, 4.2, 4.3, 4.4_
  
  - [ ]* 6.4 Write unit tests for faculty section
    - Test only one faculty member is displayed
    - Test faculty name is correct
    - Test all three credentials are present
    - Test placeholder image is used
    - Test content displays in both languages
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.7, 4.8_

- [x] 7. Remove admissions section
  - [x] 7.1 Delete admissions.php file
    - Remove `public/admissions.php`
    - _Requirements: 4.6, 7.1_
  
  - [x] 7.2 Remove admissions navigation links
    - Update `includes/navigation.php` to remove admissions link
    - Update both Spanish and English navigation translations
    - _Requirements: 7.2_
  
  - [ ]* 7.3 Write unit tests for admissions removal
    - Test admissions.php returns 404
    - Test navigation does not contain admissions link
    - _Requirements: 4.6, 7.1, 7.2_

- [x] 8. Update contact information
  - [x] 8.1 Update contact info in translation files
    - Update email to luis.galvis-e@escuelaing.edu.co
    - Update phone to +57 3017859109
    - Remove office information from translations
    - Include labels in both languages
    - _Requirements: 5.1, 5.2, 5.3_
  
  - [x] 8.2 Update contact.php with new contact information
    - Display email with mailto: link
    - Display phone with tel: link
    - Remove office information display
    - Remove FAQ section
    - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5, 5.6_
  
  - [ ]* 8.3 Write property test for email link format
    - **Property 7: Email Link Format**
    - **Validates: Requirements 5.5**
  
  - [ ]* 8.4 Write property test for phone link format
    - **Property 8: Phone Link Format**
    - **Validates: Requirements 5.6**
  
  - [ ]* 8.5 Write unit tests for contact information
    - Test email displays correctly
    - Test phone displays correctly
    - Test office info is not present
    - Test FAQ section is not present
    - _Requirements: 5.1, 5.2, 5.3, 5.4_

- [x] 9. Checkpoint - Verify content updates are complete
  - Ensure all tests pass, ask the user if questions arise.

- [x] 10. Implement social media links
  - [x] 10.1 Update social media links in translation files
    - Add LinkedIn URL: https://www.linkedin.com/in/luiscarlosgalvisespitia/
    - Add GitHub URL: https://github.com/luiscarlosge/
    - Add Instagram URL: https://www.instagram.com/luchogalvis/
    - Include platform names in both languages
    - _Requirements: 6.1, 6.2, 6.3_
  
  - [x] 10.2 Create social media links component
    - Add social links section to footer.php or contact.php
    - Create HTML structure with anchor tags
    - Add target="_blank" and rel="noopener noreferrer" to all links
    - Add icon classes for each platform
    - _Requirements: 6.1, 6.2, 6.3, 6.4_
  
  - [x] 10.3 Add social media icon styles
    - Add icon font or SVG icons for LinkedIn, GitHub, Instagram
    - Style social links with hover effects
    - Ensure icons are recognizable and accessible
    - _Requirements: 6.5_
  
  - [ ]* 10.4 Write property test for external link behavior
    - **Property 9: External Link Behavior**
    - **Validates: Requirements 6.4**
  
  - [ ]* 10.5 Write unit tests for social media links
    - Test all three social media links are present
    - Test URLs are correct
    - Test links open in new tab
    - Test icons are displayed
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5_

- [x] 11. Verify file structure and organization
  - [x] 11.1 Verify directory structure matches requirements
    - Confirm public/ directory contains all main PHP files (index.php, contact.php, curriculum.php, faculty.php, language-switch.php)
    - Confirm includes/ directory contains all include files (config.php, header.php, footer.php, navigation.php, language.php)
    - Confirm lang/ directory contains translation files (es.php, en.php)
    - Confirm assets/ directory structure (css/, js/, images/)
    - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_
  
  - [ ]* 11.2 Write property test for language-invariant file structure
    - **Property 10: Language Switch File Structure Invariance**
    - **Validates: Requirements 8.6**
  
  - [ ]* 11.3 Write property test for language-invariant DOM structure
    - **Property 6: Language-Invariant Structure**
    - **Validates: Requirements 2.8**
  
  - [ ]* 11.4 Write unit tests for file organization
    - Test CSS files are in assets/css/
    - Test JS files are in assets/js/
    - Test images are in assets/images/
    - Test include files exist and are used correctly
    - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_

- [x] 12. Final integration and validation
  - [x] 12.1 Manual testing of all pages in both languages
    - Verify index.php displays correctly in Spanish and English
    - Verify curriculum.php displays correctly in both languages
    - Verify faculty.php displays correctly in both languages
    - Verify contact.php displays correctly in both languages
    - _Requirements: 2.7, 2.8_
  
  - [x] 12.2 Manual testing of language switching
    - Test switching from Spanish to English on each page
    - Test switching from English to Spanish on each page
    - Test language preference persists across navigation
    - _Requirements: 2.5, 2.6_
  
  - [x] 12.3 Verify all removed content is gone
    - Confirm admissions.php file does not exist
    - Confirm no navigation links to admissions
    - Confirm no FAQ content on any page
    - Confirm no office location information
    - Confirm no start date for course
    - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5_
  
  - [ ]* 12.4 Run complete property test suite
    - Execute all 10 property tests with 100 iterations each
    - Verify all properties pass
    - _Requirements: All_
  
  - [ ]* 12.5 Run complete unit test suite
    - Execute all unit tests
    - Verify 80%+ code coverage
    - _Requirements: All_

- [x] 13. Final checkpoint - Ensure all requirements met
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional and can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation at key milestones
- Property tests validate universal correctness properties with 100 iterations each
- Unit tests validate specific examples, edge cases, and error conditions
- The implementation follows a logical progression: language system → visual design → content updates → testing
- All translation keys must be added to both es.php and en.php simultaneously to maintain completeness

### Current Status Summary

**Completed (Tasks 1-9)**:
- ✅ Language management system with Spanish/English support
- ✅ Language switcher UI component with session persistence
- ✅ New color scheme based on reference site
- ✅ Updated course information (Emerging Paradigms on Cloud Computing)
- ✅ Restructured faculty section (single faculty member)
- ✅ Removed admissions section
- ✅ Updated contact information (email and phone)

**Remaining (Tasks 10-13)**:
- ⏳ Social media links integration (LinkedIn, GitHub, Instagram)
- ⏳ File structure verification
- ⏳ Final integration and validation
- ⏳ Optional: Comprehensive property-based and unit testing

**Next Steps**: Begin with Task 10 to implement social media links, then proceed through verification and testing tasks.
