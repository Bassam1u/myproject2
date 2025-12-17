<?php
// storage_engines_comparison.php

echo "<!DOCTYPE html>";
echo "<html lang='ar'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>MyISAM vs InnoDB</title>";

echo "<style>
        body {
            font-family: Tahoma, Arial;
            direction: rtl;
            background-color: #f4f4f4;
            padding: 20px;
            line-height: 1.8;
        }
        h1, h2 {
            text-align: center;
        }
        .section {
            width: 80%;
            margin: auto;
            background-color: #ffffff;
            padding: 15px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
        ul {
            margin-right: 20px;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background-color: #fff;
        }
        th, td {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #ddd;
        }
      </style>";

echo "</head>";
echo "<body>";

echo "<h1>محركات التخزين MyISAM و InnoDB</h1>";

/* ===== MyISAM ===== */
echo "<div class='section'>";
echo "<h2>أولاً: MyISAM</h2>";

echo "<p>
<strong>التعريف:</strong><br>
MyISAM هو أحد محركات التخزين في MySQL، وكان المحرك الافتراضي في الإصدارات القديمة.
يعتمد على بنية بسيطة وسريع في عمليات القراءة، لكنه يفتقر إلى ميزات الأمان المتقدمة.
</p>";

echo "<strong>المميزات:</strong>";
echo "<ul>";
echo "<li>سرعة عالية في عمليات القراءة (SELECT).</li>";
echo "<li>بنية بسيطة وسهولة في الإدارة.</li>";
echo "</ul>";

echo "<strong>العيوب:</strong>";
echo "<ul>";
echo "<li>لا يدعم المعاملات (Transactions).</li>";
echo "<li>لا يدعم المفاتيح الأجنبية (Foreign Keys).</li>";
echo "</ul>";
echo "</div>";

/* ===== InnoDB ===== */
echo "<div class='section'>";
echo "<h2>ثانياً: InnoDB</h2>";

echo "<p>
<strong>التعريف:</strong><br>
InnoDB هو محرك التخزين الافتراضي في MySQL حالياً، ويتميز بدرجة عالية من الأمان
ودعم المعاملات والعلاقات بين الجداول، مما يجعله مناسباً للأنظمة الكبيرة.
</p>";

echo "<strong>المميزات:</strong>";
echo "<ul>";
echo "<li>يدعم المعاملات (ACID Transactions).</li>";
echo "<li>يدعم المفاتيح الأجنبية ويحافظ على تكامل البيانات.</li>";
echo "</ul>";

echo "<strong>العيوب:</strong>";
echo "<ul>";
echo "<li>استهلاك أعلى للذاكرة مقارنة بـ MyISAM.</li>";
echo "<li>أبطأ قليلاً في عمليات القراءة فقط.</li>";
echo "</ul>";
echo "</div>";

/* ===== Comparison ===== */
echo "<div class='section'>";
echo "<h2>ثالثاً: المقارنة بين MyISAM و InnoDB</h2>";
echo "</div>";

echo "<table>";
echo "<tr>
        <th>الخاصية</th>
        <th>MyISAM</th>
        <th>InnoDB</th>
      </tr>";

$comparison = [
    ["دعم المعاملات", "لا يدعم", "يدعم"],
    ["المفاتيح الأجنبية", "لا يدعم", "يدعم"],
    ["نوع القفل", "قفل الجدول", "قفل الصف"],
    ["الأمان", "منخفض", "عالٍ"],
    ["الاسترجاع بعد التعطل", "غير مدعوم", "مدعوم"],
    ["الاستخدام المناسب", "مشاريع بسيطة", "أنظمة كبيرة"]
];

foreach ($comparison as $row) {
    echo "<tr>";
    echo "<td>{$row[0]}</td>";
    echo "<td>{$row[1]}</td>";
    echo "<td>{$row[2]}</td>";
    echo "</tr>";
}

echo "</table>";

echo "</body>";
echo "</html>";
?>
