<?php
namespace App\Utils;

class CSSGenerator
{
    public static function generateThemeCSS()
    {
        $themeConfig = require __DIR__ . '/../../config/theme.php';
        $colors = $themeConfig['colors'];
        
        $css = ":root {\n";
        
        // Primary Colors
        $css .= "    /* Primary Colors */\n";
        $css .= "    --color-primary: {$colors['primary']};\n";
        $css .= "    --color-primary-dark: {$colors['primary-dark']};\n";
        $css .= "    --color-primary-light: {$colors['primary-light']};\n";
        
        // Accent Colors
        $css .= "    \n    /* Accent Colors */\n";
        $css .= "    --color-accent: {$colors['accent']};\n";
        $css .= "    --color-accent-dark: {$colors['accent-dark']};\n";
        $css .= "    --color-accent-light: {$colors['accent-light']};\n";
        
        // Status Colors
        $css .= "    \n    /* Status Colors */\n";
        $css .= "    --color-success: {$colors['success']};\n";
        $css .= "    --color-info: {$colors['info']};\n";
        $css .= "    --color-warning: {$colors['warning']};\n";
        $css .= "    --color-danger: {$colors['danger']};\n";
        
        // Neutral Colors
        $css .= "    \n    /* Neutral Colors */\n";
        $css .= "    --color-dark: {$colors['dark']};\n";
        $css .= "    --color-light: {$colors['light']};\n";
        
        // Component Colors
        $css .= "    \n    /* Component Colors */\n";
        $css .= "    --sidebar-bg: #2c3e50;\n";
        $css .= "    --navbar-bg: #ffffff;\n";
        $css .= "    --card-bg: #ffffff;\n";
        $css .= "    --body-bg: #f8f9fa;\n";
        
        $css .= "}\n";
        
        return $css;
    }
    
    public static function saveThemeCSS()
    {
        $cssContent = self::generateThemeCSS();
        $filePath = __DIR__ . '/../../public/assets/css/theme.css';
        
        // สร้างโฟลเดอร์ถ้ายังไม่มี
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        file_put_contents($filePath, $cssContent);
        return $filePath;
    }
    
    public static function getColor($colorName)
    {
        $themeConfig = require __DIR__ . '/../../config/theme.php';
        return $themeConfig['colors'][$colorName] ?? '#000000';
    }
    
    public static function updateColors($newColors)
    {
        $themeConfig = require __DIR__ . '/../../config/theme.php';
        
        // อัพเดทสี
        foreach ($newColors as $key => $value) {
            if (isset($themeConfig['colors'][$key])) {
                $themeConfig['colors'][$key] = $value;
            }
        }
        
        // บันทึก config ใหม่
        $configContent = "<?php\nreturn " . var_export($themeConfig, true) . ";\n?>";
        file_put_contents(__DIR__ . '/../../config/theme.php', $configContent);
        
        // สร้าง CSS ใหม่
        self::saveThemeCSS();
        
        return true;
    }
}
?>