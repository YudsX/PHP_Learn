<?php
declare(strict_types=1);
require_once __DIR__ . '/Mahasiswa.php';

define('DEMO_LIFECYCLE', true);
header('Content-Type: text/plain; charset=utf-8');

echo "1. Menjalankan Kontruktor\n";

$m = new Mahasiswa('123', 'Alya', 'Teknik', 'Informatika', 'SMA 1', 3, 'Membaca', 'Islam');
echo $m->ringkasan() . "\n";

echo "\n2. Menjalankan OVERLOADING  Attribut\n";

$m->ukuranJaket = 'M';       // __set() karena property tidak dideklarasikan
echo $m->ukuranJaket . "\n"; // __get()
echo isset($m->ukuranJaket) ? "Ada\n" : "Tidak ada\n";
unset($m->ukuranJaket);     // __unset()

echo "\n3.Menjalankan  OVERLOADING METHOD\n";
echo $m->profilSingkat() . "\n"; // __call()

echo "\n4.Menjalankan  DESTRUCTOR\n";
echo "Lihat log error PHP setelah unset referensi terakhir.\n";
unset($m); // destructor menulis error_log, bukan menghapus record CRUD
