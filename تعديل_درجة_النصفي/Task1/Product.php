<?php

/**
 * صنف المنتج (Product)
 * يمثل المنتجات في المتجر مع حماية البيانات وحساب الخصم.
 */
class Product {
    private $name;
    private $price;
    private $quantity;
    private $discountPercent = 0; // نسبة الخصم الافتراضية

    // 3. استخدام البناء (Constructor) لتعيين القيم عند إنشاء الكائن
    public function __construct($name, $price, $quantity) {
        $this->name = $name;
        $this->setPrice($price); // استخدام الدالة لضمان الحماية
        $this->quantity = $quantity;
    }

    // 5. حماية البيانات من التعديل المباشر باستخدام الـ Setters
    public function setPrice($price) {
        if ($price >= 0) {
            $this->price = $price;
        }
    }

    public function setDiscount($percent) {
        $this->discountPercent = $percent;
    }

    // حساب السعر بعد الخصم
    public function getPriceAfterDiscount() {
        return $this->price - ($this->price * ($this->discountPercent / 100));
    }

    // 4. دالة سحرية لعرض معلومات المنتج بنص بسيط
    public function __toString() {
        return "المنتج: {$this->name} | السعر: {$this->price} | الكمية: {$this->quantity}";
    }

    // Getters للوصول للبيانات (Private properties)
    public function getName() { return $this->name; }
    public function getPrice() { return $this->price; }
    public function getQuantity() { return $this->quantity; }
}
