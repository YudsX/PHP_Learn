<?php
declare(strict_types=1);
final class ScholarshipStudent extends Student {
    public function __construct(string $id, string $name, string $major, float $baseFee, private float $discount) {
        if (!is_finite($discount) || $discount < 0 || $discount > 100) throw new InvalidArgumentException('Diskon harus 0 sampai 100%.');
        parent::__construct($id, $name, $major, $baseFee);
    }
    public function getType(): string { 
           return 'scholarship'; }
    public function getDiscount(): float {
            return $this->discount; }
    public function calculateFee(): float { 
           return $this->baseFee * (1 - $this->discount / 100); }
    public function summary(): string { 
           return parent::summary() . ' [Beasiswa]'; }
}