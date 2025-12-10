<?php

//تغيير المنطقة الزمنية الى عدن بدل جيرنتش
date_default_timezone_set("Asia/Aden");
echo "The time is " . date("Y/m/d   h:i:sa");
echo "<br>";


//التعامل مع دوال الوقت والتاريخ 

// تعيين النطقة الزمنية 
date_default_timezone_set("Asia/Aden");
//الحصول على الوقت والتاريخ التنسيقات 
echo date("Y-m-d"); // 2025-12-10
echo "<br>";
echo date("d/m/Y"); // 10/12/2025
echo "<br>";
echo date("Y-m-d H:i:s"); // 2025-12-10 15:31:35
echo "<br>";
echo date("l, F j, Y"); // Wednesday, December 10, 2025
echo "<br>";
echo date("h:i A"); // 03:31 PM
echo "<br>";
echo date("D, d M Y"); // Wed, 10 Dec 2025
echo "<br>";
echo date("l jS \of F Y"); // Wednesday 10th of December 2025
echo "<br>";
echo date("Y-m-d\TH:i:sP"); // 2025-12-10T15:31:35+03:00
echo "<br>";
// التنسيق باللغة العربية
setlocale(LC_TIME, 'ar_SA.UTF-8');
echo strftime("%A %d %B %Y"); // Wednesday 10 December 2025 الناتج خرج الانجليزي لى الرغم اني مخلية عربي 
echo "<br>";
// من نص ال تاريخ
// الطريقة الأولى: strtotime
$dateString="2025-12-10";
$timestamp1 = strtotime($dateString);
echo date("Y-m-d H:i:s", $timestamp1);//2025-12-10 00:00:00
echo "<br>";

//العمليات الحسابية على التواريخ
$today = date("Y-m-d");

// إضافة أيام
$tomorrow = date("Y-m-d", strtotime("+1 day"));
$nextWeek = date("Y-m-d", strtotime("+1 week"));
$nextMonth = date("Y-m-d", strtotime("+1 month"));
$nextYear = date("Y-m-d", strtotime("+1 year"));

// طرح أيام
$yesterday = date("Y-m-d", strtotime("-1 day"));
$lastWeek = date("Y-m-d", strtotime("-1 week"));

// تواريخ محددة
$nextMonday = date("Y-m-d", strtotime("next Monday"));
$lastFriday = date("Y-m-d", strtotime("last Friday"));
$firstDayNextMonth = date("Y-m-d", strtotime("first day of next month"));

//أيام الأسبوع والشهور
// الحصول على يوم الأسبوع
$dayOfWeek = date("w"); // 0 (الأحد) إلى 6 (السبت)
$dayName = date("l"); // Monday, Tuesday, etc.

// الحصول على رقم اليوم في الشهر
$dayOfMonth = date("j"); // 1 إلى 31

// الحصول على رقم الأسبوع في السنة
$weekOfYear = date("W");
//التحقق من الاريخ 
$isValid = checkdate(2, 30, 2024); // false
// للحصول على المنطق الزمنية 
echo date_default_timezone_get();
echo "<br>";
// التوقيت العالمي 
echo gmdate("Y-m-d H:i:s");
echo "<br>";

// وشكرا 
?>