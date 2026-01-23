<?php

/**
 * صنف الطلب (Order)
 * يربط بين العميل والمنتجات ويحسب الإجمالي.
 */
class Order {
    private $orderNumber;
    private $date;
    private $status;
    private $items = []; // مصفوفة من كائنات Product

    public function __construct($orderNumber, $status = "قيد المعالجة") {
        $this->orderNumber = $orderNumber;
        $this->date = date('Y-m-d H:i:s');
        $this->status = $status;
    }

    // إضافة منتج للطلب
    public function addItem(Product $product) {
        $this->items[] = $product;
    }

    // حساب المبلغ الإجمالي لكل المنتجات في الطلب
    public function calculateTotal() {
        $total = 0;
        foreach ($this->items as $item) {
            // نستخدم السعر بعد الخصم لكل منتج
            $total += $item->getPriceAfterDiscount();
        }
        return $total;
    }

    // Getters
    public function getOrderNumber() { return $this->orderNumber; }
    public function getDate() { return $this->date; }
    public function getStatus() { return $this->status; }
    public function getItems() { return $this->items; }
}
