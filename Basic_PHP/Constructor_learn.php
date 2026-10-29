<?php
declare(strict_types=1);
class Mahasiswa {
    public function __construct(
        public string $nim,
        public string $nama,
        public int $semester
    ) {}
    public function profil(): string {
        return "$this->nim - $this->nama (Semester $this->semester)";
    }
}

// desantralisasi
$mhs = new Mahasiswa('2530521012', 'Wirayudhabuana Putra. M', 1);
echo $mhs->profil();