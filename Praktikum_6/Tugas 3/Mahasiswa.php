<?php
declare(strict_types=1);

/** Parent class: menyimpan seluruh data umum mahasiswa. */
abstract class Mahasiswa
{
    private string $nim;
    private string $nama;
    private string $fakultas;
    private string $prodi;
    private string $asalSekolah;
    private int $semester;
    private string $hobi;
    private string $agama;

    public const AGAMA = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];

    public function __construct(
        string $nim, string $nama, string $fakultas, string $prodi,
        string $asalSekolah, int $semester, string $hobi, string $agama
    ) {
        $nim = trim($nim);
        if ($nim === '') throw new InvalidArgumentException('NIM wajib diisi.');
        $this->nim = $nim;
        $this->setNama($nama);
        $this->setFakultas($fakultas);
        $this->setProdi($prodi);
        $this->setAsalSekolah($asalSekolah);
        $this->setSemester($semester);
        $this->setHobi($hobi);
        $this->setAgama($agama);
    }

    public function getNim(): string { return $this->nim; }
    public function getNama(): string { return $this->nama; }
    public function getFakultas(): string { return $this->fakultas; }
    public function getProdi(): string { return $this->prodi; }
    public function getAsalSekolah(): string { return $this->asalSekolah; }
    public function getSemester(): int { return $this->semester; }
    public function getHobi(): string { return $this->hobi; }
    public function getAgama(): string { return $this->agama; }

    public function setNama(string $v): void {
        $v = trim($v); if ($v === '') throw new InvalidArgumentException('Nama wajib diisi.'); $this->nama = $v;
    }
    public function setFakultas(string $v): void {
        $v = trim($v); if ($v === '') throw new InvalidArgumentException('Fakultas wajib diisi.'); $this->fakultas = $v;
    }
    public function setProdi(string $v): void {
        $v = trim($v); if ($v === '') throw new InvalidArgumentException('Program studi wajib diisi.'); $this->prodi = $v;
    }
    public function setAsalSekolah(string $v): void {
        $v = trim($v); if ($v === '') throw new InvalidArgumentException('Asal sekolah wajib diisi.'); $this->asalSekolah = $v;
    }
    public function setSemester(int $v): void {
        if ($v < 1 || $v > 14) throw new InvalidArgumentException('Semester harus 1–14.');
        $this->semester = $v;
    }
    public function setHobi(string $v): void { $this->hobi = trim($v); }
    public function setAgama(string $v): void {
        $v = trim($v); if (!in_array($v, self::AGAMA, true)) throw new InvalidArgumentException('Pilihan agama tidak valid.');
        $this->agama = $v;
    }

    abstract public function getJenis(): string;
    abstract public function getUkt(): int;
    abstract public function getPotongan(): float;
    abstract public function getBiayaAkhir(): float;
}
