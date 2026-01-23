<?php
require_once 'Manager.php';
require_once 'ContractEmployee.php';

// إنشاء قائمة من الموظفين (تعدد الأشكال Polymorphism)
$employees = [
    new PermanentEmployee(1, "محمد العلي", 5000, 1000),
    new Manager(2, "سارة خالد", 8000, 2000, 1500),
    new ContractEmployee(3, "فهد السالم", 50, 160) // 50 ريال للساعة * 160 ساعة
];

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نظام إدارة الموظفين</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f0f2f5; padding: 20px; }
        .container { max-width: 900px; margin: auto; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        th, td { padding: 15px; text-align: right; border-bottom: 1px solid #ddd; }
        th { background-color: #2c3e50; color: white; }
        tr:hover { background-color: #f9f9f9; }
        .salary { color: #27ae60; font-weight: bold; }
        h1 { text-align: center; color: #2c3e50; }
    </style>
</head>
<body>

<div class="container">
    <h1>نظام إدارة رواتب الموظفين</h1>
    
    <table>
        <thead>
            <tr>
                <th>التفاصيل</th>
                <th>الراتب الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?php echo $employee->getDetails(); ?></td>
                    <td class="salary"><?php echo number_format($employee->calculateSalary(), 2); ?> ريال</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
