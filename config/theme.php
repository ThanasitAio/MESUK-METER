<?php
/**
 * Theme Configuration
 * ปรับแต่งสีของระบบทั้งหมดในไฟล์เดียว
 * สามารถเปลี่ยนแปลงสำหรับระบบอื่นๆ ได้ง่าย
 */

return [
    // === THEME SETTINGS ===
    'theme_name' => 'MESUK-METER Default',
    'theme_version' => '1.0.0',
    
    // === PRIMARY COLOR SCHEME ===
    // สีหลักของระบบ
    'colors' => [
        // Primary Colors (สีหลัก)
        'primary' => '#086337',
        'primary-dark' => '#064c28', 
        'primary-light' => '#0a7a46',
        'primary-hover' => '#0a7a46',
        
        // Accent Colors (สีเสริม)
        'accent' => '#D3EE98',
        'accent-dark' => '#b8                                                                                   d47a',
        'accent-light' => '#e5f5c3',
        'accent-hover' => '#A9D654',
        
        // Status Colors (สีสถานะ)
        'success' => '#28a745',
        'success-light' => '#d4edda',
        'info' => '#36b9cc',
        'info-light' => '#d1ecf1',
        'warning' => '#f6c23e',
        'warning-light' => '#fff3cd',
        'danger' => '#e74a3b',
        'danger-light' => '#f8d7da',
        
        // Neutral Colors (สีกลาง)
        'dark' => '#1a1a1a',
        'dark-light' => '#495057',
        'gray' => '#6c757d',
        'gray-light' => '#adb5bd',
        'light' => '#f8f9fa',
        'white' => '#ffffff',
        
        // Text Colors (สีข้อความ)
        'text-primary' => '#1a1a1a',
        'text-secondary' => '#6c757d',
        'text-muted' => '#6B7A00',
        'text-accent' => '#405000',
        
        // Border Colors (สีเส้นขอบ)
        'border-primary' => '#e9ecef',
        'border-accent' => '#A9D654',
        'border-light' => '#dee2e6',
    ],
    
    // === COMPONENT COLORS ===
    // สีสำหรับส่วนประกอบต่างๆ
    'components' => [
        // Navigation
        'navbar' => [
            'bg' => '#ffffff',
            'text' => '#1a1a1a',
            'border' => '#e9ecef',
            'shadow' => 'rgba(0, 0, 0, 0.1)',
        ],
        
        'sidebar' => [
            'bg' => '#ffffff',
            'text' => '#495057',
            'border' => '#e9ecef',
            'shadow' => 'rgba(0, 0, 0, 0.1)',
            'brand_bg' => 'linear-gradient(135deg, #D3EE98 0%, #b8d47a 100%)',
        ],
        
        // Content Areas
        'body' => [
            'bg' => '#f8f9fa',
            'text' => '#1a1a1a',
        ],
        
        'card' => [
            'bg' => '#ffffff',
            'header_bg' => '#D3EE98',
            'header_text' => '#405000',
            'border' => '#A9D654',
            'shadow' => 'rgba(64,80,0,0.1)',
        ],
        
        // Tables
        'table' => [
            'header_bg' => '#A9D654',
            'header_text' => '#ffffff',
            'row_even' => '#F3F9D9',
            'row_odd' => '#E6F5B7',
            'row_hover' => '#D3EE98',
            'text' => '#405000',
            'shadow' => 'rgba(64,80,0,0.15)',
        ],
        
        // Forms
        'form' => [
            'input_border' => '#A9D654',
            'input_focus' => '#086337',
            'checkbox_accent' => '#405000',
        ],
        
        // Buttons
        'button' => [
            'primary_bg' => '#086337',
            'primary_text' => '#ffffff',
            'primary_hover' => '#0a7a46',
            'secondary_bg' => '#6c757d',
            'secondary_text' => '#ffffff',
            'success_bg' => '#28a745',
            'danger_bg' => '#e74a3b',
        ],
    ],
    
    // === LAYOUT SETTINGS ===
    'layout' => [
        'sidebar_width' => '280px',
        'navbar_height' => '70px',
        'border_radius' => '8px',
        'card_radius' => '1rem',
        'box_shadow' => '0 8px 20px',
    ],
    
    // === RESPONSIVE BREAKPOINTS ===
    'breakpoints' => [
        'xs' => '576px',
        'sm' => '768px',
        'md' => '992px',
        'lg' => '1200px',
        'xl' => '1400px',
    ],
    
    // === ANIMATION SETTINGS ===
    'animation' => [
        'transition_duration' => '0.3s',
        'transition_easing' => 'ease',
        'sidebar_transition' => '0.5s cubic-bezier(0.4, 0, 0.2, 1)',
    ],
    
    // === ADDITIONAL THEMES ===
    // สำหรับระบบอื่นๆ ที่อาจใช้สีต่างกัน
    'alternative_themes' => [
        'blue_theme' => [
            'primary' => '#007bff',
            'primary-dark' => '#0056b3',
            'accent' => '#e3f2fd',
            'accent-dark' => '#90caf9',
        ],
        'red_theme' => [
            'primary' => '#dc3545',
            'primary-dark' => '#c82333',
            'accent' => '#f8d7da',
            'accent-dark' => '#f5c6cb',
        ],
        'purple_theme' => [
            'primary' => '#6f42c1',
            'primary-dark' => '#5a2d91',
            'accent' => '#e2d9f3',
            'accent-dark' => '#d1c7e3',
        ],
    ],
];
?>