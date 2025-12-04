<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تمرين PHP</title>
    <style>
        body {
            text-align: center;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .even {
            color: blue;
            font-weight: bold;
        }
        .odd {
            color: black;
        }
    </style>
</head>
<body>
    <h2>رسائل التهنئة للطلاب</h2>
    
    <?php
    $students = ["اسامة", "ايمن", "اسيد", "محمد", "بسام"];
    
    echo "<h3>الطلاب:</h3>";
    foreach ($students as $student) {
        echo "<p>مرحبًا $student نتمنى لك حظًا سعيدًا في الامتحان!</p>";
    }
    
    echo "<hr>";
    echo "<h3>الأعداد من 1 إلى 10:</h3>";
    

    for ($i = 1; $i <= 10; $i++) {
        if ($i % 2 == 0) {
            echo "<span class='even'>$i </span>";
        } else {
            echo "<span class='odd'>$i </span>";
        }
    }
    ?>
</body>
</html>