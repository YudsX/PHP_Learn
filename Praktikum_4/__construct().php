<?php
declare(strict_types=1);

class MahasiswaModif {
    public function __construct(
        public string $nim,
        public string $nama,
        public int $semester = 1,
        public string $fakultas = "Belum diisi"
    ) {}

    public function profil(): string {
        return "$this->nim - $this->nama | Sem: $this->semester | Fakultas: $this->fakultas";
    }
}

$mhs1 = new MahasiswaModif('231001', 'Alya', 3, 'Sains dan Teknologi');
$mhs2 = new MahasiswaModif('231002', 'Yudha', 1);

echo $mhs1->profil() . "<br>";
echo $mhs2->profil() . "<br>";