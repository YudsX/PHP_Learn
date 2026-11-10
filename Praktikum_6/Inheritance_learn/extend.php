<?php
class Mahasiswa {
 public function __construct(private string $nama) {}
 public function getNama(): string { return $this->nama; }
}
class MahasiswaReguler extends Mahasiswa {
 public function jenis(): string { return 'Reguler'; }
}

$m = new MahasiswaReguler('Andi');
echo $m->getNama() . ' - ' . $m->jenis();