<?php
require_once 'Employee.php';

/**
 * موظف دائم (Permanent Employee)
 * يرث من Employee ويضيف منطق الحوافز.
 */
class PermanentEmployee extends Employee {
    private $allowances; // البدلات

    public function __construct($id, $name, $baseSalary, $allowances) {
        parent::__construct($id, $name, $baseSalary);
        $this->allowances = $allowances;
    }

    // 3. تعدد الأشكال (Polymorphism): حساب راتب الموظف الدائم
    public function calculateSalary() {
        return $this->baseSalary + $this->allowances;
    }

    public function getDetails() {
        return parent::getDetails() . " | النوع: موظف دائم";
    }
}
