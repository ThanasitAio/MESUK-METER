<?php
// app/controllers/ImportUsersController.php

class ImportUsersController extends Controller {
    
    public function showImportUsersForm() {
        // ตั้งค่าข้อมูลสำหรับหน้า
        $data = [
            'title' => 'นำเข้าข้อมูลผู้ใช้',
            'currentPage' => 'import-users',
            
            // ตัวอย่างข้อมูล (ในกรณีจริงจะดึงจากฐานข้อมูล)
            'localUsers' => [
                ['mcode' => 'M001', 'name_f' => 'สมชาย', 'name_t' => 'Somchai', 'birthday' => '1990-01-15', 'id_card' => '1234567890123', 'mobile' => '0812345678', 'sex' => 'M', 'email' => 'somchai@example.com', 'sv_code' => 'SV001'],
                ['mcode' => 'M002', 'name_f' => 'สมหญิง', 'name_t' => 'Somying', 'birthday' => '1992-05-20', 'id_card' => '9876543210987', 'mobile' => '0898765432', 'sex' => 'F', 'email' => 'somying@example.com', 'sv_code' => 'SV002'],
            ],
            
            'externalUsers' => [
                ['mcode' => 'M003', 'name_f' => 'กฤษณะ', 'name_t' => 'Kritsana', 'birthday' => '1988-11-10', 'id_card' => '4567891230456', 'mobile' => '0855554444', 'sex' => 'M', 'email' => 'kritsana@example.com', 'sv_code' => 'SV003'],
                ['mcode' => 'M004', 'name_f' => 'นฤมล', 'name_t' => 'Naruemol', 'birthday' => '1995-03-25', 'id_card' => '6543219870654', 'mobile' => '0866667777', 'sex' => 'F', 'email' => 'naruemol@example.com', 'sv_code' => 'SV004'],
                ['mcode' => 'M001', 'name_f' => 'สมชาย', 'name_t' => 'Somchai', 'birthday' => '1990-01-15', 'id_card' => '1234567890123', 'mobile' => '0812345678', 'sex' => 'M', 'email' => 'somchai@example.com', 'sv_code' => 'SV001'], // ข้อมูลซ้ำ
            ]
        ];
        
        // กรองข้อมูลที่ซ้ำกัน
        $data['uniqueExternalUsers'] = [];
        foreach ($data['externalUsers'] as $user) {
            $isDuplicate = false;
            foreach ($data['localUsers'] as $localUser) {
                if ($user['mcode'] === $localUser['mcode']) {
                    $isDuplicate = true;
                    break;
                }
            }
            if (!$isDuplicate) {
                $data['uniqueExternalUsers'][] = $user;
            }
        }
        
        // โหลด view
        $this->view('pages/import-users', $data);
    }
    
    // API สำหรับนำเข้าข้อมูล
    public function importUsersAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // แก้ไขบรรทัดนี้: ใช้ isset() แทน ??
            $input = json_decode(file_get_contents('php://input'), true);
            $selectedUsers = isset($input['users']) ? $input['users'] : [];
            
            // จำลองการนำเข้าข้อมูล
            // ในกรณีจริงควรบันทึกลงฐานข้อมูล
            $importedCount = 0;
            foreach ($selectedUsers as $mcode) {
                // บันทึกข้อมูลลงฐานข้อมูล
                // $this->model('User')->importUser($mcode);
                $importedCount++;
            }
            
            echo json_encode([
                'success' => true, 
                'imported' => $importedCount,
                'message' => 'นำเข้าข้อมูลสำเร็จ'
            ]);
        }
    }
}