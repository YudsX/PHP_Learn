<?php
class BukuDemo {
    public function __construct(public string $judul) {
        echo "Objek '$this->judul' dibuat.<br>";
    }

    public function __destruct() {
        echo "Destructor '$this->judul' di panggil! (Objek Dihancurkan).<br>";
    }
}

echo " Pengujian Tanpa Alias <br>";
$b1 = new BukuDemo("Buku A");
unset($b1); // Destructor langsung berjalan
echo "Pengujian 1 Selesai<br><br>";

echo " Pengujian Dengan Alias <br>";
$b2 = new BukuDemo("Buku B");
$aliasB2 = $b2;
unset($b2);     
echo "Variabel \$b2 sudah di-unset, tapi destructor belum jalan.<br>";
unset($aliasB2); 
echo "Pengujian 2 Selesai<br>";