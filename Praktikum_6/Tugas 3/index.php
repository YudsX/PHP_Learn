<?php
declare(strict_types=1);
require_once __DIR__ . '/DataMahasiswa.php';
session_start();

function e(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function rupiah(float $v): string { return 'Rp ' . number_format($v, 0, ',', '.'); }

$repo = new DataMahasiswa($_SESSION['mahasiswa'] ?? []);
$pesan = (string)($_SESSION['flash'] ?? '');
unset($_SESSION['flash']);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!hash_equals($_SESSION['csrf'] ?? '', (string)($_POST['csrf'] ?? ''))) {
            throw new InvalidArgumentException('Token formulir tidak valid. Muat ulang halaman.');
        }
        $aksi = (string)($_POST['aksi'] ?? '');
        if ($aksi === 'hapus') {
            $nim = trim((string)($_POST['nim'] ?? ''));
            if (!$repo->hapus($nim)) throw new InvalidArgumentException('Data mahasiswa tidak ditemukan.');
        } elseif (in_array($aksi, ['tambah', 'ubah'], true)) {
            $nimLama = trim((string)($_POST['nim_lama'] ?? ''));
            $nim = $aksi === 'ubah' ? $nimLama : trim((string)($_POST['nim'] ?? ''));
            if ($aksi === 'ubah' && $repo->cari($nimLama) === null) {
                throw new InvalidArgumentException('Data yang akan diedit tidak ditemukan.');
            }
            $nama = trim((string)($_POST['nama'] ?? ''));
            $fakultas = trim((string)($_POST['fakultas'] ?? ''));
            $prodi = trim((string)($_POST['prodi'] ?? ''));
            $asal = trim((string)($_POST['asalSekolah'] ?? ''));
            $hobi = trim((string)($_POST['hobi'] ?? ''));
            $agama = trim((string)($_POST['agama'] ?? ''));
            $jenis = (string)($_POST['jenis'] ?? '');
            foreach (['NIM' => $nim, 'Nama' => $nama, 'Fakultas' => $fakultas, 'Program studi' => $prodi, 'Asal sekolah' => $asal, 'Agama' => $agama] as $label => $value) {
                if ($value === '') throw new InvalidArgumentException($label . ' wajib diisi.');
            }
            $semester = filter_var($_POST['semester'] ?? null, FILTER_VALIDATE_INT);
            if ($semester === false || $semester < 1 || $semester > 14) throw new InvalidArgumentException('Semester harus 1–14.');
            $uktRaw = trim((string)($_POST['ukt'] ?? ''));
            if ($uktRaw === '' || filter_var($uktRaw, FILTER_VALIDATE_INT) === false) throw new InvalidArgumentException('UKT harus berupa bilangan bulat 0 atau lebih.');
            $ukt = (int)$uktRaw;
            if ($ukt < 0) throw new InvalidArgumentException('UKT tidak boleh kurang dari 0.');

            $dataUmum = [$nim, $nama, $fakultas, $prodi, $asal, $semester, $hobi, $agama];
            if ($jenis === 'Reguler') {
                $args = $dataUmum; $args[] = $ukt;
                $m = new MahasiswaReguler(...$args);
            } elseif ($jenis === 'Beasiswa') {
                $potRaw = trim((string)($_POST['potongan'] ?? ''));
                if ($potRaw === '' || !is_numeric($potRaw)) throw new InvalidArgumentException('Potongan beasiswa wajib diisi dengan angka 0–100.');
                $potongan = (float)$potRaw;
                if ($potongan < 0 || $potongan > 100) throw new InvalidArgumentException('Potongan harus berada pada rentang 0–100%.');
                $args = $dataUmum; $args[] = $ukt; $args[] = $potongan;
                $m = new MahasiswaBeasiswa(...$args);
            } else {
                throw new InvalidArgumentException('Jenis mahasiswa harus Reguler atau Beasiswa.');
            }

            $berhasil = $aksi === 'tambah' ? $repo->tambah($m) : $repo->ubah($nimLama, $m);
            if (!$berhasil) throw new InvalidArgumentException('NIM sudah terdaftar atau data lama tidak ditemukan.');
        } else {
            throw new InvalidArgumentException('Aksi tidak dikenal.');
        }
        $_SESSION['mahasiswa'] = $repo->semua();
        $_SESSION['flash'] = 'Data berhasil ' . ($aksi === 'hapus' ? 'dihapus' : ($aksi === 'ubah' ? 'diperbarui' : 'ditambahkan')) . '.';
        header('Location: index.php'); exit;
    } catch (Throwable $ex) {
        $error = $ex->getMessage();
    }
}

if (!isset($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
$edit = $repo->cari((string)($_GET['edit'] ?? ''));
$jenisEdit = $edit?->getJenis() ?? 'Reguler';
$search = trim((string)($_GET['q'] ?? ''));
$daftar = array_filter($repo->semua(), static function (Mahasiswa $m) use ($search): bool {
    if ($search === '') return true;
    $haystack = implode(' ', [$m->getNim(), $m->getNama(), $m->getProdi(), $m->getFakultas(), $m->getJenis()]);
    return stripos($haystack, $search) !== false;
});
function val(?Mahasiswa $m, string $field): string {
    if (!$m) return '';
    return match ($field) {
        'nim' => $m->getNim(), 'nama' => $m->getNama(), 'fakultas' => $m->getFakultas(),
        'prodi' => $m->getProdi(), 'asalSekolah' => $m->getAsalSekolah(),
        'semester' => (string)$m->getSemester(), 'hobi' => $m->getHobi(), 'agama' => $m->getAgama(),
        'ukt' => (string)$m->getUkt(), 'potongan' => (string)$m->getPotongan(), default => ''
    };
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>CRUD Mahasiswa Reguler dan Beasiswa</title>
<style>
*{box-sizing:border-box}body{font-family:system-ui,Arial,sans-serif;background:#f1f5f9;color:#15324d;margin:0}
main{max-width:1200px;margin:32px auto;padding:0 18px}h1{margin-bottom:4px}.muted,small{color:#60758a}
.panel{background:#fff;border-radius:13px;padding:22px;margin:18px 0;box-shadow:0 5px 18px #16324d14}
.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
label{font-weight:650;font-size:13px;display:block}input,select{width:100%;padding:10px;margin-top:6px;border:1px solid #c8d5e3;border-radius:7px;font:inherit;background:white}
input[readonly]{background:#eaf0f5;color:#60758a}button,.btn{border:0;border-radius:7px;background:#176397;color:white;padding:9px 14px;cursor:pointer;text-decoration:none;display:inline-block;font-size:13px}
.danger{background:#b9414a}.actions{display:flex;gap:7px;align-items:center}.actions form{margin:0}
table{border-collapse:collapse;width:100%;font-size:13px;min-width:1050px}th,td{padding:11px 9px;text-align:left;border-bottom:1px solid #e1e8f0}th{background:#e8f1f8}.scroll{overflow-x:auto}
.notice{padding:12px;border-radius:8px;background:#e3f4e9;margin:12px 0}.err{background:#ffe8e8}.search{display:flex;gap:8px;margin:12px 0}.search input{max-width:360px;margin:0}
@media(max-width:650px){.grid{grid-template-columns:1fr}.search{flex-direction:column}.search input{max-width:none}}
</style>
</head>
<body><main>
<h1>Data Mahasiswa</h1>
<p class="muted">PBO: Inheritance, Enkapsulasi, dan CRUD menggunakan PHP Session.</p>
<?php if ($pesan): ?><div class="notice"><?=e($pesan)?></div><?php endif; ?>
<?php if ($error): ?><div class="notice err"><?=e($error)?></div><?php endif; ?>

<section class="panel">
<h2><?=$edit ? 'Edit' : 'Tambah'?> Data Mahasiswa</h2>
<form method="post">
<input type="hidden" name="csrf" value="<?=e($_SESSION['csrf'])?>">
<input type="hidden" name="aksi" value="<?=$edit ? 'ubah' : 'tambah'?>">
<input type="hidden" name="nim_lama" value="<?=e($edit?->getNim() ?? '')?>">
<div class="grid">
<label>NIM<input name="nim" value="<?=e(val($edit,'nim'))?>" required <?=$edit ? 'readonly' : ''?>></label>
<label>Nama<input name="nama" value="<?=e(val($edit,'nama'))?>" required></label>
<label>Fakultas<input name="fakultas" value="<?=e(val($edit,'fakultas'))?>" required></label>
<label>Program Studi<input name="prodi" value="<?=e(val($edit,'prodi'))?>" required></label>
<label>Asal Sekolah<input name="asalSekolah" value="<?=e(val($edit,'asalSekolah'))?>" required></label>
<label>Semester<input type="number" name="semester" min="1" max="14" value="<?=e(val($edit,'semester'))?>" required></label>
<label>Hobi<input name="hobi" value="<?=e(val($edit,'hobi'))?>"></label>
<label>Agama<select name="agama" required><option value="">-- Pilih Agama --</option>
<?php foreach (Mahasiswa::AGAMA as $a): ?><option value="<?=e($a)?>" <?=val($edit,'agama')===$a?'selected':''?>><?=e($a)?></option><?php endforeach; ?>
</select></label>
<label>Jenis Mahasiswa<select name="jenis" id="jenis" required>
<option value="Reguler" <?=$jenisEdit==='Reguler'?'selected':''?>>Reguler</option>
<option value="Beasiswa" <?=$jenisEdit==='Beasiswa'?'selected':''?>>Beasiswa</option>
</select></label>
<label>UKT (Rp)<input type="number" name="ukt" min="0" step="1" value="<?=e(val($edit,'ukt') !== '' ? val($edit,'ukt') : '0')?>" required></label>
<div id="potonganWrap"><label>Potongan Beasiswa (%)<input type="number" name="potongan" id="potongan" min="0" max="100" step="0.01" value="<?=e(val($edit,'potongan') !== '' ? val($edit,'potongan') : '0')?>"></label></div>
</div>
<p><button type="submit"><?=$edit ? 'Simpan Perubahan' : 'Tambah Mahasiswa'?></button>
<?php if($edit): ?> <a class="btn" href="index.php">Batal</a><?php endif; ?></p>
<?php if($edit): ?><small>NIM <?=e($edit->getNim())?> tidak dapat diubah saat proses edit.</small><?php endif; ?>
</form>
</section>

<section class="panel">
<h2>Daftar Mahasiswa (<?=count($daftar)?> dari <?=count($repo->semua())?>)</h2>
<form class="search" method="get"><input name="q" placeholder="Cari NIM, nama, prodi, fakultas, jenis..." value="<?=e($search)?>"><button type="submit">Cari</button><?php if($search!==''): ?><a class="btn" href="index.php">Reset</a><?php endif; ?></form>
<div class="scroll"><table><thead><tr>
<th>NIM</th><th>Nama</th><th>Fakultas</th><th>Prodi</th><th>Asal Sekolah</th><th>Semester</th><th>Hobi</th><th>Agama</th><th>Jenis</th><th>UKT</th><th>Potongan</th><th>Biaya Akhir</th><th>Aksi</th>
</tr></thead><tbody>
<?php foreach($daftar as $m): ?><tr>
<td><?=e($m->getNim())?></td><td><?=e($m->getNama())?></td><td><?=e($m->getFakultas())?></td><td><?=e($m->getProdi())?></td>
<td><?=e($m->getAsalSekolah())?></td><td><?=e($m->getSemester())?></td><td><?=e($m->getHobi())?></td><td><?=e($m->getAgama())?></td>
<td><?=e($m->getJenis())?></td><td><?=rupiah($m->getUkt())?></td>
<td><?=e(number_format($m->getPotongan(),2,',','.'))?>%</td><td><strong><?=rupiah($m->getBiayaAkhir())?></strong></td>
<td><div class="actions"><a class="btn" href="?edit=<?=urlencode($m->getNim())?>">Edit</a>
<form method="post" onsubmit="return confirm('Hapus data mahasiswa ini?')"><input type="hidden" name="csrf" value="<?=e($_SESSION['csrf'])?>"><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="nim" value="<?=e($m->getNim())?>"><button class="danger">Hapus</button></form></div></td>
</tr><?php endforeach; ?>
<?php if(!$daftar): ?><tr><td colspan="13">Tidak ada data yang cocok atau belum ada data mahasiswa.</td></tr><?php endif; ?>
</tbody></table></div>
<p><small>Data disimpan sementara menggunakan PHP Session, bukan database MySQL. Pencarian mencakup NIM, nama, prodi, fakultas, dan jenis mahasiswa.</small></p>
</section>
</main>
<script>
const jenis = document.getElementById('jenis');
const wrap = document.getElementById('potonganWrap');
const potongan = document.getElementById('potongan');
function aturJenis(){
  const beasiswa = jenis.value === 'Beasiswa';
  wrap.hidden = !beasiswa;
  potongan.required = beasiswa;
  potongan.disabled = !beasiswa;
}
jenis.addEventListener('change', aturJenis);
aturJenis();
</script>
</body></html>
