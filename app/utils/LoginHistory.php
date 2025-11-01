<?php

/**
 * คลาสสำหรับจัดการประวัติการเข้าระบบ
 */
class LoginHistory {
    
    /**
     * บันทึกประวัติการ Login
     * 
     * @param string $userId - User ID
     * @param string $username - Username
     * @param string $status - 'success' หรือ 'failed'
     * @return bool
     */
    public static function record($userId, $username, $status = 'success') {
        try {
            $db = Database::getInstance();
            
            // ดึง IP Address
            $ipAddress = self::getClientIP();
            $datetime = date('Y-m-d H:i:s');
            // ดึง User Agent
            $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
            
            $sql = "INSERT INTO login_history (user_id, username, login_time, ip_address, user_agent, status) 
                    VALUES (:user_id, :username, '$datetime', :ip_address, :user_agent, :status)";
            
            $stmt = $db->prepare($sql);
            return $stmt->execute(array(
                ':user_id' => $userId,
                ':username' => $username,
                ':ip_address' => $ipAddress,
                ':user_agent' => $userAgent,
                ':status' => $status
            ));
        } catch (PDOException $e) {
            error_log("LoginHistory::record() Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * นับจำนวนผู้ใช้ที่ใช้งานใน N วันล่าสุด
     * 
     * @param int $days - จำนวนวันย้อนหลัง (default: 3)
     * @return int - จำนวนผู้ใช้ที่ไม่ซ้ำกัน
     */
    public static function getActiveUsersCount($days = 3) {
        try {
            $datetime = date('Y-m-d H:i:s');
            $db = Database::getInstance();
            
            $sql = "SELECT COUNT(DISTINCT user_id) as total 
                    FROM login_history 
                    WHERE status = 'success' 
                    AND login_time >= DATE_SUB('$datetime', INTERVAL :days DAY)";
            
            $stmt = $db->prepare($sql);
            $stmt->execute(array(':days' => $days));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? (int)$result['total'] : 0;
        } catch (PDOException $e) {
            error_log("LoginHistory::getActiveUsersCount() Error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * ดึงรายการผู้ใช้ที่ใช้งานใน N วันล่าสุด
     * 
     * @param int $days - จำนวนวันย้อนหลัง
     * @param int $limit - จำกัดจำนวนผลลัพธ์
     * @return array
     */
    public static function getActiveUsers($days = 3, $limit = 10) {
        try {
            $db = Database::getInstance();
            $datetime = date('Y-m-d H:i:s');
            $sql = "SELECT 
                        u.username, 
                        u.name, 
                        u.role,
                        MAX(h.login_time) as last_login,
                        COUNT(h.id) as login_count
                    FROM login_history h
                    JOIN me_users u ON h.user_id = u.id
                    WHERE h.status = 'success'
                    AND h.login_time >= DATE_SUB('$datetime', INTERVAL :days DAY)
                    GROUP BY u.id, u.username, u.name, u.role
                    ORDER BY last_login DESC
                    LIMIT :limit";
            
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':days', $days, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("LoginHistory::getActiveUsers() Error: " . $e->getMessage());
            return array();
        }
    }
    
    /**
     * ดึงประวัติ Login ของผู้ใช้คนหนึ่ง
     * 
     * @param string $userId - User ID
     * @param int $limit - จำกัดจำนวนผลลัพธ์
     * @return array
     */
    public static function getUserHistory($userId, $limit = 20) {
        try {
            $db = Database::getInstance();
            
            $sql = "SELECT * FROM login_history 
                    WHERE user_id = :user_id 
                    ORDER BY login_time DESC 
                    LIMIT :limit";
            
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("LoginHistory::getUserHistory() Error: " . $e->getMessage());
            return array();
        }
    }
    
    /**
     * ดึง IP Address ของ Client
     * 
     * @return string|null
     */
    private static function getClientIP() {
        $ipAddress = null;
        
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipAddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }
        
        return $ipAddress;
    }
    
    /**
     * ลบประวัติเก่าเกินกว่า N วัน
     * 
     * @param int $days - เก็บข้อมูลกี่วัน
     * @return int - จำนวนแถวที่ลบ
     */
    public static function cleanOldRecords($days = 90) {
        try {
            $db = Database::getInstance();
            $datetime = date('Y-m-d H:i:s');
            $sql = "DELETE FROM login_history 
                    WHERE login_time < DATE_SUB('$datetime', INTERVAL :days DAY)";

            $stmt = $db->prepare($sql);
            $stmt->execute(array(':days' => $days));
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("LoginHistory::cleanOldRecords() Error: " . $e->getMessage());
            return 0;
        }
    }
}
