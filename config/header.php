<?php
// config/header.php

return [
    'styles' => [
        // --- สไตล์สำหรับ Title ---
        'page_title' => 'font-size: 1.2rem; font-weight: 600; color: #3a3b45; position: relative; padding-bottom: 0.5rem;',
        
        // --- สไตล์สำหรับเส้นใต้ (ใช้ผ่าน Pseudo-element ::after) ---
        'page_title_after' => 'content: ""; position: absolute; bottom: 0; left: 0; width: 50px; height: 4px; background: linear-gradient(90deg, #4e73df, #1cc88a); border-radius: 2px;',
        
        // --- สไตล์สำหรับ Icon ---
        'page_title_icon' => 'margin-right: 0.75rem;',
        
        // --- สไตล์อื่นๆ (คงเดิมหรือปรับตามความเหมาะสม) ---
        'page_subtitle' => 'font-size: 0.9rem; color: #858796; margin-top: 0.25rem;',
        'card_title' => 'font-size: 0.95rem; font-weight: 600;',
        'table_header' => 'font-size: 0.8rem; font-weight: 600;',
        'badge' => 'font-size: 0.65rem;'
    ],
    'classes' => [
        'page_header' => 'd-flex justify-content-between align-items-center mb-2 mt-2',
        'page_title_container' => 'd-flex flex-column',
        'page_title' => 'm-0 d-flex align-items-center', // เพิ่ม d-flex และ align-items-center
        'page_actions' => 'd-flex gap-2 align-items-center',
        'card_header' => 'd-flex justify-content-between align-items-center py-2',
        'filter_card' => 'mb-2',
    ],
];