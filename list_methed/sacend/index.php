<?php
/**
 * تطبيق عملي للتدرب على دوال المصفوفات
 */

echo "<h1>💪 تطبيق عملي على دوال المصفوفات</h1>";

// تمرين 1: إدارة قائمة المهام
echo "<div class='section'>";
echo "<h2>تمرين 1: إدارة قائمة المهام</h2>";

$tasks = ["دراسة PHP", "كتابة تقرير", "مراجعة المشروع"];

// إضافة مهام جديدة
array_push($tasks, "اجتماع مع الفريق", "اختبار التطبيق");
echo "<strong>بعد الإضافة: </strong>" . implode(" | ", $tasks) . "<br>";

// إزالة أول مهمة
array_shift($tasks);
echo "<strong>بعد إزالة الأولى: </strong>" . implode(" | ", $tasks) . "<br>";

// البحث عن مهمة
$taskKey = array_search("كتابة تقرير", $tasks);
echo "<strong>موقع 'كتابة تقرير': </strong>المؤشر $taskKey<br>";

// تحويل جميع المهام إلى أحرف كبيرة
$upperTasks = array_map('strtoupper', $tasks);
echo "<strong>المهام بأحرف كبيرة: </strong>" . implode(" | ", $upperTasks);
echo "</div>";

// تمرين 2: تحليل نتائج الطلاب
echo "<div class='section'>";
echo "<h2>تمرين 2: تحليل نتائج الطلاب</h2>";

$students = [
    "أحمد" => 85,
    "محمد" => 92,
    "علي" => 78,
    "خالد" => 96,
    "فايز" => 88
];

// ترتيب الطلاب حسب الدرجات
arsort($students);
echo "<strong>الطلاب مرتبون حسب الدرجات: </strong><br>";
foreach($students as $name => $grade) {
    echo "$name: $grade<br>";
}

// حساب المتوسط
$average = array_sum($students) / count($students);
echo "<strong>متوسط الدرجات: </strong>" . number_format($average, 2) . "<br>";

// الطلاب فوق المتوسط
$aboveAverage = array_filter($students, function($grade) use ($average) {
    return $grade > $average;
});
echo "<strong>الطلاب فوق المتوسط: </strong>" . implode(", ", array_keys($aboveAverage));
echo "</div>";

// تمرين 3: معالجة النصوص
echo "<div class='section'>";
echo "<h2>تمرين 3: معالجة النصوص</h2>";

$text = "PHP هي لغة برمجة قوية وسهلة التعلم";
$words = explode(" ", $text);

echo "<strong>النص الأصلي: </strong>$text<br>";
echo "<strong>عدد الكلمات: </strong>" . count($words) . "<br>";

// عكس ترتيب الكلمات
$reversedWords = array_reverse($words);
echo "<strong>الكلمات معكوسة: </strong>" . implode(" ", $reversedWords) . "<br>";

// تحويل الكلمات إلى أحرف صغيرة
$lowerWords = array_map('strtolower', $words);
echo "<strong>الكلمات بأحرف صغيرة: </strong>" . implode(" ", $lowerWords) . "<br>";

// الحصول على كلمات فريدة من نص آخر
$text2 = "PHP هي لغة برمجة شائعة الاستخدام";
$words2 = explode(" ", $text2);
$uniqueWords = array_unique(array_merge($words, $words2));
echo "<strong>الكلمات الفريدة: </strong>" . implode(", ", $uniqueWords);
echo "</div>";

?>