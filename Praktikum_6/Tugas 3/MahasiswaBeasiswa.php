<?php
declare(strict_types=1);
require_once __DIR__ . '/Mahasiswa.php';

/** Child class untuk mahasiswa penerima beasiswa. */
class MahasiswaBeasiswa extends Mahasiswa
{
    private int $ukt;
    private float $potongan;

    public function __construct(
        string $nim, string $nama, string $fakultas, string $prodi,
        string $asalSekolah, int $semester, string $hobi, string $agama,
        int $ukt, float $potongan
    ) {
        parent::__construct($nim, $nama, $fakultas, $prodi, $asalSekolah, $semester, $hobi, $agama);
        $this->setUkt($ukt);
        $this->setPotongan($potongan);
    }

    public function setUkt(int $ukt): void {
        if ($ukt < 0) throw new InvalidArgumentException('UKT tidak boleh kurang dari 0.');
        $this->ukt = $ukt;
    }
    public function setPotongan(float $potongan): void {
        if ($potongan < 0 || $potongan > 100) throw new InvalidArgumentException('Potongan harus berada pada rentang 0–100%.');
        $this->potongan = $potongan;
    }
    public function getJenis(): string { return 'Beasiswa'; }
    public function getUkt(): int { return $this->ukt; }
    public function getPotongan(): float { return $this->potongan; }
    public function getBiayaAkhir(): float { return $this->ukt * (1 - $this->potongan / 100); }
}
