<?php
class Mahasiswa {
    private string $nim;
    private string $nama;
    private string $prodi;
    protected int $uktDasar = 5000000;
 
    public function __construct(string $nim, string $nama, string $prodi) {
        $this->nim = trim($nim);
        $this->setNama($nama);
        $this->prodi = trim($prodi);
        if ($this->nim === '' || $this->prodi === '') {
            throw new InvalidArgumentException('NIM dan prodi wajib diisi.');
        }
    }
    public function __destruct() {
        // Jejak siklus hidup; lihat log PHP jika diaktifkan.
        if (defined("DEBUG_PBO") && DEBUG_PBO) {
            error_log("Objek mahasiswa dilepas");
        }
    }
    public function setNama(string $nama): void {
        if (trim($nama) === '') {
            throw new InvalidArgumentException('Nama wajib diisi.');
        }
        $this->nama = trim($nama);
    }
    public function getNim(): string { return $this->nim; }
    public function getNama(): string { return $this->nama; }
    public function getProdi(): string { return $this->prodi; }
    public function hitungUKT(): int { return $this->uktDasar; }
    public function jenis(): string { return 'reguler'; }
    public function ringkasan(): string { return $this->nim . ' - ' . $this->nama; }
    public function __call(string $method, array $args): mixed {
        if ($method === 'tampil' && count($args) === 0) {
            return $this->ringkasan();
        }
        if ($method === 'tampil' && $args === ['lengkap']) {
            return $this->ringkasan() . ' - ' . $this->prodi;
        }
        throw new BadMethodCallException('Pemanggilan tidak valid.');
    }
}
class MahasiswaReguler extends Mahasiswa {}
class MahasiswaBeasiswa extends Mahasiswa {
    public function hitungUKT(): int { return (int) (parent::hitungUKT() * 0.6); }
    public function jenis(): string { return 'beasiswa'; }
}
function buatMahasiswa(array $data): Mahasiswa {
    $kelas = match ($data['jenis']) {
        'reguler' => MahasiswaReguler::class,
        'beasiswa' => MahasiswaBeasiswa::class,
        default => throw new InvalidArgumentException('Jenis tidak valid.')
    };
    return new $kelas($data['nim'], $data['nama'], $data['prodi']);
}
function tampilUKT(Mahasiswa $m): string {
    return 'Rp ' . number_format($m->hitungUKT(), 0, ',', '.');
}