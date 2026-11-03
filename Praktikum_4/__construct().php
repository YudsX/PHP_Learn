<?php
declare(strict_types=1);
class Mahasiswa {
    public function __construct(
        public string $nim,
        public string $nama,
        public int $semester, 
        public string $fakultas
    ) {}
    public function profil(): string {
        return "$this->nim - $this->nama (Semester $this->semester) - (fakultas: $this->fakultas)";
    }
}
$mhs = new Mahasiswa('231001', 'Alya', 3,'Belum diisi');
echo $mhs->profil();