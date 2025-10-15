<?php
class ThemeController extends Controller
{
    public function generateCSS()
    {
        // เรียกใช้ CSS Generator
        $cssGenerator = new App\Utils\CSSGenerator();
        $filePath = $cssGenerator::saveThemeCSS();
        
        echo "CSS generated successfully: " . $filePath;
    }
    
    public function updateColors()
    {
        if ($_POST) {
            $cssGenerator = new App\Utils\CSSGenerator();
            $result = $cssGenerator::updateColors($_POST['colors']);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Colors updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update colors']);
            }
        }
    }
    
    public function preview()
    {
        $cssGenerator = new App\Utils\CSSGenerator();
        $colors = require __DIR__ . '/../../config/theme.php';
        
        $data = [
            'title' => 'Theme Colors',
            'colors' => $colors['colors']
        ];
        
        $this->view('theme/preview', $data);
    }
}
?>