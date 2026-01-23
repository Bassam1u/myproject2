<?php
namespace Store\Traits;

/**
 * Trait LoggerTrait
 * يستخدم لتسجيل الأحداث (Events) في مختلف فئات النظام.
 */
trait LoggerTrait {
    private $logs = [];

    public function logEvent($event) {
        $timestamp = date('Y-m-d H:i:s');
        $this->logs[] = "[$timestamp] " . $event;
    }

    public function getLogs() {
        return $this->logs;
    }
}
