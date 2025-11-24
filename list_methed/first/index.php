<?php

// مصفوفات مثال للاستخدام
$fruits = ["Apple", "Banana", "Orange", "Mango"];
$numbers = [10, 5, 8, 3, 1, 7];
$user = [
    "name" => "أحمد",
    "age" => 25,
    "city" => "الرياض"
];

// 1. دوال إضافة وحذف العناصر
echo "=== دوال إضافة وحذف العناصر ===\n";

// array_push() - إضافة عناصر إلى نهاية المصفوفة
array_push($fruits, "Grapes", "Peach");
print_r($fruits);

// array_pop() - حذف آخر عنصر
$lastFruit = array_pop($fruits);
echo "العنصر المحذوف: $lastFruit\n";
print_r($fruits);

// array_unshift() - إضافة عناصر إلى البداية
array_unshift($fruits, "Strawberry", "Pineapple");
print_r($fruits);

// array_shift() - حذف أول عنصر
$firstFruit = array_shift($fruits);
echo "العنصر المحذوف: $firstFruit\n";
print_r($fruits);

// 2. دوال البحث والتصفية
echo "\n=== دوال البحث والتصفية ===\n";

// in_array() - البحث عن قيمة
$exists = in_array("Apple", $fruits);
echo "هل التفاح موجود؟ " . ($exists ? "نعم" : "لا") . "\n";

// array_search() - البحث عن قيمة وإرجاع المفتاح
$key = array_search("Banana", $fruits);
echo "مفتاح الموز: $key\n";

// array_filter() - تصفية المصفوفة
$evenNumbers = array_filter($numbers, function($n) { 
    return $n % 2 == 0; 
});
print_r($evenNumbers);

// array_keys() - إرجاع المفاتيح
$keys = array_keys($user);
print_r($keys);

// array_values() - إرجاع القيم
$values = array_values($user);
print_r($values);

// 3. دوال الترتيب والفرز
echo "\n=== دوال الترتيب والفرز ===\n";

// sort() - ترتيب تصاعدي
sort($numbers);
print_r($numbers);

// rsort() - ترتيب تنازلي
rsort($numbers);
print_r($numbers);

// asort() - ترتيب مع الحفاظ على المفاتيح
asort($user);
print_r($user);

// ksort() - ترتيب حسب المفاتيح
ksort($user);
print_r($user);

// array_reverse() - عكس الترتيب
$reversed = array_reverse($fruits);
print_r($reversed);

// 4. دوال الدمج والتقسيم
echo "\n=== دوال الدمج والتقسيم ===\n";

// array_merge() - دمج مصفوفات
$array1 = ['a', 'b'];
$array2 = ['c', 'd'];
$merged = array_merge($array1, $array2);
print_r($merged);

// array_combine() - إنشاء مصفوفة نقابية
$keys = ['name', 'age'];
$values = ['محمد', 30];
$combined = array_combine($keys, $values);
print_r($combined);

// array_slice() - أخذ جزء من المصفوفة
$slice = array_slice($fruits, 1, 2);
print_r($slice);

// array_splice() - إزالة واستبدال جزء
$tempFruits = $fruits;
array_splice($tempFruits, 1, 2, ['Peach', 'Pear']);
print_r($tempFruits);

// array_chunk() - تقسيم إلى أجزاء
$chunks = array_chunk($fruits, 2);
print_r($chunks);

// 5. دوال العد والحساب
echo "\n=== دوال العد والحساب ===\n";

// count() - عدد العناصر
$count = count($fruits);
echo "عدد العناصر: $count\n";

// array_sum() - جمع القيم
$sum = array_sum($numbers);
echo "مجموع الأرقام: $sum\n";

// array_product() - ضرب القيم
$product = array_product([2, 3, 4]);
echo "حاصل ضرب الأرقام: $product\n";

// array_unique() - إزالة المكررات
$duplicates = [1, 2, 2, 3, 4, 4, 5];
$unique = array_unique($duplicates);
print_r($unique);

// 6. دوال التنفيذ والتطبيق
echo "\n=== دوال التنفيذ والتطبيق ===\n";

// array_map() - تطبيق دالة على جميع العناصر
$lowercase = array_map('strtolower', $fruits);
print_r($lowercase);

// array_map() مع دالة مخصصة
$squared = array_map(function($n) { 
    return $n * $n; 
}, [1, 2, 3, 4]);
print_r($squared);

// array_walk() - تطبيق دالة مع إمكانية التعديل
$tempFruits2 = $fruits;
array_walk($tempFruits2, function(&$value, $key) { 
    $value = strtoupper($value); 
});
print_r($tempFruits2);

// array_reduce() - تقليل المصفوفة إلى قيمة واحدة
$total = array_reduce($numbers, function($carry, $item) { 
    return $carry + $item; 
}, 0);
echo "المجموع الكلي: $total\n";

// 7. دوال متقدمة
echo "\n=== دوال متقدمة ===\n";

// array_flip() - تبديل المفاتيح بالقيم
$flipped = array_flip($user);
print_r($flipped);

// array_intersect() - إيجاد التقاطع
$array1 = ['a', 'b', 'c'];
$array2 = ['b', 'c', 'd'];
$intersect = array_intersect($array1, $array2);
print_r($intersect);

// array_diff() - إيجاد الفرق
$diff = array_diff($array1, $array2);
print_r($diff);

// array_rand() - عنصر عشوائي
$randomKey = array_rand($fruits);
echo "العنصر العشوائي: $fruits[$randomKey]\n";

// shuffle() - خلط العناصر
$shuffled = $fruits;
shuffle($shuffled);
print_r($shuffled);

// 8. دوال إضافية مهمة
echo "\n=== دوال إضافية مهمة ===\n";

// array_column() - استخراج عمود من مصفوفة متعددة الأبعاد
$users = [
    ['id' => 1, 'name' => 'أحمد', 'age' => 25],
    ['id' => 2, 'name' => 'محمد', 'age' => 30],
    ['id' => 3, 'name' => 'علي', 'age' => 28]
];
$names = array_column($users, 'name');
print_r($names);

// array_fill() - ملء مصفوفة بقيم
$filled = array_fill(0, 5, 'قيمة');
print_r($filled);

// range() - إنشاء مصفوفة بنطاق من الأرقام
$range = range(1, 10);
print_r($range);

// array_replace() - استبدال عناصر المصفوفة
$base = ['a', 'b', 'c'];
$replacement = [0 => 'x', 2 => 'z'];
$replaced = array_replace($base, $replacement);
print_r($replaced);

// 9. دوال التحقق
echo "\n=== دوال التحقق ===\n";

// is_array() - التحقق إذا كان متغير مصفوفة
echo "هل fruits مصفوفة؟ " . (is_array($fruits) ? "نعم" : "لا") . "\n";

// array_key_exists() - التحقق من وجود مفتاح
echo "هل المفتاح 'name' موجود؟ " . (array_key_exists('name', $user) ? "نعم" : "لا") . "\n";

// in_array() - التحقق من وجود قيمة
echo "هل القيمة 'Apple' موجودة؟ " . (in_array('Apple', $fruits) ? "نعم" : "لا") . "\n";

// 10. أمثلة عملية
echo "\n=== أمثلة عملية ===\n";

// مثال: معالجة بيانات المنتجات
$products = [
    ['name' => 'لابتوب', 'price' => 2000, 'category' => 'إلكترونيات'],
    ['name' => 'هاتف', 'price' => 800, 'category' => 'إلكترونيات'],
    ['name' => 'كتاب', 'price' => 30, 'category' => 'أدوات'],
    ['name' => 'قلم', 'price' => 5, 'category' => 'أدوات']
];

// استخراج أسماء المنتجات
$productNames = array_column($products, 'name');
echo "أسماء المنتجات: " . implode(', ', $productNames) . "\n";

// تصفية المنتجات الإلكترونية
$electronicProducts = array_filter($products, function($product) {
    return $product['category'] === 'إلكترونيات';
});
echo "المنتجات الإلكترونية:\n";
print_r($electronicProducts);

// حساب إجمالي الأسعار
$totalPrice = array_reduce($products, function($carry, $product) {
    return $carry + $product['price'];
}, 0);
echo "إجمالي أسعار المنتجات: $totalPrice\n";

// تحويل أسماء المنتجات إلى أحرف كبيرة
$upperProducts = array_map(function($product) {
    $product['name'] = strtoupper($product['name']);
    return $product;
}, $products);
echo "المنتجات بأحرف كبيرة:\n";
print_r($upperProducts);

// مثال: إدارة قائمة المهام
$tasks = ["الدراسة", "التمرين", "القراءة", "الكتابة"];

// إضافة مهام جديدة
array_push($tasks, "التسوق", "الطهي");
echo "المهام بعد الإضافة: " . implode(' - ', $tasks) . "\n";

// إزالة أول مهمة
array_shift($tasks);
echo "المهام بعد إزالة الأولى: " . implode(' - ', $tasks) . "\n";

// عكس ترتيب المهام
$reversedTasks = array_reverse($tasks);
echo "المهام معكوسة: " . implode(' - ', $reversedTasks) . "\n";

// اختيار مهمة عشوائية
$randomTask = $tasks[array_rand($tasks)];
echo "المهمة العشوائية: $randomTask\n";

?>