<?php
require_once 'Employee.php';

/**
 * موظف بعقد (Contract Employee)
 * يحسب راتبه بناءً على الساعات ومعدل الساعة.
 */
class ContractEmployee extends Employee {
    private $hourlyRate;
    private $hoursWorked;

    public function __construct($id, $name, $hourlyRate, $hoursWorked) {
        // نضع الراتب الأساسي كـ 0 لأن الحساب يعتمد على الساعات
        parent::__construct($id, $name, 0);
        $this->hourlyRate = $hourlyRate;
        $this->hoursWorked = $hoursWorked;
    }

    // 3. تعدد الأشكال (Polymorphism): حساب راتب موظف العقد
    public function calculateSalary() {
        return $this->hourlyRate * $this->hoursWorked;
    }

    public function getDetails() {
        return parent::getDetails() . " | النوع: موظف بعقد (ساعات)";
    }
}
