<?php
declare(strict_types=1);

class Mahasiswa {
    private array $atributTambahan = [];

    // Constructor: validasi keadaan awal object.
    public function __construct(
        public string $nim,
        public string $nama,
        public string $fakultas,
        public string $prodi,
        public string $asalSekolah,
        public int $semester,
        public string $hobi,
        public string $agama
    ) {
        
        if ($nim === '' || $nama === '' || $semester < 1 || $semester > 14) {
            throw new InvalidArgumentException('NIM, nama, dan semester harus valid.');
        }
    }

    public function ringkasan(): string {
        return $this->nim . ' - ' . $this->nama;
    }

    // Overloading property: menangani atribut DINAMIS yang tidak dideklarasikan.
    public function __set(string $nama, mixed $nilai): void {
        $this->atributTambahan[$nama] = $nilai;
    }
    public function __get(string $nama): mixed {
        return $this->atributTambahan[$nama] ?? null;
    }
    public function __isset(string $nama): bool {
        return isset($this->atributTambahan[$nama]);
    }
    public function __unset(string $nama): void {
        unset($this->atributTambahan[$nama]);
    }

    // Overloading method: hanya menerima alias yang sengaja disediakan.
    public function __call(string $nama, array $argumen): mixed {
        if ($nama === 'profilSingkat' && count($argumen) === 0) {
            return $this->ringkasan();
        }
        throw new BadMethodCallException('Method tidak tersedia: ' . $nama);
    }

    public function __destruct() {
        // Demonstrasi saja. Jangan echo di aplikasi CRUD/session:
        // destructor dapat dipanggil saat request selesai atau object dilepas.
        if (defined('DEMO_LIFECYCLE') && DEMO_LIFECYCLE) {
            error_log('Destructor Mahasiswa: ' . $this->nim);
        }
    }
}
