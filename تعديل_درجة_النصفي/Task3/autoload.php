<?php

/**
 * 3. تطبيق Autoloading
 * يقوم بتحميل الملفات تلقائياً بناءً على الـ Namespace
 */
spl_autoload_register(function ($class) {
    // نحدد البادئة (Namespace Prefix)
    $prefix = 'Store\\';
    // المجلد الأساسي للملفات
    $base_dir = __DIR__ . '/src/';

    // هل الفئة المطلوبة تستخدم نفس البادئة؟
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // الحصول على اسم الفئة النسبي
    $relative_class = substr($class, $len);

    // تحويل الـ Namespace إلى مسار ملف
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // إذا كان الملف موجوداً، قم بتحميله
    if (file_exists($file)) {
        require $file;
    }
});
