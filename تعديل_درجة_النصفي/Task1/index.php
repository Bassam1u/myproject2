<?php
require_once 'Product.php';
require_once 'Customer.php';
require_once 'Order.php';

// إنشاء بيانات تجريبية
$product1 = new Product("هاتف ذكي", 1200, 10);
$product1->setDiscount(10); // خصم 10%

$product2 = new Product("سماعات لاسلكية", 150, 20);

$customer = new Customer("أحمد محمد", "ahmed@example.com", "2023-01-15");

$order = new Order("ORD-1001");
$order->addItem($product1);
$order->addItem($product2);

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>شاشة نظام المتجر</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1, h2 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .card { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px; }
        .price { font-weight: bold; color: #27ae60; }
        .old-price { text-decoration: line-through; color: #e74c3c; font-size: 0.9em; }
        .badge { background: #3498db; color: white; padding: 3px 8px; border-radius: 3px; font-size: 0.8em; }
    </style>
</head>
<body>

<div class="container">
    <h1>نظام إدارة المتجر الإلكتروني</h1>

    <div class="card">
        <h2>بيانات العميل</h2>
        <p><strong>الاسم:</strong> <?php echo $customer->getName(); ?></p>
        <p><strong>البريد:</strong> <?php echo $customer->getEmail(); ?></p>
        <p><strong>عمر العضوية:</strong> <?php echo $customer->getMembershipDuration(); ?></p>
    </div>

    <div class="card">
        <h2>تفاصيل الطلب (#<?php echo $order->getOrderNumber(); ?>)</h2>
        <p><strong>الحالة:</strong> <span class="badge"><?php echo $order->getStatus(); ?></span></p>
        <p><strong>التاريخ:</strong> <?php echo $order->getDate(); ?></p>
        
        <h3>المنتجات:</h3>
        <ul>
            <?php foreach ($order->getItems() as $item): ?>
                <li>
                    <?php echo $item->getName(); ?> - 
                    <span class="price"><?php echo $item->getPriceAfterDiscount(); ?> ريال</span>
                    <?php if ($item->getPrice() != $item->getPriceAfterDiscount()): ?>
                        <span class="old-price">(<?php echo $item->getPrice(); ?> ريال)</span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <hr>
        <p><strong>الإجمالي النهائي:</strong> <span class="price"><?php echo $order->calculateTotal(); ?> ريال</span></p>
    </div>
</div>

</body>
</html>
