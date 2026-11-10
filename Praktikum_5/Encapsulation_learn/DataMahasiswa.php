<?php
declare(strict_types=1);
require_once __DIR__ . '/Mahasiswa.php';

class DataMahasiswa {
    /** @var array<string, Mahasiswa> */
    private array $data = [];

    public function __construct(array $dataAwal = []) {
        foreach ($dataAwal as $item) {
            if ($item instanceof Mahasiswa) {
                // Gunakan getNim() karena properti nim bersifat private
                $this->data[$item->getNim()] = $item;
            }
        }
    }

    public function tambah(Mahasiswa $m): bool {
        // Gunakan getNim()
        if (isset($this->data[$m->getNim()])) return false;
        $this->data[$m->getNim()] = $m;
        return true;
    }

    /** @return array<string, Mahasiswa> */
    public function semua(): array { 
        return $this->data; 
    }

    public function cari(string $nim): ?Mahasiswa {
        return $this->data[$nim] ?? null;
    }

    public function ubah(string $nimLama, Mahasiswa $m): bool {
        if (!isset($this->data[$nimLama])) return false;
        
        // Gunakan getNim() untuk perbandingan dan key array
        if ($m->getNim() !== $nimLama && isset($this->data[$m->getNim()])) return false;
        
        unset($this->data[$nimLama]);
        $this->data[$m->getNim()] = $m;
        return true;
    }

    public function hapus(string $nim): bool {
        if (!isset($this->data[$nim])) return false;
        unset($this->data[$nim]);
        return true;
    }
}