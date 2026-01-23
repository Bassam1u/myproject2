<?php
namespace Store\Models;

use Store\Traits\LoggerTrait;

class Customer {
    use LoggerTrait;

    private $name;
    private $email;
    private $registrationDate;

    public function __construct($name, $email, $registrationDate = null) {
        $this->name = $name;
        $this->email = $email;
        $this->registrationDate = $registrationDate ?? date('Y-m-d');
        $this->logEvent("تسجيل عميل جديد: $name");
    }

    public function updateEmail($newEmail) {
        $this->email = $newEmail;
        $this->logEvent("تحديث البريد الإلكتروني إلى: $newEmail");
    }

    public function getMembershipAge() {
        $date = new \DateTime($this->registrationDate);
        $now = new \DateTime();
        return $date->diff($now)->y . " سنوات";
    }

    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
}
