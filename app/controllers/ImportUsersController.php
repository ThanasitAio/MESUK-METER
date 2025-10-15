<?php
// app/controllers/ImportUsersController.php

require_once __DIR__ . '/../core/Database.php';

class ImportUsersController extends Controller {
    
    public function showImportUsersForm() {
        try {
            $db = Database::getInstance();
            
            // ดึงข้อมูลจาก me_users (ที่อยู่ในระบบแล้ว)
            $sqlLocal = "SELECT code as mcode, name as name_f, tel as mobile 
                        FROM me_users 
                        ORDER BY code";
            $stmtLocal = $db->prepare($sqlLocal);
            $stmtLocal->execute();
            $localUsers = $stmtLocal->fetchAll(PDO::FETCH_ASSOC);
            
            // ดึงข้อมูลจาก ali_member (ที่รออยู่)
            $sqlExternal = "SELECT 
                            mcode, 
                            name_t, 
                            mobile,
                            sv_code,
                            birthday,
                            facebook_name,
                            line_id
                        FROM ali_member 
                        WHERE mcode IS NOT NULL 
                        AND mcode != ''
                        ORDER BY mcode
                        LIMIT 100";
            $stmtExternal = $db->prepare($sqlExternal);
            $stmtExternal->execute();
            $externalUsers = $stmtExternal->fetchAll(PDO::FETCH_ASSOC);
            
            // กรองข้อมูลที่ซ้ำกัน (เช็คว่าอยู่ใน me_users แล้วหรือยัง)
            $uniqueExternalUsers = array();
            $localCodes = array();
            
            // สร้าง array ของ code ที่มีอยู่แล้ว
            foreach ($localUsers as $localUser) {
                $localCodes[] = $localUser['mcode'];
            }
            
            // กรองเฉพาะที่ยังไม่มีใน me_users
            foreach ($externalUsers as $user) {
                if (!in_array($user['mcode'], $localCodes)) {
                    $uniqueExternalUsers[] = $user;
                }
            }
            
            $data = array(
                'title' => 'นำเข้าข้อมูลผู้ใช้',
                'currentPage' => 'import-users',
                'localUsers' => $localUsers,
                'externalUsers' => $externalUsers,
                'uniqueExternalUsers' => $uniqueExternalUsers
            );
            
            // โหลด view
            $this->view('pages/import-users', $data);
            
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    
    // API สำหรับนำเข้าข้อมูล
    public function importUsersAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            
            try {
                $input = json_decode(file_get_contents('php://input'), true);
                $selectedUsers = isset($input['users']) ? $input['users'] : array();
                
                if (empty($selectedUsers)) {
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'ไม่มีข้อมูลที่เลือก'
                    ));
                    return;
                }
                
                $db = Database::getInstance();
                $importedCount = 0;
                $errors = array();
                
                foreach ($selectedUsers as $mcode) {
                    try {
                        // ดึงข้อมูลจาก ali_member
                        $sqlSelect = "SELECT 
                                        mcode,
                                        name_t,
                                        sv_code,
                                        mobile,
                                        birthday,
                                        facebook_name,
                                        line_id
                                    FROM ali_member 
                                    WHERE mcode = :mcode 
                                    LIMIT 1";
                        
                        $stmtSelect = $db->prepare($sqlSelect);
                        $stmtSelect->execute(array(':mcode' => $mcode));
                        $user = $stmtSelect->fetch(PDO::FETCH_ASSOC);
                        
                        if (!$user) {
                            $errors[] = "ไม่พบข้อมูล mcode: {$mcode}";
                            continue;
                        }
                        
                        // เช็คว่ามีอยู่ใน me_users แล้วหรือยัง
                        $sqlCheck = "SELECT id FROM me_users WHERE code = :code OR username = :username LIMIT 1";
                        $stmtCheck = $db->prepare($sqlCheck);
                        $stmtCheck->execute(array(
                            ':code' => $user['mcode'],
                            ':username' => $user['mcode']
                        ));
                        
                        if ($stmtCheck->fetch()) {
                            $errors[] = "มีข้อมูลอยู่แล้ว: {$mcode}";
                            continue;
                        }
                        
                        // Insert เข้า me_users
                        $sqlInsert = "INSERT INTO me_users 
                                    (id, code, username, password, name, tel, birthday, facebook_name, line_id, role, created_by) 
                                    VALUES 
                                    (UUID(), :code, :username, :password, :name, :tel, :birthday, :facebook_name, :line_id, 'agent', 'import')";
                        
                        $stmtInsert = $db->prepare($sqlInsert);
                        $password = !empty($user['sv_code']) ? md5($user['sv_code']) : md5('123456');
                        
                        $success = $stmtInsert->execute(array(
                            ':code' => $user['mcode'],
                            ':username' => $user['mcode'],
                            ':password' => $password,
                            ':name' => $user['name_t'],
                            ':tel' => $user['mobile'],
                            ':birthday' => !empty($user['birthday']) ? $user['birthday'] : null,
                            ':facebook_name' => $user['facebook_name'],
                            ':line_id' => $user['line_id']
                        ));
                        
                        if ($success) {
                            $importedCount++;
                        }
                        
                    } catch (PDOException $e) {
                        $errors[] = "Error importing {$mcode}: " . $e->getMessage();
                    }
                }
                
                echo json_encode(array(
                    'success' => true,
                    'imported' => $importedCount,
                    'errors' => $errors,
                    'message' => "นำเข้าข้อมูลสำเร็จ {$importedCount} รายการ"
                ));
                
            } catch (Exception $e) {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
                ));
            }
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Invalid request method'
            ));
        }
    }
}