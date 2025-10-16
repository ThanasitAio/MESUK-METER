<?php
/**
 * Generate Theme CSS File
 * สร้างไฟล์ theme-generated.css จาก config/theme.php
 */

require_once __DIR__ . '/config/theme.php';
require_once __DIR__ . '/app/utils/ThemeHelper.php';

// Generate CSS
$css = ThemeHelper::generateThemeCSS();

// Save to file
$outputFile = __DIR__ . '/assets/css/theme-generated.css';
file_put_contents($outputFile, $css);

echo "✅ Theme CSS generated successfully!\n";
echo "📄 Output: {$outputFile}\n";
echo "📊 Size: " . number_format(strlen($css)) . " bytes\n";
?>
