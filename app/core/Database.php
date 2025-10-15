<?php

/**
 * คลาสสำหรับจัดการการเชื่อมต่อและดำเนินการกับฐานข้อมูล (PDO)
 * ใช้รูปแบบ Singleton Pattern เพื่อให้แน่ใจว่ามีการเชื่อมต่อเกิดขึ้นเพียงครั้งเดียว
 * พร้อมด้วย Helper Methods สำหรับการทำงานแบบ CRUD
 */
class Database {

    /** @var PDO|null เก็บ instance ของ PDO connection */
    private static $pdo = null;

    /** @var PDOStatement|null เก็บ statement ล่าสุดที่ทำงาน */
    private static $stmt = null;

    private function __construct() {}
    private function __clone() {}

    /**
     * เมธอดหลักสำหรับเรียกใช้การเชื่อมต่อฐานข้อมูล
     * @return PDO
     */
    public static function getInstance() {
        if (self::$pdo === null) {
            try {
                $config = require __DIR__ . '/../../config/database.php';
                $connectionConfig = $config['connections']['mysql'];
                $dsn = "mysql:host={$connectionConfig['host']};port={$connectionConfig['port']};dbname={$connectionConfig['database']};charset={$connectionConfig['charset']}";

                self::$pdo = new PDO(
                    $dsn,
                    $connectionConfig['username'],
                    $connectionConfig['password'],
                    $connectionConfig['options']
                );
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    /**
     * เมธอดกลางสำหรับเตรียมและรันคำสั่ง SQL (SELECT, INSERT, UPDATE, DELETE)
     * @param string $sql คำสั่ง SQL พร้อม placeholders (เช่น :id)
     * @param array $params อาร์เรย์ของค่าที่จะ bind (เช่น ['id' => 1])
     * @return bool|PDOStatement คืนค่า PDOStatement object หรือ false ถ้าล้มเหลว
     */
    public static function query($sql, $params = []) {
        try {
            self::$stmt = self::getInstance()->prepare($sql);
            self::$stmt->execute($params);
            return self::$stmt;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    // =================================================================
    //  เมธอดสำหรับการดึงข้อมูล (Read)
    // =================================================================

    /**
     * ดึงข้อมูลทั้งหมด (หลายแถว) จากการ query
     * @param string $sql คำสั่ง SQL
     * @param array $params พารามิเตอร์
     * @return array ผลลัพธ์เป็น array ของ object
     */
    public static function fetchAll($sql, $params = []) {
        return self::query($sql, $params)->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * ดึงข้อมูลเพียงแถวเดียวจากการ query
     * @param string $sql คำสั่ง SQL
     * @param array $params พารามิเตอร์
     * @return mixed ผลลัพธ์เป็น object หรือ false ถ้าไม่พบข้อมูล
     */
    public static function fetch($sql, $params = []) {
        return self::query($sql, $params)->fetch(PDO::FETCH_OBJ);
    }

    // =================================================================
    //  เมธอดสำหรับการเพิ่มข้อมูล (Create)
    // =================================================================

    /**
     * เพิ่มข้อมูลลงในตาราง
     * @param string $table ชื่อตาราง
     * @param array $data ข้อมูลในรูปแบบ ['column' => 'value']
     * @return string ID ของแถวที่เพิ่มล่าสุด
     */
    public static function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        self::query($sql, $data);
        return self::getInstance()->lastInsertId();
    }

    // =================================================================
    //  เมธอดสำหรับการแก้ไขข้อมูล (Update)
    // =================================================================

    /**
     * อัปเดตข้อมูลในตาราง
     * @param string $table ชื่อตาราง
     * @param array $data ข้อมูลที่จะอัปเดต ['column' => 'value']
     * @param string $where เงื่อนไข (เช่น 'id = :id')
     * @param array $whereParams พารามิเตอร์สำหรับเงื่อนไข (เช่น ['id' => 1])
     * @return int จำนวนแถวที่ได้รับผลกระทบ
     */
    public static function update($table, $data, $where, $whereParams = []) {
        $setPart = [];
        foreach ($data as $key => $value) {
            $setPart[] = "{$key} = :{$key}";
        }
        $setPart = implode(', ', $setPart);
        $sql = "UPDATE {$table} SET {$setPart} WHERE {$where}";
        
        $params = array_merge($data, $whereParams);
        
        return self::query($sql, $params)->rowCount();
    }

    // =================================================================
    //  เมธอดสำหรับการลบข้อมูล (Delete)
    // =================================================================

    /**
     * ลบข้อมูลออกจากตาราง
     * @param string $table ชื่อตาราง
     * @param string $where เงื่อนไข (เช่น 'id = :id')
     * @param array $params พารามิเตอร์สำหรับเงื่อนไข (เช่น ['id' => 1])
     * @return int จำนวนแถวที่ได้รับผลกระทบ
     */
    public static function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return self::query($sql, $params)->rowCount();
    }

    // =================================================================
    //  เมธอดเสริม (Utilities)
    // =================================================================

    /**
     * นับจำนวนแถว
     * @param string $table ชื่อตาราง
     * @param string $where เงื่อนไข (ถ้ามี)
     * @param array $params พารามิเตอร์สำหรับเงื่อนไข (ถ้ามี)
     * @return int จำนวนแถว
     */
    public static function count($table, $where = "1", $params = []) {
        $sql = "SELECT COUNT(*) FROM {$table} WHERE {$where}";
        return (int) self::query($sql, $params)->fetchColumn();
    }
}