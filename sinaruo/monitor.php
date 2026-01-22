<?php
require_once 'config.php';
$database = new Database();
$db = $database->getConnection();

// صفحة للمراقبة والإدارة
session_start();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مراقبة النظام</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; background-color: #f5f5f5; }
        .metric-card { margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .alert { direction: ltr; font-family: monospace; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">مراقبة نظام التحويلات</h1>
        
        <?php
        try {
            // إحصائيات النظام
            $stats = [];
            
            // عدد التحويلات
            $query = "SELECT status, COUNT(*) as count FROM transfers GROUP BY status";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $transfer_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // إجمالي المبالغ المحولة
            $query = "SELECT 
                      SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as total_completed,
                      SUM(CASE WHEN status = 'failed' THEN amount ELSE 0 END) as total_failed,
                      SUM(CASE WHEN status = 'rolled_back' THEN amount ELSE 0 END) as total_rolled_back
                      FROM transfers";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $amount_stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // سجلات الأخطاء
            $query = "SELECT * FROM system_logs WHERE log_type IN ('error', 'critical') ORDER BY created_at DESC LIMIT 10";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $error_logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // نقاط الاستعادة
            $query = "SELECT * FROM rollback_points ORDER BY created_at DESC LIMIT 5";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $rollback_points = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        
        <div class="row">
            <!-- بطاقات المقاييس -->
            <?php foreach($transfer_stats as $stat): ?>
                <div class="col-md-3">
                    <div class="card metric-card">
                        <div class="card-body text-center">
                            <h5><?php echo $stat['count']; ?></h5>
                            <p class="text-muted"><?php echo $stat['status']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- المبالغ الإجمالية -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5><?php echo number_format($amount_stats['total_completed'] ?? 0, 2); ?> <?php echo CURRENCY; ?></h5>
                        <p>إجمالي المبالغ المكتملة</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5><?php echo number_format($amount_stats['total_failed'] ?? 0, 2); ?> <?php echo CURRENCY; ?></h5>
                        <p>إجمالي المبالغ الفاشلة</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5><?php echo number_format($amount_stats['total_rolled_back'] ?? 0, 2); ?> <?php echo CURRENCY; ?></h5>
                        <p>إجمالي المبالغ المرتجعة</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- سجلات الأخطاء -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h4>سجلات الأخطاء والإنذارات</h4>
            </div>
            <div class="card-body">
                <?php if(!empty($error_logs)): ?>
                    <?php foreach($error_logs as $log): ?>
                        <div class="alert alert-danger">
                            <strong>[<?php echo $log['created_at']; ?>]</strong>
                            <?php echo htmlspecialchars($log['message']); ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-success">لا توجد أخطاء في السجلات</div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- نقاط الاستعادة -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4>نقاط الاستعادة</h4>
            </div>
            <div class="card-body">
                <?php if(!empty($rollback_points)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>التاريخ</th>
                                <th>البيانات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($rollback_points as $point): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($point['point_name']); ?></td>
                                    <td><?php echo $point['created_at']; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info" onclick="showSnapshot(<?php echo htmlspecialchars($point['data_snapshot']); ?>)">
                                            عرض البيانات
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">لا توجد نقاط استعادة</div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php } catch(Exception $e) { ?>
            <div class="alert alert-danger">خطأ في تحميل بيانات المراقبة: <?php echo $e->getMessage(); ?></div>
        <?php } ?>
    </div>
    
    <script>
        function showSnapshot(snapshot) {
            alert('بيانات النقطة:\n' + JSON.stringify(snapshot, null, 2));
        }
    </script>
</body>
</html>
