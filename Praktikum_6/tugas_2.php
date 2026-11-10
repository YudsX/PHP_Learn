<?php

// 1. Parent Class (Pegawai)
class Pegawai {
    private int $id;
    private string $nama;

    public function __construct(int $id, string $nama) {
        $this->id = $id;
        $this->nama = $nama;
    }

    public function getNama(): string {
        return $this->nama;
    }
}

// 2. Child Class 1 (PegawaiTetap)
class PegawaiTetap extends Pegawai {
    private int $gajiPokok;

    public function __construct(int $id, string $nama, int $gajiPokok) {
        // Memanggil constructor parent untuk id dan nama
        parent::__construct($id, $nama);

        // Validasi gaji tidak boleh negatif
        if ($gajiPokok < 0) {
            throw new InvalidArgumentException("Gaji pokok tidak boleh negatif.");
        }
        $this->gajiPokok = $gajiPokok;
    }

    // Overriding method untuk menghitung gaji Pegawai Tetap
    public function hitungGaji(): int {
        return $this->gajiPokok;
    }
}

// 3. Child Class 2 (PegawaiKontrak)
class PegawaiKontrak extends Pegawai {
    private int $upahPerJam;
    private int $jamKerja; // Tambahan property agar bisa dihitung totalnya

    public function __construct(int $id, string $nama, int $upahPerJam, int $jamKerja) {
        // Memanggil constructor parent untuk id dan nama
        parent::__construct($id, $nama);

        // Validasi upah dan jam tidak boleh negatif
        if ($upahPerJam < 0 || $jamKerja < 0) {
            throw new InvalidArgumentException("Upah per jam atau jam kerja tidak boleh negatif.");
        }
        
        $this->upahPerJam = $upahPerJam;
        $this->jamKerja = $jamKerja;
    }

    // Overriding method untuk menghitung total gaji Pegawai Kontrak
    public function hitungGaji(): int {
        return $this->upahPerJam * $this->jamKerja;
    }
}

// ==========================================
// 4. Instansiasi dan Menampilkan Output
// ==========================================

try {
    // Membuat objek Pegawai Tetap
    $pegawaiTetap = new PegawaiTetap(101, "Budi Santoso", 6500000);
    
    // Membuat objek Pegawai Kontrak (contoh: upah 50.000/jam, kerja 160 jam)
    $pegawaiKontrak = new PegawaiKontrak(102, "Siti Aminah", 50000, 160);

    // Menampilkan output
    echo "Nama: " . $pegawaiTetap->getNama() . " | Status: Tetap | Gaji: Rp" . number_format($pegawaiTetap->hitungGaji(), 0, ',', '.') . "<br>";
    echo "Nama: " . $pegawaiKontrak->getNama() . " | Status: Kontrak | Gaji: Rp" . number_format($pegawaiKontrak->hitungGaji(), 0, ',', '.') . "<br>";

} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage();
}

?>