<?php
declare(strict_types=1);

abstract class Student {

    public function __construct(protected string $id, protected string $name, protected string $major, protected float $baseFee) {
        if (trim($id)==='' || trim($name)==='' || trim($major)==='' || !is_finite($baseFee) || $baseFee < 0) throw new InvalidArgumentException('Data mahasiswa tidak valid.');
    }
    final public function getId(): string {
           return $this->id; }
    public function getName(): string {
           return $this->name; }
    public function getMajor(): string {
            return $this->major; }
    public function getBaseFee(): float {
           return $this->baseFee; }
    public function summary(): string {
           return "$this->id - $this->name"; }

    abstract public function getType(): string;
    abstract public function calculateFee(): float;
    
    public function getDiscount(): float {
            return 0; }
}