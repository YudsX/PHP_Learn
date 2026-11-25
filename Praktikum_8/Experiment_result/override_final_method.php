<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/Student.php';

/**
 * Eksperimen 4:
 * Mencoba meng-override method final Student::getId(): string pada subclass.
 */
final class OverrideFinalStudent extends Student {
    public function getType(): string {
        return 'override-final';
    }

    public function calculateFee(): float {
        return $this->baseFee;
    }

    // Error: Student::getId() bertipe final sehingga tidak boleh di-override
    public function getId(): string {
        return 'CUSTOM-' . $this->id;
    }
}
