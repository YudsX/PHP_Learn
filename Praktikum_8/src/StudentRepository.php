<?php
declare(strict_types=1);

final class StudentRepository {
    /** @var array<string,Student> */
    private array $items = [];
    public function create(Student $student): void {
        if (isset($this->items[$student->getId()])) throw new InvalidArgumentException('NIM sudah terdaftar.');
        $this->items[$student->getId()] = $student;
    }
    public function all(): array { ksort($this->items);
           return array_values($this->items); }
    public function find(string $id): ?Student {
          return $this->items[$id] ?? null; }
    public function update(string $oldId, Student $student): void {
        if (!isset($this->items[$oldId])) throw new InvalidArgumentException('Mahasiswa tidak ditemukan.');
        if ($oldId !== $student->getId() && isset($this->items[$student->getId()])) throw new InvalidArgumentException('NIM baru sudah terdaftar.');
        unset($this->items[$oldId]); $this->items[$student->getId()] = $student;
    }
    public function delete(string $id): void {
        if (!isset($this->items[$id])) throw new InvalidArgumentException('Mahasiswa tidak ditemukan.');
        unset($this->items[$id]);
    }
}