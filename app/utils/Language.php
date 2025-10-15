<?php
class Language {
    private static $currentLang = 'en';
    private static $translations = [];

   public static function init() {
    // ตรวจสอบภาษาจาก session หรือ cookie
    if (isset($_SESSION['lang'])) {
        self::$currentLang = $_SESSION['lang'];
    } elseif (isset($_COOKIE['lang'])) {
        self::$currentLang = $_COOKIE['lang'];
    }

    // โหลดไฟล์ภาษา - ใช้ BASE_PATH ที่ถูกต้อง
    $langFile = BASE_PATH . '/config/languages/' . self::$currentLang . '.php';
    
    error_log("=== LANGUAGE DEBUG ===");
    error_log("Language file path: " . $langFile);
    error_log("File exists: " . (file_exists($langFile) ? 'YES' : 'NO'));
    
    if (file_exists($langFile)) {
        error_log("Loading language file: " . $langFile);
        self::$translations = require $langFile;
        error_log("Translations loaded: " . (count(self::$translations) > 0 ? 'YES' : 'NO'));
    } else {
        // Fallback to English
        error_log("Language file not found, fallback to English");
        $fallbackFile = BASE_PATH . '/config/languages/en.php';
        if (file_exists($fallbackFile)) {
            self::$translations = require $fallbackFile;
        } else {
            error_log("ERROR: Fallback file also not found!");
            // ถ้าไม่มีไฟล์ภาษาเลย
           
        }
    }
    
    error_log("Final translations count: " . count(self::$translations));
}

    public static function setLanguage($lang) {
    if (in_array($lang, ['en', 'th'])) {
        self::$currentLang = $lang;
        $_SESSION['lang'] = $lang;
        setcookie('lang', $lang, time() + (365 * 24 * 60 * 60), '/');
        
        // โหลดภาษาใหม่
        $langFile = BASE_PATH . '/config/languages/' . $lang . '.php';
        if (file_exists($langFile)) {
            self::$translations = require $langFile;
        }
        
    }
}

    public static function get($key, $default = '') {
        $keys = explode('.', $key);
        $value = self::$translations;
        
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default;
            }
        }
        
        return $value;
    }

    public static function getCurrentLang() {
        return self::$currentLang;
    }

    public static function getTranslations() {
    return self::$translations;
}

public static function debug() {
    return [
        'current_lang' => self::$currentLang,
        'translations' => self::$translations,
        'session_lang' => isset($_SESSION['lang']) ? $_SESSION['lang'] : 'not set',
        'cookie_lang' => isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'not set'
    ];
}


}