<?php
namespace App\Utils;

/**
 * ThemeHelper Class
 * จัดการ theme configuration และสร้าง CSS แบบ dynamic
 */
class ThemeHelper
{
    private static $themeConfig;
    
    /**
     * เริ่มต้น theme config
     */
    public static function init()
    {
        if (self::$themeConfig === null) {
            self::$themeConfig = require __DIR__ . '/../../config/theme.php';
        }
    }
    
    /**
     * ดึงสีจาก config
     */
    public static function getColor($colorName)
    {
        self::init();
        return isset(self::$themeConfig['colors'][$colorName]) ? self::$themeConfig['colors'][$colorName] : '#000000';
    }
    
    /**
     * ดึงสีของ component
     */
    public static function getComponentColor($component, $property)
    {
        self::init();
        return isset(self::$themeConfig['components'][$component][$property]) ? self::$themeConfig['components'][$component][$property] : '#000000';
    }
    
    /**
     * ดึงค่า layout setting
     */
    public static function getLayoutSetting($setting)
    {
        self::init();
        return isset(self::$themeConfig['layout'][$setting]) ? self::$themeConfig['layout'][$setting] : null;
    }
    
    /**
     * ดึงทุกสีในระบบ
     */
    public static function getAllColors()
    {
        self::init();
        return self::$themeConfig['colors'];
    }
    
    /**
     * ดึง theme config ทั้งหมด
     */
    public static function getThemeConfig()
    {
        self::init();
        return self::$themeConfig;
    }
    
    /**
     * อัปเดตสี
     */
    public static function updateColor($colorName, $newColor)
    {
        self::init();
        self::$themeConfig['colors'][$colorName] = $newColor;
        self::saveConfig();
        self::generateThemeCSS();
    }
    
    /**
     * อัปเดตสีของ component
     */
    public static function updateComponentColor($component, $property, $newColor)
    {
        self::init();
        self::$themeConfig['components'][$component][$property] = $newColor;
        self::saveConfig();
        self::generateThemeCSS();
    }
    
    /**
     * เปลี่ยน theme แบบทั้งชุด
     */
    public static function applyAlternativeTheme($themeName)
    {
        self::init();
        if (isset(self::$themeConfig['alternative_themes'][$themeName])) {
            $altTheme = self::$themeConfig['alternative_themes'][$themeName];
            foreach ($altTheme as $colorName => $colorValue) {
                self::$themeConfig['colors'][$colorName] = $colorValue;
            }
            self::saveConfig();
            self::generateThemeCSS();
            return true;
        }
        return false;
    }
    
    /**
     * บันทึก config กลับไปที่ไฟล์
     */
    private static function saveConfig()
    {
        $configContent = "<?php\n/**\n * Theme Configuration\n * ปรับแต่งสีของระบบทั้งหมดในไฟล์เดียว\n * สามารถเปลี่ยนแปลงสำหรับระบบอื่นๆ ได้ง่าย\n */\n\nreturn " . var_export(self::$themeConfig, true) . ";\n?>";
        file_put_contents(__DIR__ . '/../../config/theme.php', $configContent);
    }
    
    /**
     * สร้าง CSS ไฟล์แบบ dynamic
     */
    public static function generateThemeCSS()
    {
        $cssContent = self::getThemeCSSContent();
        
        // สร้างไฟล์ CSS ใหม่
        $cssFile = __DIR__ . '/../../assets/css/theme.css';
        file_put_contents($cssFile, $cssContent);
        
        return true;
    }
    
    /**
     * สร้างเนื้อหา CSS จาก theme config
     */
    private static function getThemeCSSContent()
    {
        self::init();
        
        $css = "/* ==============================================\n";
        $css .= "   Theme CSS - Generated Dynamically\n";
        $css .= "   Theme: " . self::$themeConfig['theme_name'] . "\n";
        $css .= "   Version: " . self::$themeConfig['theme_version'] . "\n";
        $css .= "   Generated: " . date('Y-m-d H:i:s') . "\n";
        $css .= "   ============================================== */\n\n";
        
        // CSS Variables
        $css .= ":root {\n";
        $css .= "    /* ========== PRIMARY COLORS ========== */\n";
        $css .= "    --color-primary: " . self::getColor('primary') . ";\n";
        $css .= "    --color-primary-dark: " . self::getColor('primary-dark') . ";\n";
        $css .= "    --color-primary-light: " . self::getColor('primary-light') . ";\n";
        $css .= "    --color-primary-hover: " . self::getColor('primary-hover') . ";\n\n";
        
        $css .= "    /* ========== ACCENT COLORS ========== */\n";
        $css .= "    --color-accent: " . self::getColor('accent') . ";\n";
        $css .= "    --color-accent-dark: " . self::getColor('accent-dark') . ";\n";
        $css .= "    --color-accent-light: " . self::getColor('accent-light') . ";\n";
        $css .= "    --color-accent-hover: " . self::getColor('accent-hover') . ";\n\n";
        
        $css .= "    /* ========== STATUS COLORS ========== */\n";
        $css .= "    --color-success: " . self::getColor('success') . ";\n";
        $css .= "    --color-success-light: " . self::getColor('success-light') . ";\n";
        $css .= "    --color-info: " . self::getColor('info') . ";\n";
        $css .= "    --color-info-light: " . self::getColor('info-light') . ";\n";
        $css .= "    --color-warning: " . self::getColor('warning') . ";\n";
        $css .= "    --color-warning-light: " . self::getColor('warning-light') . ";\n";
        $css .= "    --color-danger: " . self::getColor('danger') . ";\n";
        $css .= "    --color-danger-light: " . self::getColor('danger-light') . ";\n\n";
        
        $css .= "    /* ========== NEUTRAL COLORS ========== */\n";
        $css .= "    --color-dark: " . self::getColor('dark') . ";\n";
        $css .= "    --color-dark-light: " . self::getColor('dark-light') . ";\n";
        $css .= "    --color-gray: " . self::getColor('gray') . ";\n";
        $css .= "    --color-gray-light: " . self::getColor('gray-light') . ";\n";
        $css .= "    --color-light: " . self::getColor('light') . ";\n";
        $css .= "    --color-white: " . self::getColor('white') . ";\n\n";
        
        $css .= "    /* ========== TEXT COLORS ========== */\n";
        $css .= "    --text-primary: " . self::getColor('text-primary') . ";\n";
        $css .= "    --text-secondary: " . self::getColor('text-secondary') . ";\n";
        $css .= "    --text-muted: " . self::getColor('text-muted') . ";\n";
        $css .= "    --text-accent: " . self::getColor('text-accent') . ";\n\n";
        
        $css .= "    /* ========== BORDER COLORS ========== */\n";
        $css .= "    --border-primary: " . self::getColor('border-primary') . ";\n";
        $css .= "    --border-accent: " . self::getColor('border-accent') . ";\n";
        $css .= "    --border-light: " . self::getColor('border-light') . ";\n\n";
        
        $css .= "    /* ========== COMPONENT COLORS ========== */\n";
        
        // Navbar
        $css .= "    --navbar-bg: " . self::getComponentColor('navbar', 'bg') . ";\n";
        $css .= "    --navbar-text: " . self::getComponentColor('navbar', 'text') . ";\n";
        $css .= "    --navbar-border: " . self::getComponentColor('navbar', 'border') . ";\n";
        $css .= "    --navbar-shadow: " . self::getComponentColor('navbar', 'shadow') . ";\n\n";
        
        // Sidebar
        $css .= "    --sidebar-bg: " . self::getComponentColor('sidebar', 'bg') . ";\n";
        $css .= "    --sidebar-text: " . self::getComponentColor('sidebar', 'text') . ";\n";
        $css .= "    --sidebar-border: " . self::getComponentColor('sidebar', 'border') . ";\n";
        $css .= "    --sidebar-shadow: " . self::getComponentColor('sidebar', 'shadow') . ";\n";
        $css .= "    --sidebar-brand-bg: " . self::getComponentColor('sidebar', 'brand_bg') . ";\n\n";
        
        // Body
        $css .= "    --body-bg: " . self::getComponentColor('body', 'bg') . ";\n";
        $css .= "    --body-text: " . self::getComponentColor('body', 'text') . ";\n\n";
        
        // Card
        $css .= "    --card-bg: " . self::getComponentColor('card', 'bg') . ";\n";
        $css .= "    --card-header-bg: " . self::getComponentColor('card', 'header_bg') . ";\n";
        $css .= "    --card-header-text: " . self::getComponentColor('card', 'header_text') . ";\n";
        $css .= "    --card-border: " . self::getComponentColor('card', 'border') . ";\n";
        $css .= "    --card-shadow: " . self::getComponentColor('card', 'shadow') . ";\n\n";
        
        // Table
        $css .= "    --table-header-bg: " . self::getComponentColor('table', 'header_bg') . ";\n";
        $css .= "    --table-header-text: " . self::getComponentColor('table', 'header_text') . ";\n";
        $css .= "    --table-row-even: " . self::getComponentColor('table', 'row_even') . ";\n";
        $css .= "    --table-row-odd: " . self::getComponentColor('table', 'row_odd') . ";\n";
        $css .= "    --table-row-hover: " . self::getComponentColor('table', 'row_hover') . ";\n";
        $css .= "    --table-text: " . self::getComponentColor('table', 'text') . ";\n";
        $css .= "    --table-shadow: " . self::getComponentColor('table', 'shadow') . ";\n\n";
        
        // Form
        $css .= "    --form-input-border: " . self::getComponentColor('form', 'input_border') . ";\n";
        $css .= "    --form-input-focus: " . self::getComponentColor('form', 'input_focus') . ";\n";
        $css .= "    --form-checkbox-accent: " . self::getComponentColor('form', 'checkbox_accent') . ";\n\n";
        
        // Button
        $css .= "    --btn-primary-bg: " . self::getComponentColor('button', 'primary_bg') . ";\n";
        $css .= "    --btn-primary-text: " . self::getComponentColor('button', 'primary_text') . ";\n";
        $css .= "    --btn-primary-hover: " . self::getComponentColor('button', 'primary_hover') . ";\n";
        $css .= "    --btn-secondary-bg: " . self::getComponentColor('button', 'secondary_bg') . ";\n";
        $css .= "    --btn-secondary-text: " . self::getComponentColor('button', 'secondary_text') . ";\n";
        $css .= "    --btn-success-bg: " . self::getComponentColor('button', 'success_bg') . ";\n";
        $css .= "    --btn-danger-bg: " . self::getComponentColor('button', 'danger_bg') . ";\n\n";
        
        $css .= "    /* ========== LAYOUT SETTINGS ========== */\n";
        $css .= "    --sidebar-width: " . self::getLayoutSetting('sidebar_width') . ";\n";
        $css .= "    --navbar-height: " . self::getLayoutSetting('navbar_height') . ";\n";
        $css .= "    --border-radius: " . self::getLayoutSetting('border_radius') . ";\n";
        $css .= "    --card-radius: " . self::getLayoutSetting('card_radius') . ";\n\n";
        
        $css .= "    /* ========== ANIMATION SETTINGS ========== */\n";
        $css .= "    --transition-duration: " . self::$themeConfig['animation']['transition_duration'] . ";\n";
        $css .= "    --transition-easing: " . self::$themeConfig['animation']['transition_easing'] . ";\n";
        $css .= "    --sidebar-transition: " . self::$themeConfig['animation']['sidebar_transition'] . ";\n";
        
        $css .= "}\n\n";
        
        $css .= "/* ========== UTILITY CLASSES ========== */\n";
        $css .= ".text-primary { color: var(--text-primary) !important; }\n";
        $css .= ".text-secondary { color: var(--text-secondary) !important; }\n";
        $css .= ".text-muted { color: var(--text-muted) !important; }\n";
        $css .= ".text-accent { color: var(--text-accent) !important; }\n";
        $css .= ".bg-primary { background-color: var(--color-primary) !important; }\n";
        $css .= ".bg-accent { background-color: var(--color-accent) !important; }\n";
        $css .= ".border-primary { border-color: var(--border-primary) !important; }\n";
        $css .= ".border-accent { border-color: var(--border-accent) !important; }\n";
        
        return $css;
    }
    
    /**
     * ตรวจสอบว่า theme config ถูกต้องหรือไม่
     */
    public static function validateThemeConfig()
    {
        self::init();
        
        $requiredKeys = ['theme_name', 'colors', 'components', 'layout'];
        foreach ($requiredKeys as $key) {
            if (!isset(self::$themeConfig[$key])) {
                return false;
            }
        }
        
        return true;
    }
}
?>