<?php
// ตรวจสอบว่า Language class มีอยู่
if (!class_exists('Language')) {
    require_once APP_PATH . '/utils/Language.php';
    // เรียก init ถ้ายังไม่ถูกเรียก
    if (method_exists('Language', 'init')) {
        Language::init();
    }
}

/**
 * Get base path from config
 */
function basePath($path = '') {
    static $basePath = null;
    
    if ($basePath === null) {
        $config = require BASE_PATH . '/config/app.php';
        $basePath = isset($config['app']['base_path']) ? $config['app']['base_path'] : '';
    }
    
    // Ensure no double slashes
    $fullPath = $basePath . '/' . ltrim($path, '/');
    return rtrim($fullPath, '/');
}

/**
 * Generate URL with base path
 */
function url($path = '') {
    return basePath($path);
}

function t($key, $default = '') {
    if (class_exists('Language') && method_exists('Language', 'get')) {
        return Language::get($key, $default);
    }
    return $default ?: $key; // Fallback
}

function currentLang() {
    if (class_exists('Language') && method_exists('Language', 'getCurrentLang')) {
        return Language::getCurrentLang();
    }
    return 'en'; // Default
}


function pageHeader($title, $subtitle = '', $actions = '', $iconClass = '') {
    $config = require BASE_PATH . '/config/header.php';
    
    $html = '<div class="' . $config['classes']['page_header'] . '">';
    
    // --- ส่วนของ Title (ฝั่งซ้าย) ---
    $html .= '<div class="' . $config['classes']['page_title_container'] . '">';
    $html .= '<h1 class="' . $config['classes']['page_title'] . '" style="' . $config['styles']['page_title'] . '">';
    
    // ถ้ามี $iconClass ให้แสดงไอคอน
    if (!empty($iconClass)) {
        $html .= '<i class="' . $iconClass . '" style="' . $config['styles']['page_title_icon'] . '"></i>';
    }
    
    $html .= '<span>' . $title . '</span>';
    $html .= '</h1>';
    
    // เพิ่มเส้นใต้ไล่ระดับสีด้วย <style> tag
    $html .= '<style>h1.' . $config['classes']['page_title'] . '::after { ' . $config['styles']['page_title_after'] . ' }</style>';
    
    // ถ้ามี subtitle ให้แสดง
    if (!empty($subtitle)) {
        $html .= '<p class="page-subtitle m-0" style="' . $config['styles']['page_subtitle'] . '">' . $subtitle . '</p>';
    }
    $html .= '</div>';
    
    // --- ส่วนของ Actions (ฝั่งขวา) ---
    $actionsHtml = '';
    if (!empty($actions)) {
        $actionsHtml .= '<div class="page-actions ' . $config['classes']['page_actions'] . '">';
        if (!empty($actions)) {
            $actionsHtml .= $actions;
        }
        $actionsHtml .= '</div>';
    }
    
    $html .= $actionsHtml;
    $html .= '</div>';
    
    return $html;
}


/**
 * สร้าง card header
 */
function cardHeader($title, $badge = '', $icon = '') {
    $config = require BASE_PATH . '/config/header.php';
    
    $html = '<div class="card-header ' . $config['classes']['card_header'] . '">';
    $html .= '<h5 class="card-title mb-0" style="' . $config['styles']['card_title'] . '">';
    
    if (!empty($icon)) {
        $html .= '<i class="' . $icon . ' me-2"></i>';
    }
     $html .= $title;
    
    $html .= '</h5>';
    
    if (!empty($badge)) {
        // ตรวจสอบว่า $badge เป็น array หรือไม่
        if (is_array($badge)) {
            $badge = count($badge); // ถ้าเป็น array ให้นับจำนวน
        }
        $html .= '<span class="badge" style="' . $config['styles']['card_count'] . '">' . $badge . '</span>';
    }
    
    $html .= '</div>';
    
    return $html;
}
