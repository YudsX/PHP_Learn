<?php
// Mengaktifkan pelaporan error untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Produk {
    // 1. Properti dengan modifier berbeda
    public string $nama = "Laptop Gaming";       // Bisa diakses dari mana saja
    protected string $kategori = "Elektronik";   // Bisa diakses class sendiri & subclass
    private int $harga = 15000000;             // HANYA bisa diakses di dalam class ini saja

    // Method untuk menampilkan isi data dari dalam class Produk
    public function tampilkanInfoDariParent(): void {
        echo "<b>Di dalam Class Produk</b><br>";
        echo "- Nama (public): " . $this->nama . "<br>";
        echo "- Kategori (protected): " . $this->kategori . "<br>";
        echo "- Harga (private): " . $this->harga . "<br><br>";
    }
}

// Subclass / Class Turunan untuk menguji akses 'protected'
class ProdukDigital extends Produk {
    public function aksesDariSubclass(): void {
        echo "<b>Di dalam Subclass / Class Anak</b><br>";
        echo "- Nama (public) bisa diakses: " . $this->nama . "<br>";
        echo "- Kategori (protected) bisa diakses: " . $this->kategori . "<br>";
        
        // Baris di bawah ini akan ERROR jika dibuka (dinyalakan) karena 'private' tidak bisa diakses anak class
        // echo "- Harga (private): " . $this->harga . "<br>"; 
    }
}

echo "<h2>Eksperimen Access Modifier (Public, Protected, Private)</h2>";

// Membuat objek dari class Produk
$p = new Produk();
$p->tampilkanInfoDariParent();

echo "<b>Pengujian Langsung dari Luar Class</b><br>";

// 1. PENGUJIAN PROPERTI PUBLIC
// Berhasil diakses karena sifatnya terbuka untuk umum
echo "1. Mengakses \$nama (Public): " . $p->nama . ", 'BERHASIL'<br>";


/* 
   CATATAN PENGUJIAN PROTECTED & PRIVATE DARI LUAR:
   Jika baris kode di bawah ini kamu hilangkan komentarnya (dijalankan), 
   maka PHP akan memunculkan Fatal Error.
*/

// 2. PENGUJIAN PROPERTI PROTECTED (Akan error jika dicoba akses langsung dari luar)
// echo $p->kategori; 
echo "2. Mengakses \$kategori (Protected) dari luar 'DITOLAK' = ERROR! <br>";

// 3. PENGUJIAN PROPERTI PRIVATE (Akan error jika dicoba akses langsung dari luar)
// echo $p->harga; 
echo "3. Mengakses \$harga (Private) dari luar 'DITOLAK' = ERROR! <br><br>";


// Pengujian Melalui Subclass (Class Anak)
$digital = new ProdukDigital();
$digital->aksesDariSubclass();
?>