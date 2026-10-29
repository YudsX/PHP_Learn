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
$laporan = new LaporanMahasiswa('Alya');
echo $laporan->cetakRingkas();