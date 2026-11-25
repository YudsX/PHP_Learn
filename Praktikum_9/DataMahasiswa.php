<?php
class DataMahasiswa {
    private array $data;
 
    public function __construct(array $data = []) {
        $this->data = $data;
    }
    public function semua(): array {
        return $this->data;
    }
    public function cari(int $id): ?array {
        return $this->data[$id] ?? null;
    }
    private function nilai(Mahasiswa $m): array {
        return [
            'nim' => $m->getNim(),
            'nama' => $m->getNama(),
            'prodi' => $m->getProdi(),
            'jenis' => $m->jenis()
        ];
    }
    private function cekNim(Mahasiswa $m, int $id = 0): void {
        foreach ($this->data as $key => $row) {
            if ($key !== $id && $row['nim'] === $m->getNim()) {
                throw new InvalidArgumentException('NIM sudah ada.');
            }
        }
    }
    public function tambah(Mahasiswa $m): void {
        $this->cekNim($m);
        $id = max([0, ...array_keys($this->data)]) + 1;
        $this->data[$id] = ['id' => $id] + $this->nilai($m);
    }
    public function ubah(int $id, Mahasiswa $m): void {
        if (!$this->cari($id)) {
            throw new InvalidArgumentException('Data tidak ditemukan.');
        }
        $this->cekNim($m, $id);
        $this->data[$id] = ['id' => $id] + $this->nilai($m);
    }
    public function hapus(int $id): void {
        unset($this->data[$id]);
    }
}