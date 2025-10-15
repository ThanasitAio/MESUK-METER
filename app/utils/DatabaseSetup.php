<?php
// app/utils/DatabaseSetup.php
class DatabaseSetup {
    private $db;
    
    public function __construct() {
        try {
            $this->db = Database::getInstance();
        } catch (Exception $e) {
            throw new Exception("Cannot connect to database: " . $e->getMessage());
        }
    }
    
    public function install() {
        try {
            $schemaFile = BASE_PATH . '/database/schema.sql';
            
            if (!file_exists($schemaFile)) {
                throw new Exception("Schema file not found at: " . $schemaFile);
            }
            
            $sql = file_get_contents($schemaFile);
            if ($sql === false) {
                throw new Exception("Cannot read schema file");
            }
            
            // แยก statements และแบ่งเป็น CREATE กับ INSERT
            $statements = $this->simpleSplitSQL($sql);
            $createStatements = array();
            $insertStatements = array();
            
            foreach ($statements as $statement) {
                if (strpos(strtoupper($statement), 'CREATE TABLE') === 0) {
                    $createStatements[] = $statement;
                } else if (strpos(strtoupper($statement), 'INSERT INTO') === 0) {
                    $insertStatements[] = $statement;
                }
            }
            
            $executed = 0;
            $errors = array();
            
            // Step 1: Execute CREATE TABLE statements
            error_log("Executing CREATE TABLE statements...");
            foreach ($createStatements as $index => $statement) {
                try {
                    error_log("Creating table: " . substr($statement, 0, 50));
                    $this->db->query($statement);
                    $executed++;
                    error_log("✓ Table created successfully");
                } catch (Exception $e) {
                    $errorMsg = "CREATE TABLE failed: " . $e->getMessage();
                    $errors[] = $errorMsg;
                    error_log("✗ " . $errorMsg);
                }
            }
            
            // Step 2: Execute INSERT statements (only if CREATE was successful)
            if (empty($errors)) {
                error_log("Executing INSERT statements...");
                foreach ($insertStatements as $index => $statement) {
                    try {
                        error_log("Inserting data: " . substr($statement, 0, 50));
                        $this->db->query($statement);
                        $executed++;
                        error_log("✓ Data inserted successfully");
                    } catch (Exception $e) {
                        $errorMsg = "INSERT failed: " . $e->getMessage();
                        $errors[] = $errorMsg;
                        error_log("✗ " . $errorMsg);
                    }
                }
            }
            
            if (!empty($errors)) {
                return array(
                    'success' => false,
                    'message' => 'Installation completed with errors. Executed: ' . $executed . ' statements. First error: ' . $errors[0]
                );
            }
            
            return array(
                'success' => true,
                'message' => 'Database installed successfully! Executed ' . $executed . ' statements.'
            );
            
        } catch (Exception $e) {
            error_log("Database Installation Error: " . $e->getMessage());
            
            return array(
                'success' => false,
                'message' => 'Installation failed: ' . $e->getMessage()
            );
        }
    }
    
    private function simpleSplitSQL($sql) {
        // แยกคำสั่ง SQL แบบง่ายๆ ด้วย ;
        $statements = explode(';', $sql);
        
        // กรองคำสั่งว่างและ trim
        $statements = array_map('trim', $statements);
        $statements = array_filter($statements, function($stmt) {
            return !empty($stmt) && 
                   strlen($stmt) > 10 && // เพิ่มความยาวขั้นต่ำ
                   strpos($stmt, '--') !== 0;
        });
        
        return array_values($statements);
    }
    
    // ... methods อื่นๆ เหมือนเดิม ...
}