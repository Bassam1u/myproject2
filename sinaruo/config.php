<?php
// config.php - ملف إعدادات الاتصال بقاعدة البيانات

class Database {
    private $host = "localhost";
    private $db_name = "critical_scenarios";
    private $username = "root";
    private $password = "";
    public $conn;
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
            // إعدادات إضافية
            $this->conn->exec("SET time_zone = '+03:00'");
            
        } catch(PDOException $exception) {
            error_log("فشل الاتصال بقاعدة البيانات: " . $exception->getMessage());
            throw new Exception("تعذر الاتصال بقاعدة البيانات. الرجاء المحاولة لاحقاً.");
        }
        
        return $this->conn;
    }
    
    public function logError($message, $transaction_id = null) {
        $logFile = __DIR__ . '/logs/system_errors.log';
        $logMessage = date('Y-m-d H:i:s') . " | Transaction: " . ($transaction_id ?? 'N/A') . " | " . $message . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}

// بدء الجلسة
session_start();

// إعدادات الموقع
define('SITE_NAME', 'نظام إدارة السيناريوهات الحرجة');
define('MAX_TRANSFER_AMOUNT', 10000);
define('CURRENCY', 'ريال سعودي');

?>
