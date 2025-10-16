# 🎨 MESUK-METER Theme Configuration Guide

## 📖 Overview

ระบบ Theme Configuration ของ MESUK-METER ถูกออกแบบให้สามารถจัดการสีและ styling ของระบบทั้งหมดในที่เดียว ทำให้ง่ายต่อการปรับแต่งสำหรับระบบอื่นๆ หรือการเปลี่ยนแปลง theme

## 📁 ไฟล์ที่เกี่ยวข้อง

```
config/
├── theme.php                 # การกำหนดค่า theme หลัก
app/utils/
├── ThemeHelper.php           # คลาสจัดการ theme
assets/css/
├── theme.css                 # CSS ที่สร้างจาก config
├── theme-generated.css       # ตัวอย่าง CSS ที่สร้างแล้ว
theme_demo.php               # ไฟล์ demo การใช้งาน
```

## 🎯 คุณสมบัติหลัก

### ✅ สีที่สามารถปรับแต่งได้
- **Primary Colors**: สีหลักของระบบ
- **Accent Colors**: สีเสริมและ highlight
- **Status Colors**: สีสำหรับสถานะต่างๆ (success, warning, danger, info)
- **Neutral Colors**: สีกลางสำหรับข้อความและพื้นหลัง
- **Component Colors**: สีเฉพาะสำหรับส่วนประกอบแต่ละชนิด

### ✅ การจัดการที่ยืดหยุ่น
- สร้าง CSS แบบ dynamic
- รองรับหลาย theme
- API ที่ใช้งานง่าย
- ระบบ fallback สำหรับสีที่ไม่มี

## 🛠️ การใช้งาน

### 1. การดึงสีจาก config

```php
<?php
use App\Utils\ThemeHelper;

// ดึงสีหลัก
$primaryColor = ThemeHelper::getColor('primary');           // #086337
$accentColor = ThemeHelper::getColor('accent');             // #D3EE98

// ดึงสีของ component
$navbarBg = ThemeHelper::getComponentColor('navbar', 'bg'); // #ffffff
$cardBorder = ThemeHelper::getComponentColor('card', 'border'); // #A9D654
?>
```

### 2. การอัปเดตสี

```php
<?php
// อัปเดตสีหลัก
ThemeHelper::updateColor('primary', '#ff0000');

// อัปเดตสี component
ThemeHelper::updateComponentColor('navbar', 'bg', '#f8f9fa');
?>
```

### 3. การเปลี่ยน theme

```php
<?php
// เปลี่ยนเป็น blue theme
ThemeHelper::applyAlternativeTheme('blue_theme');

// เปลี่ยนเป็น red theme
ThemeHelper::applyAlternativeTheme('red_theme');
?>
```

### 4. การสร้าง CSS ใหม่

```php
<?php
// สร้าง CSS ไฟล์ใหม่
ThemeHelper::generateThemeCSS();
?>
```

## 🎨 โครงสร้าง Theme Config

### Primary Colors (สีหลัก)
```php
'colors' => [
    'primary' => '#086337',        // สีหลักของระบบ
    'primary-dark' => '#064c28',   // สีหลักเข้ม
    'primary-light' => '#0a7a46',  // สีหลักอ่อน
    'primary-hover' => '#0a7a46',  // สีเมื่อ hover
]
```

### Accent Colors (สีเสริม)
```php
'accent' => '#D3EE98',           // สีเสริมหลัก
'accent-dark' => '#b8d47a',      // สีเสริมเข้ม
'accent-light' => '#e5f5c3',     // สีเสริมอ่อน
'accent-hover' => '#A9D654',     // สีเสริมเมื่อ hover
```

### Component Colors (สีส่วนประกอบ)
```php
'components' => [
    'navbar' => [
        'bg' => '#ffffff',         // พื้นหลัง navbar
        'text' => '#1a1a1a',       // ข้อความ navbar
        'border' => '#e9ecef',     // เส้นขอบ navbar
    ],
    'sidebar' => [
        'bg' => '#ffffff',         // พื้นหลัง sidebar
        'text' => '#495057',       // ข้อความ sidebar
    ],
    'card' => [
        'bg' => '#ffffff',         // พื้นหลัง card
        'header_bg' => '#D3EE98',  // พื้นหลัง header card
        'border' => '#A9D654',     // เส้นขอบ card
    ],
]
```

## 🔄 Alternative Themes

ระบบรองรับ theme อื่นๆ สำหรับระบบที่ต่างกัน:

### Blue Theme
```php
'blue_theme' => [
    'primary' => '#007bff',
    'primary-dark' => '#0056b3',
    'accent' => '#e3f2fd',
    'accent-dark' => '#90caf9',
]
```

### Red Theme
```php
'red_theme' => [
    'primary' => '#dc3545',
    'primary-dark' => '#c82333',
    'accent' => '#f8d7da',
    'accent-dark' => '#f5c6cb',
]
```

## 📱 CSS Variables

CSS ที่สร้างขึ้นจะใช้ CSS Variables เพื่อความยืดหยุ่น:

```css
:root {
    /* Primary Colors */
    --color-primary: #086337;
    --color-primary-dark: #064c28;
    
    /* Component Colors */
    --navbar-bg: #ffffff;
    --card-header-bg: #D3EE98;
    
    /* Layout */
    --sidebar-width: 280px;
    --navbar-height: 70px;
}
```

## 🎯 Utility Classes

ระบบสร้าง utility classes ให้ใช้งานได้ทันที:

```css
.text-primary { color: var(--text-primary) !important; }
.text-accent { color: var(--text-accent) !important; }
.bg-primary { background-color: var(--color-primary) !important; }
.bg-accent { background-color: var(--color-accent) !important; }
```

## 📋 ตัวอย่างการใช้งานใน View

```php
<!-- ใช้ ThemeHelper ใน view -->
<div style="background-color: <?= ThemeHelper::getColor('primary') ?>;">
    Primary Background
</div>

<div class="card" style="border-color: <?= ThemeHelper::getComponentColor('card', 'border') ?>;">
    <div class="card-header" style="background-color: <?= ThemeHelper::getComponentColor('card', 'header_bg') ?>;">
        Card Header
    </div>
</div>
```

## 🔧 การสร้างระบบใหม่

สำหรับระบบใหม่ที่ต้องการใช้สีต่างกัน:

1. **Copy theme config**:
   ```bash
   cp config/theme.php config/theme-new-system.php
   ```

2. **แก้ไขสีในไฟล์ใหม่**:
   ```php
   'colors' => [
       'primary' => '#YOUR_PRIMARY_COLOR',
       'accent' => '#YOUR_ACCENT_COLOR',
       // ...
   ]
   ```

3. **อัปเดต ThemeHelper** ให้ใช้ config ใหม่:
   ```php
   self::$themeConfig = require __DIR__ . '/../../config/theme-new-system.php';
   ```

4. **สร้าง CSS ใหม่**:
   ```php
   ThemeHelper::generateThemeCSS();
   ```

## ⚡ Performance Tips

1. **Cache CSS**: สร้าง CSS ครั้งเดียวและใช้ cache
2. **Minify**: ใช้ CSS minification สำหรับ production
3. **Lazy Loading**: โหลด theme เมื่อจำเป็นเท่านั้น

## 🔍 การ Debug

```php
<?php
// ตรวจสอบ theme config
var_dump(ThemeHelper::getThemeConfig());

// ตรวจสอบสีที่ได้
echo ThemeHelper::getColor('primary');

// ตรวจสอบว่า config ถูกต้อง
if (ThemeHelper::validateThemeConfig()) {
    echo "Theme config is valid!";
}
?>
```

## 🏃‍♂️ Quick Start

1. **ดู demo**: เปิดไฟล์ `theme_demo.php` ในเบราว์เซอร์
2. **แก้ไขสี**: เปิด `config/theme.php` และแก้ไขสีที่ต้องการ
3. **สร้าง CSS**: รัน `ThemeHelper::generateThemeCSS()`
4. **ใช้งาน**: import CSS ใหม่ในหน้าเว็บ

## 🎉 สรุป

Theme Configuration System ของ MESUK-METER ให้คุณ:

- ✅ จัดการสีทั้งหมดในที่เดียว
- ✅ เปลี่ยน theme ได้ง่าย
- ✅ รองรับหลายระบบ
- ✅ สร้าง CSS แบบ automatic
- ✅ ใช้งานผ่าน API ที่เข้าใจง่าย

ตอนนี้คุณสามารถปรับแต่งสีของระบบได้อย่างสะดวกและมีประสิทธิภาพ! 🚀