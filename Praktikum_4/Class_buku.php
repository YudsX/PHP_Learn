<?php
declare(strict_types=1);
class Buku {
    public function __construct(
        public string $judul,
        public string $penulis,
        public string $tahun
    ) {}
    public function tampilinfo(): string {
        return "Judul Buku: $this->judul | Nama penulis: $this->penulis | Tahun terbit: $this->tahun";
    }
    // public function __destruct() {echo "Lanjut ke Bab 1 Habits </br>";}
}

// Memasukan Atribut 
$books1 = new Buku('Atomic Habits', 'Yudhs', '2026');
$books2 = new Buku('Psychology Of Money', 'Alips', '2026');
$books3 = new Buku('Pemrograman Assembly', 'Eful', '2026');

// Print untuk setiap buku
echo $books1->tampilinfo() . "<br>";
echo $books2->tampilinfo() . "<br>";
echo $books3->tampilinfo() . "<br>";

// echo '..."Menghapus Object buku"...</br>';
// unset($books);
// echo '..."object sudah di unset"...</br>';