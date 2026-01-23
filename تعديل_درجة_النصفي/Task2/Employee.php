<?php
require_once 'EmployeeInterface.php';

/**
 * الصنف الأساسي (Abstract Class)
 * يجمع الخصائص المشتركة بين جميع الموظفين.
 */
abstract class Employee implements EmployeeInterface {
    protected $id;
    protected $name;
    protected $baseSalary; // 2. حماية البيانات (Protected للسماح للأبناء بالوصول إليها)

    public function __construct($id, $name, $baseSalary) {
        $this->id = $id;
        $this->name = $name;
        $this->setBaseSalary($baseSalary);
    }

    // 2. الفلفلة (Encapsulation) لحماية الراتب الأساسي
    public function setBaseSalary($salary) {
        if ($salary >= 0) {
            $this->baseSalary = $salary;
        }
    }

    public function getBaseSalary() {
        return $this->baseSalary;
    }

    public function getName() {
        return $this->name;
    }

    // تطبيق الدالة المشتركة من الواجهة
    public function getDetails() {
        return "الموظف: [{$this->id}] {$this->name}";
    }
    
    // ملاحظة: calculateSalary() بقيت abstract ليتم تعريفها في كل نوع موظف بشكل مختلف
}
