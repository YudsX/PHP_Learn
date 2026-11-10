<?php
declare(strict_types=1);

class Mahasiswa {
    private string $nim;
    private string $nama;
    private int $semester;
    private string $prodi;

    public function __construct(string $nim, string $nama, int $semester, string $prodi) {
        $nim = trim($nim);
        if ($nim === "") {
            throw new InvalidArgumentException("NIM wajib diisi");
        }
        $this->nim = $nim;
        $this->setNama($nama);
        $this->setSemester($semester);
        $this->setProdi($prodi);
    }

    public function getNim(): string { 
        return $this->nim; 
    }

    public function getNama(): string { 
        return $this->nama; 
    }

    public function getSemester(): int { 
        return $this->semester; 
    }

    public function getProdi(): string { 
        return $this->prodi; 
    }

    public function setNama(string $nama): void {
        $nama = trim($nama);
        if ($nama === "") {
            throw new InvalidArgumentException("Nama wajib diisi");
        }
        $this->nama = $nama;
    }

    public function setSemester(int $semester): void {
        if ($semester < 1 || $semester > 14) {
            throw new InvalidArgumentException("Semester harus 1-14");
        }
        $this->semester = $semester;
    }

    public function setProdi(string $prodi): void {
        $prodi = trim($prodi);
        if ($prodi === "") {
            throw new InvalidArgumentException("Prodi wajib diisi");
        }
        $this->prodi = $prodi;
    }
}
```[cite: 6, 7]

---

### Langkah 2: Ubah Akses pada Repository DataMahasiswa
Perubahan cara repository mengakses properti objek mahasiswa dari yang sebelumnya langsung (seperti `$m->nim`) menjadi menggunakan getter (`$m->getNim()`)[cite: 7].
```php
<?php

// SEBELUM (Pertemuan 4)
// $this->data[$m->nim] = $m;

// SESUDAH (Pertemuan 5)
$this->data[$m->getNim()] = $m;

// Contoh pencarian tetap memakai key NIM
public function cari(string $nim): ?Mahasiswa {
    return $this->data[$nim] ?? null;
}

// Langkah 3: Ubah Akses pada Tampilan index.php
<!-- SEBELUM -->
<td><?= aman($m->nama) ?></td>
<td><?= aman($m->semester) ?></td>

<!-- SESUDAH -->
<td><?= aman($m->getNama()) ?></td>
<td><?= aman($m->getSemester()) ?></td>

<!-- Isi nilai form Edit menggunakan operator nullsafe -->
<input name="nama" value="<?= aman($edit?->getNama() ?? '') ?>">
<input name="semester" type="number" value="<?= aman($edit?->getSemester() ?? '') ?>">

// Langkah 4: Tangani Input yang Tidak Valid 
<?php

try {
    $m = new Mahasiswa(
        trim((string) ($_POST['nim'] ?? '')),
        trim((string) ($_POST['nama'] ?? '')),
        (int) ($_POST['semester'] ?? 0),
        trim((string) ($_POST['prodi'] ?? ''))
    );
    
    $repo->tambah($m);
} catch (InvalidArgumentException $e) {
    $error = $e->getMessage();
}