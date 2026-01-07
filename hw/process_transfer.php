<?php
require_once 'config.php';
$database = new Database();
$db = $database->getConnection();

// بدء الجلسة
session_start();

// التحقق من البيانات المرسلة
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from_account = $_POST['from_account'] ?? '';
    $to_account = $_POST['to_account'] ?? '';
    $amount = floatval($_POST['amount'] ?? 0);
    $description = $_POST['description'] ?? '';
    
    // توليد رقم معاملة فريد
    $transaction_id = 'TXN_' . time() . '_' . uniqid();
    
    try {
        // بدء معاملة (Transaction)
        $db->beginTransaction();
        
        // 1. التحقق من صحة البيانات
        if(empty($from_account) || empty($to_account) || $amount <= 0) {
            throw new Exception("بيانات غير صالحة");
        }
        
        if($from_account === $to_account) {
            throw new Exception("لا يمكن التحويل لنفس الحساب");
        }
        
        if($amount > MAX_TRANSFER_AMOUNT) {
            throw new Exception("المبلغ يتجاوز الحد المسموح به");
        }
        
        // 2. التحقق من رصيد الحساب المرسل
        $query = "SELECT balance FROM accounts WHERE account_number = :from_account FOR UPDATE";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':from_account', $from_account);
        $stmt->execute();
        $sender = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$sender) {
            throw new Exception("الحساب المرسل غير موجود");
        }
        
        if($sender['balance'] < $amount) {
            throw new Exception("الرصيد غير كافي");
        }
        
        // 3. التحقق من وجود الحساب المستقبل
        $query = "SELECT account_number FROM accounts WHERE account_number = :to_account FOR UPDATE";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':to_account', $to_account);
        $stmt->execute();
        $receiver = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$receiver) {
            throw new Exception("الحساب المستقبل غير موجود");
        }
        
        // 4. محاكاة فشل النظام (لأغراض الاختبار)
        if($amount > 50000) {
            throw new Exception("فشل محاكاة: المبلغ كبير جداً يتسبب في خطأ في النظام");
        }
        
        // 5. تنفيذ التحويل
        // خصم من المرسل
        $query = "UPDATE accounts SET balance = balance - :amount WHERE account_number = :from_account";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':from_account', $from_account);
        $stmt->execute();
        
        // إضافة للمستقبل
        $query = "UPDATE accounts SET balance = balance + :amount WHERE account_number = :to_account";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':to_account', $to_account);
        $stmt->execute();
        
        // 6. تسجيل التحويل
        $query = "INSERT INTO transfers (from_account, to_account, amount, status, transaction_id, error_message) 
                  VALUES (:from_account, :to_account, :amount, 'completed', :transaction_id, '')";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':from_account', $from_account);
        $stmt->bindParam(':to_account', $to_account);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':transaction_id', $transaction_id);
        $stmt->execute();
        
        // 7. تسجيل في سجلات النظام
        $query = "INSERT INTO system_logs (log_type, message, transaction_id) 
                  VALUES ('info', 'تحويل مالي ناجح: $amount من $from_account إلى $to_account', :transaction_id)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':transaction_id', $transaction_id);
        $stmt->execute();
        
        // 8. حفظ نقطة استعادة
        $query = "INSERT INTO rollback_points (point_name, data_snapshot) 
                  VALUES (:point_name, :snapshot)";
        $stmt = $db->prepare($query);
        $point_name = "قبل التحويل $transaction_id";
        $snapshot = json_encode([
            'transaction_id' => $transaction_id,
            'from_account' => $from_account,
            'to_account' => $to_account,
            'amount' => $amount,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        $stmt->bindParam(':point_name', $point_name);
        $stmt->bindParam(':snapshot', $snapshot);
        $stmt->execute();
        
        // تأكيد المعاملة
        $db->commit();
        
        $_SESSION['success_message'] = "تم التحويل بنجاح! رقم العملية: $transaction_id";
        
    } catch (Exception $e) {
        // التراجع عن جميع العمليات
        $db->rollBack();
        
        // تسجيل التحويل الفاشل
        try {
            $query = "INSERT INTO transfers (from_account, to_account, amount, status, transaction_id, error_message) 
                      VALUES (:from_account, :to_account, :amount, 'failed', :transaction_id, :error)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':from_account', $from_account);
            $stmt->bindParam(':to_account', $to_account);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':transaction_id', $transaction_id);
            $error_msg = $e->getMessage();
            $stmt->bindParam(':error', $error_msg);
            $stmt->execute();
            
            // تسجيل الخطأ في سجلات النظام
            $query = "INSERT INTO system_logs (log_type, message, transaction_id) 
                      VALUES ('error', 'فشل تحويل مالي: " . $e->getMessage() . "', :transaction_id)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':transaction_id', $transaction_id);
            $stmt->execute();
            
            // تسجيل الخطأ في ملف النظام
            $database->logError($e->getMessage(), $transaction_id);
            
        } catch(Exception $log_error) {
            // في حالة فشل التسجيل أيضاً
            $database->logError("فشل في تسجيل الخطأ: " . $log_error->getMessage(), $transaction_id);
        }
        
        $_SESSION['error_message'] = "فشل التحويل: " . $e->getMessage();
    }
    
    // إعادة التوجيه للصفحة الرئيسية
    header('Location: index.php');
    exit;
} else {
    header('Location: index.php');
    exit;
}
?>