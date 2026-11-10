<?php
class Mahasiswa {
 public function hitungBiaya(): int { return 5000000; }
}
class MahasiswaBeasiswa extends Mahasiswa {
 public function hitungBiaya(): int { return 2000000; }
}
$a = new Mahasiswa();
$b = new MahasiswaBeasiswa();
echo $a->hitungBiaya() . ' | ' . $b->hitungBiaya();
