<?php
namespace Store\Interfaces;

/**
 * واجهة لضمان أن الكائن لديه قدرة تسجيل الأحداث
 */
interface LoggableInterface {
    public function logEvent($event);
    public function getLogs();
}
