<?php
require_once 'config.php';
$database = new Database();
$db = $database->getConnection();

header('Content-Type: application/json');

// التحقق من البيانات المرسلة
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction_id = $_POST['transaction_id'] ?? '';
    $action = $_POST['action'] ?? '';
    
    if(empty($transaction_id) || $action !== 'rollback') {
        echo json_encode([
            'success' => false,
            'message' => 'بيانات غير صالحة'
        ]);
        exit;
    }
    
    try {
        // بدء معاملة التراجع
        $db->beginTransaction();
        
        // 1. الحصول على بيانات التحويل
        $query = "SELECT * FROM transfers WHERE transaction_id = :transaction_id AND status = 'completed'";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':transaction_id', $transaction_id);
        $stmt->execute();
        $transfer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$transfer) {
            throw new Exception("التحويل غير موجود أو غير مكتمل");
        }
        
        // 2. التراجع عن التحويل (إرجاع الأموال)
        // إرجاع المبلغ للحساب المرسل
        $query = "UPDATE accounts SET balance = balance + :amount WHERE account_number = :from_account";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':amount', $transfer['amount']);
        $stmt->bindParam(':from_account', $transfer['from_account']);
        $stmt->execute();
        
        // خصم المبلغ من الحساب المستقبل
        $query = "UPDATE accounts SET balance = balance - :amount WHERE account_number = :to_account";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':amount', $transfer['amount']);
        $stmt->bindParam(':to_account', $transfer['to_account']);
        $stmt->execute();
        
        // 3. تحديث حالة التحويل
        $query = "UPDATE transfers SET status = 'rolled_back', updated_at = NOW() WHERE transaction_id = :transaction_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':transaction_id', $transaction_id);
        $stmt->execute();
        
        // 4. تسجيل عملية التراجع
        $query = "INSERT INTO system_logs (log_type, message, transaction_id) 
                  VALUES ('warning', 'تم التراجع عن التحويل: {$transfer['amount']} من {$transfer['from_account']} إلى {$transfer['to_account']}', :transaction_id)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':transaction_id', $transaction_id);
        $stmt->execute();
        
        // تأكيد المعاملة
        $db->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'تم التراجع عن التحويل بنجاح وإرجاع الأموال'
        ]);
        
    } catch (Exception $e) {
        // التراجع عن عملية التراجع
        $db->rollBack();
        
        // تسجيل الخطأ
        $database->logError("فشل التراجع: " . $e->getMessage(), $transaction_id);
        
        echo json_encode([
            'success' => false,
            'message' => 'فشل عملية التراجع: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'طريقة طلب غير صالحة'
    ]);
}
?>
