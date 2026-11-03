<?php
declare(strict_types=1);
class MahasiswaDinamis {
    private array $atributTambahan = [];
    public function __construct(public string $nim, public string $nama) {}

    // ini Set
    public function __set(string $key, mixed $value): void {
        $diizinkan = ['hobi', 'asalSekolah', 'agama'];
        if (!in_array($key, $diizinkan, true)) {
            throw new InvalidArgumentException('Atribut tidak diizinkan');
        }
        $this->atributTambahan[$key] = $value;
    }

    // ini Get
    public function __get(string $key): mixed {
        if (!array_key_exists($key, $this->atributTambahan)) {
            throw new OutOfBoundsException('Atribut tidak ditemukan');
        }
        return $this->atributTambahan[$key];
    }

    // ini Isset
    public function __isset(string $key): bool {
        return isset($this->atributTambahan[$key]);
    }

    // ini Unset
    public function __unset(string $key): void {
        unset($this->atributTambahan[$key]);
    }
}

// --- PENGUJIAN LATIHAN NO. 4
$mhs = new MahasiswaDinamis('231001', 'Alya');

// 1. Menguji __set()
$mhs->hobi = 'Membaca';
echo "Hobi berhasil diisi.<br>";

// 2. Menguji __get()
echo "Nilai hobi: " . htmlspecialchars($mhs->hobi, ENT_QUOTES, 'UTF-8') . "<br>";

// 3. Menguji __isset() (Memeriksa atribut hobi apakah ada atau tidak)
if (isset($mhs->hobi)) {
    echo "Status __Isset: Atribut 'Hobi' ada.<br>";
} else {
    echo "Status __isset: Atribut 'Hobi' tidak ada.<br>"; 
}

// 4. Menguji __unset() (Menghapus atribut hobi)
unset($mhs->hobi);
echo "Atribut hobi telah di-unset.<br>";

// Memeriksa kembali setelah di-unset
if (isset($mhs->hobi)) {
    echo "Status setelah unset: Atribut masih ada.<br>";
} else {
    echo "Status setelah unset: Atribut sudah terhapus (tidak ada).<br>";
}