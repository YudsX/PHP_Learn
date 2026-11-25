<?php
declare(strict_types=1);
final class RegularStudent extends Student {
    public function getType(): string { return 'regular'; }
    public function calculateFee(): float { return $this->baseFee; } // overriding abstract method
}