<?php
namespace Store\Models;

use Store\Traits\LoggerTrait;
use Store\Interfaces\LoggableInterface;

class Order implements LoggableInterface {
    use LoggerTrait;

    private $orderNumber;
    private $date;
    private $status;
    private $items = [];

    public function __construct($orderNumber, $status = "جديد") {
        $this->orderNumber = $orderNumber;
        $this->status = $status;
        $this->date = date('Y-m-d H:i:s');
        $this->logEvent("تم إنشاء الطلب رقم: $orderNumber");
    }

    public function addItem(Product $product) {
        $this->items[] = $product;
        $this->logEvent("تمت إضافة المنتج " . $product->getName() . " إلى الطلب");
    }

    public function calculateTotal() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getFinalPrice();
        }
        return $total;
    }

    public function updateStatus($newStatus) {
        $this->status = $newStatus;
        $this->logEvent("تحديث حالة الطلب إلى: $newStatus");
    }

    public function getOrderNumber() { return $this->orderNumber; }
    public function getStatus() { return $this->status; }
    public function getItems() { return $this->items; }
}
