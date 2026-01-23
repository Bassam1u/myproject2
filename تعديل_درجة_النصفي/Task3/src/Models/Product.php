<?php
namespace Store\Models;

use Store\Traits\LoggerTrait;

class Product {
    use LoggerTrait; // 1. استخدام Trait لتسجيل الأحداث

    private $name;
    private $price;
    private $quantity;
    private $discount = 0;

    public function __construct($name, $price, $quantity) {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->logEvent("تم إنشاء المنتج: $name");
    }

    // 7. حماية البيانات من التعديل المباشر
    public function setPrice($price) {
        $oldPrice = $this->price;
        $this->price = $price;
        $this->logEvent("تغيير السعر من $oldPrice إلى $price");
    }

    public function setDiscount($discount) {
        $this->discount = $discount;
        $this->logEvent("تم تعيين خصم بقيمة: $discount%");
    }

    public function getFinalPrice() {
        return $this->price - ($this->price * ($this->discount / 100));
    }

    // 6. دالة سحرية
    public function __get($prop) {
        if (property_exists($this, $prop)) {
            return $this->$prop;
        }
    }

    public function getName() { return $this->name; }
}
