<?php

/**
 * الواجهة (Interface) لضمان سلوك موحد لجميع الموظفين
 */
interface EmployeeInterface {
    public function calculateSalary(); // دالة حساب الراتب
    public function getDetails();      // دالة استرجاع البيانات
}
