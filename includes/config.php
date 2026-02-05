<?php
/**
 * Configuration File
 * Contains site constants, course information, curriculum modules, and faculty data
 */

// Site Configuration
define('SITE_NAME', 'Post Graduate Course in Cloud Computing');
define('SITE_URL', 'https://your-app.azurewebsites.net');
define('CONTACT_EMAIL', 'info@cloudcomputing.edu');

// Course Information
define('COURSE_DURATION', '2 years');
define('COURSE_START_DATE', 'September 2024');
define('COURSE_MODE', 'Full-time / Part-time');

// Curriculum Modules
global $curriculum;
$curriculum = [
    [
        'module' => 'Cloud Fundamentals',
        'topics' => [
            'Cloud Computing Concepts',
            'Service Models (IaaS, PaaS, SaaS)',
            'Deployment Models',
            'Cloud Economics and Business Models'
        ],
        'credits' => 6
    ],
    [
        'module' => 'Cloud Architecture',
        'topics' => [
            'Design Patterns',
            'Scalability and Elasticity',
            'High Availability',
            'Disaster Recovery',
            'Microservices Architecture'
        ],
        'credits' => 6
    ],
    [
        'module' => 'DevOps and CI/CD',
        'topics' => [
            'Version Control with Git',
            'Continuous Integration',
            'Continuous Deployment',
            'Infrastructure as Code',
            'Configuration Management'
        ],
        'credits' => 6
    ],
    [
        'module' => 'Cloud Platforms',
        'topics' => [
            'Amazon Web Services (AWS)',
            'Microsoft Azure',
            'Google Cloud Platform',
            'Multi-cloud Strategies',
            'Cloud Migration'
        ],
        'credits' => 8
    ],
    [
        'module' => 'Cloud Security',
        'topics' => [
            'Identity and Access Management',
            'Encryption and Key Management',
            'Compliance and Governance',
            'Security Best Practices',
            'Threat Detection and Response'
        ],
        'credits' => 6
    ],
    [
        'module' => 'Capstone Project',
        'topics' => [
            'Real-world Cloud Implementation',
            'Project Planning and Management',
            'Technical Documentation',
            'Presentation and Defense'
        ],
        'credits' => 8
    ]
];

// Faculty Information
global $faculty;
$faculty = [
    [
        'name' => 'Dr. Jane Smith',
        'title' => 'Professor of Cloud Computing',
        'credentials' => 'PhD in Computer Science, AWS Certified Solutions Architect',
        'specialization' => 'Cloud Architecture and Distributed Systems',
        'image' => 'assets/images/faculty/jane-smith.jpg'
    ],
    [
        'name' => 'Prof. John Doe',
        'title' => 'Associate Professor',
        'credentials' => 'MS in Software Engineering, Azure Solutions Architect Expert',
        'specialization' => 'DevOps and Infrastructure Automation',
        'image' => 'assets/images/faculty/john-doe.jpg'
    ],
    [
        'name' => 'Dr. Sarah Johnson',
        'title' => 'Assistant Professor',
        'credentials' => 'PhD in Information Security, CISSP, Google Cloud Certified',
        'specialization' => 'Cloud Security and Compliance',
        'image' => 'assets/images/faculty/sarah-johnson.jpg'
    ],
    [
        'name' => 'Prof. Michael Chen',
        'title' => 'Senior Lecturer',
        'credentials' => 'MS in Cloud Computing, Kubernetes Certified Administrator',
        'specialization' => 'Container Orchestration and Microservices',
        'image' => 'assets/images/faculty/michael-chen.jpg'
    ]
];
