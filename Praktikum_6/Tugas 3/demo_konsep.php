<?php
declare(strict_types=1);
require_once __DIR__ . '/DataMahasiswa.php';

header('Content-Type: text/plain; charset=utf-8');

$reguler = new MahasiswaReguler('1001', 'Alya', 'Teknik', 'Informatika', 'SMA 1', 3, 'Membaca', 'Islam', 5000000);
$beasiswa = new MahasiswaBeasiswa('1002', 'Bima', 'Teknik', 'Informatika', 'SMA 2', 2, 'Musik', 'Islam', 5000000, 50);

echo "Contoh inheritance mahasiswa\n";
echo $reguler->getNama() . " | " . $reguler->getJenis() . " | Biaya akhir: Rp " . number_format($reguler->getBiayaAkhir(), 0, ',', '.') . "\n";
echo $beasiswa->getNama() . " | " . $beasiswa->getJenis() . " | Biaya akhir: Rp " . number_format($beasiswa->getBiayaAkhir(), 0, ',', '.') . "\n";
