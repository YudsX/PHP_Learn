<?php
declare(strict_types=1);
// Class harus dimuat sebelum session_start agar object session bisa dipulihkan.
require_once __DIR__ . '/DataMahasiswa.php';
session_start();

function aman(mixed $teks): string {
    return htmlspecialchars((string)$teks, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
$repo = new DataMahasiswa($_SESSION['mahasiswa'] ?? []);
$pesan = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);
$error = '';
$agamaList = ['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'];
$keys = [
    'nim'=>'NIM','nama'=>'Nama','fakultas'=>'Fakultas',
    'prodi'=>'Program Studi','asalSekolah'=>'Asal Sekolah',
    'semester'=>'Semester','hobi'=>'Hobi','agama'=>'Agama'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!hash_equals($_SESSION['csrf'] ?? '', (string)($_POST['csrf'] ?? ''))) {
            throw new InvalidArgumentException('Token formulir tidak valid. Muat ulang halaman.');
        }
        $aksi = (string)($_POST['aksi'] ?? '');
        $nimLama = (string)($_POST['nim_lama'] ?? '');
        if ($aksi === 'hapus') {
            $nim = trim((string)($_POST['nim'] ?? ''));
            if (!$repo->hapus($nim)) throw new InvalidArgumentException('NIM tidak ditemukan.');
        } elseif (in_array($aksi, ['tambah','ubah'], true)) {
            $data = [];
            foreach ($keys as $k => $label) {
                $data[$k] = trim((string)($_POST[$k] ?? ''));
            }
            foreach (['nim','nama','fakultas','prodi','asalSekolah','agama'] as $k) {
                if ($data[$k] === '') throw new InvalidArgumentException($keys[$k] . ' wajib diisi.');
            }
            if (!in_array($data['agama'], $agamaList, true)) {
                throw new InvalidArgumentException('Pilihan agama tidak valid.');
            }
            $semester = filter_var($data['semester'], FILTER_VALIDATE_INT);
            if ($semester === false || $semester < 1 || $semester > 14) {
                throw new InvalidArgumentException('Semester harus 1–14.');
            }
            // CONSTRUCTOR dipanggil saat object dibuat.
            $m = new Mahasiswa(
                $data['nim'], $data['nama'], $data['fakultas'], $data['prodi'],
                $data['asalSekolah'], $semester, $data['hobi'], $data['agama']
            );
            // CRUD bekerja pada object Mahasiswa, bukan property satu per satu.
            $berhasil = $aksi === 'tambah'
                ? $repo->tambah($m)
                : $repo->ubah($nimLama, $m);
            if (!$berhasil) throw new InvalidArgumentException('NIM duplikat atau data lama tidak ditemukan.');
        } else {
            throw new InvalidArgumentException('Aksi tidak dikenal.');
        }
        $_SESSION['mahasiswa'] = $repo->semua();
        $_SESSION['flash'] = 'Data berhasil ' . ($aksi === 'hapus' ? 'dihapus' : ($aksi === 'ubah' ? 'diubah' : 'ditambahkan')) . '.';
        header('Location: index.php');
        exit;
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}
if (!isset($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
$edit = $repo->cari((string)($_GET['edit'] ?? ''));
?>
<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Praktikum PBO 4 - CRUD Mahasiswa</title>
<style>
    *{box-sizing:border-box}body{font-family:system-ui,Arial,sans-serif;background:#f1f5f9;color:#15324d;margin:0}
    main{max-width:1120px;margin:36px auto;padding:0 18px}h1{margin-bottom:4px}
    small,.muted{color:#60758a}.panel{background:white;border-radius:13px;padding:24px;margin:20px 0;box-shadow:0 5px 18px #16324d14}
    .grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:15px}
    label{font-weight:650;font-size:13px;display:block}input,select{display:block;width:100%;padding:11px;margin-top:6px;border:1px solid #c8d5e3;border-radius:7px;background:white;font:inherit}
    button,.btn{border:0;border-radius:7px;background:#176397;color:white;padding:9px 15px;cursor:pointer;text-decoration:none;display:inline-block;font-size:13px}
    .danger{background:#b9414a}.actions{display:flex;gap:7px;align-items:center}.actions form{margin:0}
    table{border-collapse:collapse;width:100%;font-size:13px}th,td{padding:12px 9px;text-align:left;border-bottom:1px solid #e1e8f0}th{background:#e8f1f8}
    .scroll{overflow-x:auto}.notice{padding:12px;border-radius:8px;background:#e3f4e9}.err{background:#ffe8e8}
    @media(max-width:650px){.grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<main>

<h1>Data Mahasiswa</h1><p class="muted">PBO Pertemuan 4: Constructor, Destructor, Overloading + CRUD Array/Session</p>
<?php if ($pesan): ?><div class="notice"><?= aman($pesan) ?></div><?php endif; ?>
<?php if ($error): ?><div class="notice err"><?= aman($error) ?></div><?php endif; ?>
<section class="panel"><h2><?= $edit ? 'Edit' : 'Tambah' ?> Data Mahasiswa</h2>

<form method="post"><input type="hidden" name="csrf" value="<?= aman($_SESSION['csrf']) ?>">
<input type="hidden" name="aksi" value="<?= $edit ? 'ubah' : 'tambah' ?>">
<input type="hidden" name="nim_lama" value="<?= aman($edit?->nim ?? '') ?>">
<div class="grid">

<?php foreach ($keys as $key => $label):
    $value = $edit ? ($edit->$key ?? '') : '';
?>
<label><?= aman($label) ?>
<?php if ($key === 'agama'): ?>
<select name="agama" required><option value="">-- Pilih Agama --</option>
<?php foreach ($agamaList as $agama): ?>
<option value="<?= aman($agama) ?>" <?= $value === $agama ? 'selected' : '' ?>><?= aman($agama) ?></option>
<?php endforeach; ?></select>
<?php else: ?>
<input name="<?= aman($key) ?>" value="<?= aman($value) ?>"
<?= $key === 'semester' ? 'type="number" min="1" max="14"' : 'type="text"' ?>
<?= $key !== 'hobi' ? 'required' : '' ?>>
<?php endif; ?></label>
<?php endforeach; ?></div>
<p><button><?= $edit ? 'Simpan Perubahan' : 'Tambah Mahasiswa' ?></button>
<?php if ($edit): ?><a class="btn" href="index.php">Batal</a><?php endif; ?></p>
</form></section>
    <section class="panel"><h2>Daftar Mahasiswa (<?= count($repo->semua()) ?>)</h2><div class="scroll"><table>
    <thead>
        <tr><?php foreach ($keys as $label): ?>
            <th><?= aman($label) ?>
            </th><?php endforeach; ?>
            <th>Aksi</th>
        </tr>
    </thead>
<tbody>
<?php foreach ($repo->semua() as $m): ?>
<tr><?php foreach (array_keys($keys) as $key): ?>
        <td><?= aman($m->$key ?? '') ?></td>
    <?php endforeach; ?>
    <td>
        <div class="actions">
            <a class="btn" href="?edit=<?= urlencode($m->nim) ?>">Edit</a>
            <form method="post" onsubmit="return confirm('Hapus data ini?')">
                <input type="hidden" name="csrf" value="<?= aman($_SESSION['csrf']) ?>">
                <input type="hidden" name="aksi" value="hapus"><input type="hidden" name="nim" value="<?= aman($m->nim) ?>">
                <button class="danger">Hapus</button>
            </form>
        </div>
    </td>
</tr>

<?php endforeach; ?>
<?php if (!$repo->semua()): ?>
    <tr><td colspan="9">Belum ada data.</td></tr><?php endif; ?>
    </tbody></table></div><p><small>Data disimpan pada PHP Session (sementara), bukan MySQL.</small></p></section>
    <p><a href="demo_konsep.php">Lihat demonstrasi constructor, destructor, dan overloading →</a></p>
    </main>
</body>
</html>
