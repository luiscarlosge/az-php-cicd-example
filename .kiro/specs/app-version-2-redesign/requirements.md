# Requirements Document: App Version 2 Redesign

## Introduction

This document specifies the requirements for Version 2 of the PHP educational website. The redesign focuses on implementing a new visual identity, adding bilingual support (Spanish/English), updating course information to reflect the "Emerging Paradigms on Cloud Computing" program, restructuring faculty information, and updating contact details and social media links.

## Glossary

- **Website**: The PHP-based educational website system
- **User**: Any person accessing the website through a web browser
- **Language_Switcher**: UI component that allows users to toggle between Spanish and English
- **Color_Scheme**: The visual design palette including primary, secondary, and accent colors
- **Faculty_Section**: The webpage section displaying instructor information and credentials
- **Course_Information**: The collection of data describing the educational program
- **Contact_Form**: The web form allowing users to submit inquiries
- **Social_Media_Links**: Clickable links to external social media profiles
- **Hybrid_Mode**: Educational delivery combining in-person and online instruction
- **Placeholder_Image**: Temporary image file to be replaced with actual content later

## Requirements

### Requirement 1: Visual Design Update

**User Story:** As a user, I want to see a modern and professional visual design, so that the website reflects the institution's brand identity.

#### Acceptance Criteria

1. THE Website SHALL implement a color scheme based on the reference site https://www.escuelaing.edu.co/es/
2. WHEN a user loads any page, THE Website SHALL apply the new color scheme to all visual elements
3. THE Website SHALL maintain consistent color usage across all pages
4. THE Website SHALL ensure sufficient color contrast for accessibility compliance

### Requirement 2: Bilingual Language Support

**User Story:** As a user, I want to view the website in Spanish or English, so that I can read content in my preferred language.

#### Acceptance Criteria

1. THE Website SHALL support Spanish as the primary language
2. THE Website SHALL support English as a secondary language
3. WHEN a user accesses the website for the first time, THE Website SHALL display content in Spanish by default
4. THE Website SHALL display a Language_Switcher component on all pages
5. WHEN a user clicks the Language_Switcher, THE Website SHALL toggle between Spanish and English
6. WHEN a user switches languages, THE Website SHALL persist the language preference across page navigation
7. THE Website SHALL translate all user-facing text including navigation, headings, body content, and form labels
8. THE Website SHALL maintain the same page structure regardless of selected language

### Requirement 3: Course Information Updates

**User Story:** As a user, I want to see accurate course information, so that I understand the program details.

#### Acceptance Criteria

1. THE Website SHALL display the course title as "Emerging Paradigms on Cloud Computing"
2. THE Website SHALL display the course description as "Optional class from Master in Information Technologies program"
3. THE Website SHALL display the course duration as "12 sessions"
4. THE Website SHALL display the course mode as "Hybrid"
5. THE Website SHALL NOT display a start date field
6. WHEN a user views the course information in Spanish, THE Website SHALL display translated course details
7. WHEN a user views the course information in English, THE Website SHALL display English course details

### Requirement 4: Faculty Section Restructuring

**User Story:** As a user, I want to see information about the course instructor, so that I can learn about their qualifications.

#### Acceptance Criteria

1. THE Website SHALL display Luis Carlos Galvis Espitia as the Cloud Computing Lecturer
2. THE Website SHALL display a faculty photo using a Placeholder_Image file
3. THE Website SHALL display the following credentials:
   - Bachelor Degree in Systems Engineering from Colombian School of Engineering
   - Master Degree in Business and Information Technology from University of the Andes
   - AWS Solution Architect Associate certification
4. THE Website SHALL display only one faculty member
5. THE Website SHALL NOT display any previously existing faculty members
6. THE Website SHALL NOT display an Admissions section
7. WHEN a user views the Faculty_Section in Spanish, THE Website SHALL display translated faculty information
8. WHEN a user views the Faculty_Section in English, THE Website SHALL display English faculty information

### Requirement 5: Contact Information Updates

**User Story:** As a user, I want to contact the instructor, so that I can ask questions about the course.

#### Acceptance Criteria

1. THE Website SHALL display the email address as luis.galvis-e@escuelaing.edu.co
2. THE Website SHALL display the phone number as +57 3017859109
3. THE Website SHALL NOT display office information
4. THE Website SHALL NOT display an FAQ section
5. WHEN a user clicks the email address, THE Website SHALL open the user's default email client with the address pre-filled
6. WHEN a user clicks the phone number on a mobile device, THE Website SHALL initiate a phone call

### Requirement 6: Social Media Integration

**User Story:** As a user, I want to access the instructor's social media profiles, so that I can learn more about their professional background.

#### Acceptance Criteria

1. THE Website SHALL display a LinkedIn link to https://www.linkedin.com/in/luiscarlosgalvisespitia/
2. THE Website SHALL display a GitHub link to https://github.com/luiscarlosge/
3. THE Website SHALL display an Instagram link to https://www.instagram.com/luchogalvis/
4. WHEN a user clicks a social media link, THE Website SHALL open the link in a new browser tab
5. THE Website SHALL display recognizable icons for each social media platform
6. THE Website SHALL NOT display any previously existing social media links

### Requirement 7: Content Removal

**User Story:** As a website administrator, I want outdated content removed, so that the website only displays current information.

#### Acceptance Criteria

1. THE Website SHALL NOT display the admissions.php page
2. THE Website SHALL NOT include navigation links to the Admissions section
3. THE Website SHALL NOT display FAQ content
4. THE Website SHALL NOT display office location information
5. THE Website SHALL NOT display start date information for the course

### Requirement 8: File Structure Maintenance

**User Story:** As a developer, I want to maintain the existing PHP file structure, so that the codebase remains organized and maintainable.

#### Acceptance Criteria

1. THE Website SHALL maintain the existing directory structure with public assets
2. THE Website SHALL use the existing include files (config.php, header.php, footer.php, navigation.php)
3. THE Website SHALL organize CSS files in the assets directory
4. THE Website SHALL organize JavaScript files in the assets directory
5. THE Website SHALL organize image files in the assets directory
6. WHEN the language changes, THE Website SHALL load appropriate language-specific content without changing file structure
