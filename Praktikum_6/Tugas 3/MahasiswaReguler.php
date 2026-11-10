<?php
declare(strict_types=1);
require_once __DIR__ . '/Mahasiswa.php';

/** Child class untuk mahasiswa reguler. */
class MahasiswaReguler extends Mahasiswa
{
    private int $ukt;

    public function __construct(
        string $nim, string $nama, string $fakultas, string $prodi,
        string $asalSekolah, int $semester, string $hobi, string $agama, int $ukt
    ) {
        parent::__construct($nim, $nama, $fakultas, $prodi, $asalSekolah, $semester, $hobi, $agama);
        $this->setUkt($ukt);
    }

    public function setUkt(int $ukt): void {
        if ($ukt < 0) throw new InvalidArgumentException('UKT tidak boleh kurang dari 0.');
        $this->ukt = $ukt;
    }
    public function getJenis(): string { return 'Reguler'; }
    public function getUkt(): int { return $this->ukt; }
    public function getPotongan(): float { return 0.0; }
    public function getBiayaAkhir(): float { return (float)$this->ukt; }
}
