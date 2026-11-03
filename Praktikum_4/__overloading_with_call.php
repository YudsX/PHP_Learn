<?php
class LaporanMahasiswa {
    public function __construct(private string $nama) {}
    public function __call(string $method, array $args): mixed {
        if ($method === 'cetakRingkas' && count($args) === 0) {
            return 'Mahasiswa: ' . $this->nama;
        }
        throw new BadMethodCallException('Method tidak tersedia: ' . $method);
    }
}


// --- PENGUJIAN LATIHAN MANDIRI NO. 5 ---

$laporan = new LaporanMahasiswa('Alya');

echo "<h3>Pengujian Magic Method __call()</h3>";

// 1. Uji pemanggilan method yang hanya diizinkan 'cetakRingkas'
try {
    echo "<b>1. Memanggil method 'cetakRingkas()' [DIIZINKAN]:</b><br>";
    echo $laporan->cetakRingkas() . "<br><br>";
} catch (BadMethodCallException $e) {
    echo "Terjadi Error: " . $e->getMessage() . "<br><br>";
}

// 2. Uji pemanggilan method lain yang tidak diizinkan 'cetakLengkap()'
try {
    echo "<b>2. Memanggil method 'cetakLengkap()' [TIDAK DIIZINKAN]:</b><br>";
    echo $laporan->cetakLengkap() . "<br>";
} catch (BadMethodCallException $e) {
    // Menangkap dan mencatat exception yang dialihkan
    echo "<span style='color:red;'><b>Exception Tertangkap:</b> " . $e->getMessage() . "</span><br><br>";
}

// 3. Uji pemanggilan method lain yang tidak diizinkan 'hapusData()'
try {
    echo "<b>3. Memanggil method 'hapusData()' [TIDAK DIIZINKAN]:</b><br>";
    echo $laporan->hapusData() . "<br>";
} catch (BadMethodCallException $e) {
    // Menangkap dan mencatat exception yang dialihkan
    echo "<span style='color:red;'><b>Exception Tertangkap:</b> " . $e->getMessage() . "</span><br>";
}