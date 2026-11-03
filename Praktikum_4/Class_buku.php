<?php
declare(strict_types=1);
class Buku {
    public function __construct(
        public string $judul,
        public string $penulis,
        public string $tahun
    ) {}
    public function tampilinfo(): string {
        return "Judul Buku: $this->judul</br> Nama penulis: $this->penulis</br> Tahun terbit: $this->tahun</br>";
    }
    public function __destruct() {echo "Lanjut ke Bab 1 Habits </br>";}
}

$books = new Buku('Atomic Habits', 'Yudhs', '2026');
echo $books->tampilinfo();
echo '..."Menghapus Object buku"...</br>';
unset($books);
echo '..."object sudah di unset"...</br>';