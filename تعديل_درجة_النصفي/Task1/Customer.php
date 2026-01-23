<?php

/**
 * صنف العميل (Customer)
 * يمثل بيانات العميل وحساب مدة العضوية.
 */
class Customer {
    private $name;
    private $email;
    private $registrationDate;

    // 3. Constructor
    public function __construct($name, $email, $registrationDate = null) {
        $this->name = $name;
        $this->email = $email;
        // إذا لم يتم تمرير تاريخ، نفترض تاريخ اليوم
        $this->registrationDate = $registrationDate ?? date('Y-m-d');
    }

    // حساب عمر العضوية (بالأيام أو السنين)
    public function getMembershipDuration() {
        $regDate = new DateTime($this->registrationDate);
        $today = new DateTime();
        $interval = $regDate->diff($today);
        
        if ($interval->y > 0) {
            return $interval->format('%y سنة و %m شهر');
        }
        return $interval->format('%a يوم');
    }

    // 4. دالة سحرية للتأكد من حالة الكائن عند محاولة استدعائه كدالة (مثال)
    public function __invoke() {
        return "هذا الكائن يمثل العميل: " . $this->name;
    }

    // Getters
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getRegistrationDate() { return $this->registrationDate; }
}
