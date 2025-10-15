<?php
namespace App\Utils;

class ThemeHelper
{
    private static $colors;
    
    public static function init()
    {
        if (self::$colors === null) {
            self::$colors = require __DIR__ . '/../../config/theme.php';
        }
    }
    
    public static function getColor($colorName)
    {
        self::init();
        return self::$colors['colors'][$colorName] ?? '#000000';
    }
    
    public static function getAllColors()
    {
        self::init();
        return self::$colors['colors'];
    }
    
    public static function updateColor($colorName, $newColor)
    {
        self::init();
        self::$colors['colors'][$colorName] = $newColor;
        
        // Save to config file
        $configContent = "<?php\nreturn " . var_export(self::$colors, true) . ";\n?>";
        file_put_contents(__DIR__ . '/../../config/theme.php', $configContent);
        
        // Regenerate CSS
        self::generateThemeCSS();
    }
    
    public static function generateThemeCSS()
    {
        $cssContent = self::getThemeCSSContent();
        file_put_contents(__DIR__ . '/../../public/assets/css/theme.css', $cssContent);
    }
    
    private static function getThemeCSSContent()
    {
        return "/* Theme Variables - Dynamic Colors */\n" .
               ":root {\n" .
               "    /* Primary Colors */\n" .
               "    --color-primary: " . self::getColor('primary') . ";\n" .
               "    --color-primary-dark: " . self::getColor('primary-dark') . ";\n" .
               "    --color-primary-light: " . self::getColor('primary-light') . ";\n" .
               "    \n" .
               "    /* Accent Colors */\n" .
               "    --color-accent: " . self::getColor('accent') . ";\n" .
               "    --color-accent-dark: " . self::getColor('accent-dark') . ";\n" .
               "    --color-accent-light: " . self::getColor('accent-light') . ";\n" .
               "    \n" .
               "    /* Status Colors */\n" .
               "    --color-success: " . self::getColor('success') . ";\n" .
               "    --color-info: " . self::getColor('info') . ";\n" .
               "    --color-warning: " . self::getColor('warning') . ";\n" .
               "    --color-danger: " . self::getColor('danger') . ";\n" .
               "    \n" .
               "    /* Neutral Colors */\n" .
               "    --color-dark: " . self::getColor('dark') . ";\n" .
               "    --color-light: " . self::getColor('light') . ";\n" .
               "    \n" .
               "    /* Component Colors */\n" .
               "    --sidebar-bg: #2c3e50;\n" .
               "    --navbar-bg: #ffffff;\n" .
               "    --card-bg: #ffffff;\n" .
               "    --body-bg: #f8f9fa;\n" .
               "}";
    }
}
?>