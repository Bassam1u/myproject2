<?php
require_once 'PermanentEmployee.php';

/**
 * صنف المدير (Manager)
 * يرث من PermanentEmployee (لأنه موظف دائم أصلاً) ويضيف مزايا إضافية.
 */
class Manager extends PermanentEmployee {
    private $bonus; // مكافأة الإدارة

    public function __construct($id, $name, $baseSalary, $allowances, $bonus) {
        // 5. إعادة استخدام الكود عبر استدعاء بناء الأب
        parent::__construct($id, $name, $baseSalary, $allowances);
        $this->bonus = $bonus;
    }

    // 3. تعدد الأشكال: حساب راتب المدير (راتب دائم + مكافأة)
    public function calculateSalary() {
        return parent::calculateSalary() + $this->bonus;
    }

    public function getDetails() {
        return parent::getDetails() . " (منصب إداري)";
    }
}
