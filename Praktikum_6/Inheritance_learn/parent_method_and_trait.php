<?php
trait BisaCetak {
 public function cetak(): string { return 'Siap dicetak'; }
}
class Mahasiswa {
 public function label(): string { return 'Mahasiswa'; }
}
class MahasiswaReguler extends Mahasiswa {
 use BisaCetak;
 public function label(): string {
 return parent::label() . ' Reguler';
 }
}
$m = new MahasiswaReguler();
echo $m->label() . ' - ' . $m->cetak();