<?php
define('DEBUG_PBO', true);
require 'Mahasiswa.php';

$m = new MahasiswaReguler('003', 'Budi', 'TI');

echo $m->tampil() . '<br>';
echo $m->tampil('lengkap') . '<br>';

$m->setNama('Budi Baru');
echo $m->getNama() . '<br>';

$b = new MahasiswaBeasiswa('004', 'Rina', 'TI');

foreach ([$m, $b] as $objek) {
    echo tampilUKT($objek) . '<br>';
}

unset($objek, $m, $b);