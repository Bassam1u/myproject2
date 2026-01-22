<?php
require_once 'config.php';
$database = new Database();
$db = $database->getConnection();

// الحصول على قائمة الحسابات
$accounts = [];
try {
    $query = "SELECT * FROM accounts ORDER BY account_name";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "خطأ في جلب بيانات الحسابات: " . $e->getMessage();
}

// الحصول على آخر التحويلات
$transfers = [];
try {
    $query = "SELECT t.*, 
              a1.account_name as from_name,
              a2.account_name as to_name
              FROM transfers t
              LEFT JOIN accounts a1 ON t.from_account = a1.account_number
              LEFT JOIN accounts a2 ON t.to_account = a2.account_number
              ORDER BY t.created_at DESC LIMIT 10";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $transfers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "خطأ في جلب سجل التحويلات: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام التحويلات المالية - إدارة السيناريوهات الحرجة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .main-container {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            margin-top: 30px;
            margin-bottom: 30px;
            padding: 30px;
        }
        
        .header-section {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        
        .account-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        
        .account-card:hover {
            transform: translateY(-5px);
        }
        
        .account-card.primary {
            border-top: 5px solid var(--primary-color);
        }
        
        .account-card.success {
            border-top: 5px solid var(--success-color);
        }
        
        .transfer-form {
            background-color: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status-rolled_back {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .logs-section {
            background-color: #2c3e50;
            color: white;
            border-radius: 15px;
            padding: 20px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .log-item {
            padding: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            font-family: monospace;
        }
        
        .transaction-id {
            font-family: monospace;
            background-color: #f8f9fa;
            padding: 2px 8px;
            border-radius: 5px;
        }
        
        @media (max-width: 768px) {
            .main-container {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-container">
            <!-- رأس الصفحة -->
            <div class="header-section text-center">
                <h1><i class="fas fa-shield-alt"></i> نظام إدارة السيناريوهات الحرجة</h1>
                <p class="lead">نظام آمن للتحويلات المالية مع إمكانية التراجع والمراقبة</p>
            </div>
            
            <div class="row">
                <!-- قسم الحسابات -->
                <div class="col-lg-4 mb-4">
                    <div class="card account-card primary">
                        <div class="card-header bg-primary text-white">
                            <h4><i class="fas fa-university"></i> الحسابات المتاحة</h4>
                        </div>
                        <div class="card-body">
                            <?php if(!empty($accounts)): ?>
                                <?php foreach($accounts as $account): ?>
                                    <div class="account-item mb-3 p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-1"><?php echo htmlspecialchars($account['account_name']); ?></h5>
                                                <small class="text-muted">رقم الحساب: <?php echo $account['account_number']; ?></small>
                                            </div>
                                            <div class="text-end">
                                                <h4 class="text-success"><?php echo number_format($account['balance'], 2); ?> <small><?php echo CURRENCY; ?></small></h4>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-warning">لا توجد حسابات متاحة</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- سجلات النظام -->
                    <div class="card account-card mt-4">
                        <div class="card-header bg-dark text-white">
                            <h4><i class="fas fa-clipboard-list"></i> سجلات النظام</h4>
                        </div>
                        <div class="logs-section">
                            <?php
                            try {
                                $query = "SELECT * FROM system_logs ORDER BY created_at DESC LIMIT 10";
                                $stmt = $db->prepare($query);
                                $stmt->execute();
                                $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                
                                foreach($logs as $log):
                            ?>
                                    <div class="log-item">
                                        <small>
                                            [<?php echo $log['created_at']; ?>] 
                                            <span class="badge bg-<?php echo $log['log_type'] == 'error' ? 'danger' : ($log['log_type'] == 'warning' ? 'warning' : 'info'); ?>">
                                                <?php echo strtoupper($log['log_type']); ?>
                                            </span>
                                            <?php echo htmlspecialchars(substr($log['message'], 0, 100)); ?>...
                                        </small>
                                    </div>
                            <?php
                                endforeach;
                            } catch(PDOException $e) {
                                echo '<div class="text-warning">تعذر تحميل السجلات</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- قسم التحويلات -->
                <div class="col-lg-8">
                    <!-- نموذج التحويل -->
                    <div class="transfer-form mb-4">
                        <h3 class="mb-4"><i class="fas fa-exchange-alt"></i> تحويل مالي جديد</h3>
                        
                        <?php if(isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo $_SESSION['success_message']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['success_message']); ?>
                        <?php endif; ?>
                        
                        <?php if(isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $_SESSION['error_message']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['error_message']); ?>
                        <?php endif; ?>
                        
                        <form action="process_transfer.php" method="POST" id="transferForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="from_account" class="form-label">الحساب المرسل</label>
                                    <select class="form-select" id="from_account" name="from_account" required>
                                        <option value="">اختر الحساب المرسل</option>
                                        <?php foreach($accounts as $account): ?>
                                            <option value="<?php echo $account['account_number']; ?>">
                                                <?php echo $account['account_name']; ?> (رصيد: <?php echo number_format($account['balance'], 2); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="to_account" class="form-label">الحساب المستقبل</label>
                                    <select class="form-select" id="to_account" name="to_account" required>
                                        <option value="">اختر الحساب المستقبل</option>
                                        <?php foreach($accounts as $account): ?>
                                            <option value="<?php echo $account['account_number']; ?>">
                                                <?php echo $account['account_name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="amount" class="form-label">المبلغ (<?php echo CURRENCY; ?>)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="amount" name="amount" 
                                           step="0.01" min="1" max="<?php echo MAX_TRANSFER_AMOUNT; ?>" 
                                           placeholder="أدخل المبلغ" required>
                                    <span class="input-group-text"><?php echo CURRENCY; ?></span>
                                </div>
                                <div class="form-text">الحد الأقصى للتحويل: <?php echo number_format(MAX_TRANSFER_AMOUNT, 2); ?> <?php echo CURRENCY; ?></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">وصف التحويل (اختياري)</label>
                                <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-secondary me-md-2" onclick="simulateFailure()">
                                    <i class="fas fa-bug"></i> محاكاة فشل
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane"></i> إجراء التحويل
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- سجل التحويلات -->
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h4><i class="fas fa-history"></i> سجل التحويلات الأخيرة</h4>
                        </div>
                        <div class="card-body">
                            <?php if(!empty($transfers)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>رقم العملية</th>
                                                <th>من</th>
                                                <th>إلى</th>
                                                <th>المبلغ</th>
                                                <th>الحالة</th>
                                                <th>التاريخ</th>
                                                <th>إجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($transfers as $transfer): ?>
                                                <tr>
                                                    <td><span class="transaction-id"><?php echo substr($transfer['transaction_id'] ?? 'N/A', 0, 8); ?></span></td>
                                                    <td><?php echo htmlspecialchars($transfer['from_name'] ?? $transfer['from_account']); ?></td>
                                                    <td><?php echo htmlspecialchars($transfer['to_name'] ?? $transfer['to_account']); ?></td>
                                                    <td class="fw-bold"><?php echo number_format($transfer['amount'], 2); ?></td>
                                                    <td>
                                                        <span class="status-badge status-<?php echo $transfer['status']; ?>">
                                                            <?php 
                                                            $status_text = [
                                                                'completed' => 'مكتمل',
                                                                'failed' => 'فشل',
                                                                'rolled_back' => 'تراجع',
                                                                'pending' => 'قيد الانتظار'
                                                            ];
                                                            echo $status_text[$transfer['status']] ?? $transfer['status'];
                                                            ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo date('Y-m-d H:i', strtotime($transfer['created_at'])); ?></td>
                                                    <td>
                                                        <?php if($transfer['status'] == 'completed'): ?>
                                                            <button class="btn btn-sm btn-warning" onclick="rollbackTransfer('<?php echo $transfer['transaction_id']; ?>')">
                                                                <i class="fas fa-undo"></i> تراجع
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">لا توجد تحويلات سابقة</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- قسم المعلومات التعليمية -->
                    <div class="card mt-4">
                        <div class="card-header bg-warning text-dark">
                            <h4><i class="fas fa-lightbulb"></i> كيف نتعامل مع السيناريوهات الحرجة؟</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5><i class="fas fa-sync-alt text-primary"></i> نظام التراجع (Rollback)</h5>
                                    <p>يضمن النظام إمكانية التراجع عن التحويلات في حالة اكتشاف خطأ أو عملية مشبوهة.</p>
                                </div>
                                <div class="col-md-6">
                                    <h5><i class="fas fa-clipboard-check text-success"></i> المعاملات الآمنة</h5>
                                    <p>جميع التحويلات تتم ضمن معاملات (Transactions) تضمن نجاح أو فشل العملية بالكامل.</p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5><i class="fas fa-file-alt text-danger"></i> السجلات التفصيلية</h5>
                                    <p>يتم تسجيل كل خطوة في النظام لتسهيل عملية التتبع والتدقيق.</p>
                                </div>
                                <div class="col-md-6">
                                    <h5><i class="fas fa-shield-alt text-info"></i> الحماية من الفشل</h5>
                                    <p>النظام مصمم للتعامل مع حالات الفشل المفاجئة دون فقدان البيانات.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- تذييل الصفحة -->
            <footer class="mt-5 pt-4 border-top text-center text-muted">
                <p>نظام إدارة السيناريوهات الحرجة &copy; <?php echo date('Y'); ?> - تم التطوير لغرض تعليمي</p>
                <small>جميع الحقوق محفوظة - مشروع مادة تطوير تطبيقات الويب</small>
            </footer>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function simulateFailure() {
            if(confirm('هل تريد محاكاة فشل في النظام؟ (هذا لغرض الاختبار فقط)')) {
                document.getElementById('amount').value = '99999';
                document.getElementById('from_account').value = 'ACC001';
                document.getElementById('to_account').value = 'ACC001';
                document.getElementById('transferForm').submit();
            }
        }
        
        function rollbackTransfer(transactionId) {
            if(confirm('هل أنت متأكد من تراجع عن هذه العملية؟')) {
                $.post('rollback.php', {
                    transaction_id: transactionId,
                    action: 'rollback'
                }, function(response) {
                    alert(response.message);
                    if(response.success) {
                        location.reload();
                    }
                }, 'json');
            }
        }
        
        // تحديث الرصيد عند تغيير الحساب المرسل
        $('#from_account').change(function() {
            let selectedOption = $(this).find('option:selected');
            let balanceText = selectedOption.text().match(/رصيد: ([\d,.]+)/);
            if(balanceText) {
                let balance = parseFloat(balanceText[1].replace(/,/g, ''));
                $('#amount').attr('max', balance);
                $('.form-text').html('الحد الأقصى للتحويل: ' + balance.toFixed(2) + ' <?php echo CURRENCY; ?>');
            }
        });
        
        // منع اختيار نفس الحساب مرسل ومستقبل
        $('#from_account, #to_account').change(function() {
            let from = $('#from_account').val();
            let to = $('#to_account').val();
            
            if(from && to && from === to) {
                alert('لا يمكن اختيار نفس الحساب مرسل ومستقبل!');
                $('#to_account').val('');
            }
        });
    </script>
</body>
</html>
