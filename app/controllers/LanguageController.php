<?php
class LanguageController {
    public function switchLanguage() {
        error_log("Language switch called");
        
        if (isset($_POST['lang']) && in_array($_POST['lang'], ['en', 'th'])) {
            $lang = $_POST['lang'];
            error_log("Switching language to: " . $lang);
            
            Language::setLanguage($lang);
            
            // Redirect back - แก้ไขสำหรับ PHP ต่ำกว่า 7.0
            $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : url('/');
            error_log("Redirecting to: " . $redirect);
            header('Location: ' . $redirect);
            exit;
        } else {
            error_log("Invalid language parameter");
            // Redirect to home if invalid lang
            header('Location: ' . url('/'));
            exit;
        }
    }
}