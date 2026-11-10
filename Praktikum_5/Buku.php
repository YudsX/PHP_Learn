<?php
// Mengaktifkan pelaporan error untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Buku {
    private string $kodeBuku;
    private string $judul;
    private int $stok;

    // Constructor
    public function __construct(string $kodeBuku, string $judul, int $stok) {
        $this->kodeBuku = trim($kodeBuku);
        if ($this->kodeBuku === "") {
            throw new InvalidArgumentException("Kode buku wajib diisi!");
        }
        
        $this->setJudul($judul);
        $this->setStokAwal($stok);
    }

    // Getter untuk Kode Buku (Read-only, tidak ada setter)
    public function getKodeBuku(): string {
        return $this->kodeBuku;
    }

    // Getter dan Setter untuk Judul
    public function getJudul(): string {
        return $this->judul;
    }

    public function setJudul(string $judul): void {
        $judul = trim($judul);
        if ($judul === "") {
            throw new InvalidArgumentException("Judul buku tidak boleh kosong!");
        }
        $this->judul = $judul;
    }

    // Getter untuk Stok
    public function getStok(): int {
        return $this->stok;
    }

    // Khusus untuk constructor agar stok awal tidak negatif
    private function setStokAwal(int $stok): void {
        if ($stok < 0) {
            throw new InvalidArgumentException("Stok awal tidak boleh bernilai negatif!");
        }
        $this->stok = $stok;
    }

    // Method Perilaku: Tambah Stok
    public function tambahStok(int $jumlah): void {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException("Jumlah penambahan stok harus bernilai positif!");
        }
        $this->stok += $jumlah;
    }

    // Method Perilaku: Kurangi Stok (dengan validasi kecukupan)
    public function kurangiStok(int $jumlah): void {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException("Jumlah pengurangan stok harus bernilai positif!");
        }
        if ($jumlah > $this->stok) {
            throw new InvalidArgumentException("Pengurangan gagal! Stok saat ini tidak mencukupi (Sisa: {$this->stok}).");
        }
        $this->stok -= $jumlah;
    }
}

// --- PENGUJIAN KASUS VALID DAN TIDAK VALID ---
echo "<h2>Pengujian Tugas 2: Class Buku & Enkapsulasi Perilaku</h2>";

// Pengujian 1: Input Valid
try {
    echo "<b>[Uji 1 - Valid] Membuat objek buku baru:</b><br>";
    $buku1 = new Buku("B001", "Pemrograman Berorientasi Objek", 10);
    echo "Berhasil membuat buku: " . $buku1->getJudul() . " | Stok awal: " . $buku1->getStok() . "<br><br>";

    echo "<b>[Uji 2 - Valid] Menambah dan mengurangi stok:</b><br>";
    $buku1->tambahStok(5);
    echo "Setelah ditambah 5 stok, total: " . $buku1->getStok() . "<br>";
    $buku1->kurangiStok(3);
    echo "Setelah dikurangi 3 stok, total akhir: " . $buku1->getStok() . "<br><br>";
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Pengujian 2: Input Tidak Valid (Diluar Aturan Bisnis)
$cases = [
    ["Desc" => "Uji Tidak Valid A (Stok Awal Negatif)", "Func" => function() { new Buku("B002", "Basis Data", -5); }],
    ["Desc" => "Uji Tidak Valid B (Judul Kosong)", "Func" => function() { new Buku("B003", "", 10); }],
    ["Desc" => "Uji Tidak Valid C (Kurangi Stok Melebihi Batas)", "Func" => function() { 
        $b = new Buku("B004", "Algoritma", 5); 
        $b->kurangiStok(10); 
    }]
];

foreach ($cases as $i => $case) {
    try {
        echo "<b>[" . $case["Desc"] . "]</b><br>";
        $case["Func"]();
    } catch (InvalidArgumentException $e) {
        echo "<span style='color:red;'>Ditolak sesuai aturan bisnis: " . $e->getMessage() . "</span><br><br>";
    }
}
?>