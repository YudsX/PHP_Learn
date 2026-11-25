<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/Student.php';

/**
 * Eksperimen 3:
 * Mengubah signature calculateFee() menjadi tidak kompatibel dengan Student::calculateFee(): float
 * dengan menambahkan parameter wajib ($tax) dan/atau mengubah tipe kembalian.
 */
final class BrokenFeeStudent extends Student {
    public function getType(): string {
        return 'broken';
    }

    // Tidak kompatibel: parent mendefinisikan calculateFee(): float tanpa parameter wajib
    public function calculateFee(float $tax): float {
        return $this->baseFee + $tax;
    }
}
