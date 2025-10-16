<?php
/**
 * Theme Demo - ตัวอย่างการใช้งาน Theme Configuration
 */

require_once 'app/utils/ThemeHelper.php';
use App\Utils\ThemeHelper;

// เรียกใช้ ThemeHelper
ThemeHelper::init();

echo "<h1>MESUK-METER Theme Configuration Demo</h1>\n";
echo "<style>body { font-family: Arial, sans-serif; margin: 20px; }</style>\n";

// แสดง Theme Information
echo "<h2>🎨 Theme Information</h2>\n";
$config = ThemeHelper::getThemeConfig();
echo "<p><strong>Theme Name:</strong> " . $config['theme_name'] . "</p>\n";
echo "<p><strong>Version:</strong> " . $config['theme_version'] . "</p>\n";

// แสดงสีหลัก
echo "<h2>🎯 Primary Colors</h2>\n";
echo "<div style='display: flex; gap: 10px; flex-wrap: wrap;'>\n";

$primaryColors = ['primary', 'primary-dark', 'primary-light', 'primary-hover'];
foreach ($primaryColors as $color) {
    $value = ThemeHelper::getColor($color);
    echo "<div style='padding: 10px; background: $value; color: white; border-radius: 5px; min-width: 120px; text-align: center;'>\n";
    echo "<strong>$color</strong><br>$value\n";
    echo "</div>\n";
}
echo "</div>\n";

// แสดงสี Accent
echo "<h2>✨ Accent Colors</h2>\n";
echo "<div style='display: flex; gap: 10px; flex-wrap: wrap;'>\n";

$accentColors = ['accent', 'accent-dark', 'accent-light', 'accent-hover'];
foreach ($accentColors as $color) {
    $value = ThemeHelper::getColor($color);
    echo "<div style='padding: 10px; background: $value; color: #333; border-radius: 5px; min-width: 120px; text-align: center;'>\n";
    echo "<strong>$color</strong><br>$value\n";
    echo "</div>\n";
}
echo "</div>\n";

// แสดงสีสถานะ
echo "<h2>📊 Status Colors</h2>\n";
echo "<div style='display: flex; gap: 10px; flex-wrap: wrap;'>\n";

$statusColors = ['success', 'info', 'warning', 'danger'];
foreach ($statusColors as $color) {
    $value = ThemeHelper::getColor($color);
    echo "<div style='padding: 10px; background: $value; color: white; border-radius: 5px; min-width: 120px; text-align: center;'>\n";
    echo "<strong>$color</strong><br>$value\n";
    echo "</div>\n";
}
echo "</div>\n";

// แสดงสี Component
echo "<h2>🧩 Component Colors</h2>\n";

// Navbar
echo "<h3>Navbar</h3>\n";
echo "<div style='padding: 15px; background: " . ThemeHelper::getComponentColor('navbar', 'bg') . "; color: " . ThemeHelper::getComponentColor('navbar', 'text') . "; border: 1px solid " . ThemeHelper::getComponentColor('navbar', 'border') . "; border-radius: 5px;'>\n";
echo "This is a navbar example<br>\n";
echo "<small>Background: " . ThemeHelper::getComponentColor('navbar', 'bg') . " | Text: " . ThemeHelper::getComponentColor('navbar', 'text') . "</small>\n";
echo "</div>\n";

// Card
echo "<h3>Card</h3>\n";
echo "<div style='background: " . ThemeHelper::getComponentColor('card', 'bg') . "; border: 2px solid " . ThemeHelper::getComponentColor('card', 'border') . "; border-radius: 10px; overflow: hidden; max-width: 300px;'>\n";
echo "<div style='padding: 10px; background: " . ThemeHelper::getComponentColor('card', 'header_bg') . "; color: " . ThemeHelper::getComponentColor('card', 'header_text') . "; font-weight: bold;'>Card Header</div>\n";
echo "<div style='padding: 15px;'>This is card content area</div>\n";
echo "</div>\n";

// Table
echo "<h3>Table Colors</h3>\n";
echo "<table style='border-collapse: collapse; width: 100%; max-width: 500px;'>\n";
echo "<thead style='background: " . ThemeHelper::getComponentColor('table', 'header_bg') . "; color: " . ThemeHelper::getComponentColor('table', 'header_text') . ";'>\n";
echo "<tr><th style='padding: 10px; border: 1px solid #ccc;'>Column 1</th><th style='padding: 10px; border: 1px solid #ccc;'>Column 2</th></tr>\n";
echo "</thead>\n";
echo "<tbody>\n";
echo "<tr style='background: " . ThemeHelper::getComponentColor('table', 'row_odd') . "; color: " . ThemeHelper::getComponentColor('table', 'text') . ";'>\n";
echo "<td style='padding: 8px; border: 1px solid #ccc;'>Row 1 (Odd)</td><td style='padding: 8px; border: 1px solid #ccc;'>Data</td>\n";
echo "</tr>\n";
echo "<tr style='background: " . ThemeHelper::getComponentColor('table', 'row_even') . "; color: " . ThemeHelper::getComponentColor('table', 'text') . ";'>\n";
echo "<td style='padding: 8px; border: 1px solid #ccc;'>Row 2 (Even)</td><td style='padding: 8px; border: 1px solid #ccc;'>Data</td>\n";
echo "</tr>\n";
echo "</tbody>\n";
echo "</table>\n";

// แสดงการใช้งาน Alternative Themes
echo "<h2>🔄 Alternative Themes</h2>\n";
echo "<p>ระบบรองรับ theme อื่นๆ ที่สามารถเปลี่ยนได้:</p>\n";

$altThemes = $config['alternative_themes'];
foreach ($altThemes as $themeName => $colors) {
    echo "<h4>$themeName</h4>\n";
    echo "<div style='display: flex; gap: 5px;'>\n";
    foreach ($colors as $colorName => $colorValue) {
        echo "<div style='padding: 5px; background: $colorValue; color: white; border-radius: 3px; font-size: 12px;'>$colorName<br>$colorValue</div>\n";
    }
    echo "</div>\n";
}

// สร้าง CSS
echo "<h2>🎨 Generate CSS</h2>\n";
echo "<p>คลิกปุ่มด้านล่างเพื่อสร้าง CSS ไฟล์ใหม่จาก theme config:</p>\n";

// ตรวจสอบว่าสร้าง CSS ได้หรือไม่
try {
    $cssGenerated = ThemeHelper::generateThemeCSS();
    if ($cssGenerated) {
        echo "<div style='padding: 10px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;'>\n";
        echo "✅ Theme CSS has been generated successfully!<br>\n";
        echo "📁 File location: assets/css/theme.css\n";
        echo "</div>\n";
    }
} catch (Exception $e) {
    echo "<div style='padding: 10px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;'>\n";
    echo "❌ Error generating CSS: " . $e->getMessage() . "\n";
    echo "</div>\n";
}

// แสดงตัวอย่างการใช้งาน API
echo "<h2>🔧 API Usage Examples</h2>\n";
echo "<pre style='background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto;'>\n";
echo htmlspecialchars("<?php
// ดึงสีหลัก
\$primaryColor = ThemeHelper::getColor('primary');

// ดึงสีของ component
\$navbarBg = ThemeHelper::getComponentColor('navbar', 'bg');

// อัปเดตสี
ThemeHelper::updateColor('primary', '#ff0000');

// เปลี่ยน theme
ThemeHelper::applyAlternativeTheme('blue_theme');

// สร้าง CSS ใหม่
ThemeHelper::generateThemeCSS();
?>");
echo "</pre>\n";

echo "<h2>📋 Summary</h2>\n";
echo "<ul>\n";
echo "<li>✅ Theme config ครบถ้วนพร้อมใช้งาน</li>\n";
echo "<li>✅ สามารถแก้สีได้ทั้งหมดในที่เดียว</li>\n";
echo "<li>✅ รองรับหลาย theme สำหรับระบบอื่นๆ</li>\n";
echo "<li>✅ สร้าง CSS แบบ dynamic</li>\n";
echo "<li>✅ API ที่ใช้งานง่าย</li>\n";
echo "</ul>\n";

?>