<?php
declare(strict_types=1);
/** PHP overloading: __call() menangani method yang TIDAK dideklarasikan. */
final class StudentPresenter {
    public function __call(string $method, array $args): string {
        if ($method !== 'showData') throw new BadMethodCallException("Method tidak dikenal: $method");
        if (count($args) === 1 && is_string($args[0])) return 'Nama: ' . $args[0];
        if (count($args) === 2 && is_string($args[0]) && is_string($args[1])) return 'NIM: ' . $args[0] . ', Nama: ' . $args[1];
        if (count($args) === 3 && is_string($args[0]) && is_string($args[1]) && is_string($args[2])) return 'NIM: ' . $args[0] . ', Nama: ' . $args[1] . ', Prodi: ' . $args[2];
        if (count($args) === 4) {
            if (!is_string($args[0]) || trim($args[0]) === '' || !is_string($args[1]) || trim($args[1]) === '' || !is_string($args[2]) || trim($args[2]) === '') {
                throw new InvalidArgumentException('NIM, Nama, dan Prodi harus berupa string tidak kosong.');
            }
            if (!is_int($args[3]) || $args[3] < 1 || $args[3] > 14) {
                throw new InvalidArgumentException('Semester harus berupa bilangan bulat antara 1 sampai 14.');
            }
            return 'NIM: ' . $args[0] . ', Nama: ' . $args[1] . ', Prodi: ' . $args[2] . ', Semester: ' . $args[3];
        }
        throw new InvalidArgumentException('showData menerima 1, 2, 3 argumen string, atau 4 argumen (id, name, major, semester).');
    }
}