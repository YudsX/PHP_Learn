<?php
session_start();
require 'Mahasiswa.php';
require 'DataMahasiswa.php';
$repo = new DataMahasiswa($_SESSION['mahasiswa'] ?? []);
$_SESSION['token'] ??= bin2hex(random_bytes(32));
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!hash_equals($_SESSION['token'], $_POST['token'] ?? '')) {
            throw new InvalidArgumentException('Token tidak valid.');
        }
        $id = (int) ($_POST['id'] ?? 0);
        if (($_POST['aksi'] ?? '') === 'hapus') {
            $repo->hapus($id);
        } else {
            $m = buatMahasiswa($_POST);
            if ($id > 0) { $repo->ubah($id, $m); }
            else { $repo->tambah($m); }
        }
        $_SESSION['mahasiswa'] = $repo->semua();
        header('Location: index.php'); exit;
    } catch (InvalidArgumentException $ex) { $error = $ex->getMessage(); }
}
$edit = $repo->cari((int) ($_GET['edit'] ?? 0)) ?? [];
?>
<!doctype html><html lang="id"><meta charset="utf-8"><title>CRUD Mahasiswa</title>
<style>body{font:16px Arial;max-width:1000px;margin:40px auto;padding:16px}input,select,button{padding:8px;margin:4px}table{border-collapse:collapse;width:100%}td,th{border:1px solid #ddd;padding:10px;text-align:left}th{background:#d8f1ef}form.inline{display:inline}</style>
<h1>CRUD Data Mahasiswa</h1>
<p>Penyimpanan Array dan Session PHP (tanpa database)</p>
<p><?= e($error) ?></p>
<form method="post">
<input type="hidden" name="token" value="<?= e($_SESSION['token']) ?>">
<input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
<input name="nim" placeholder="NIM" required maxlength="20" value="<?= e($edit['nim'] ?? '') ?>">
<input name="nama" placeholder="Nama" required maxlength="100" value="<?= e($edit['nama'] ?? '') ?>">
<input name="prodi" placeholder="Prodi" required maxlength="100" value="<?= e($edit['prodi'] ?? '') ?>">
<select name="jenis"><option value="reguler">Reguler</option><option value="beasiswa" <?= ($edit['jenis'] ?? '') === 'beasiswa' ? 'selected' : '' ?>>Beasiswa</option></select>
<button>Simpan</button><a href="index.php">Batal</a>
</form>
<table><tr><th>NIM / Nama / Prodi</th><th>Jenis</th><th>UKT</th><th>Aksi</th></tr>
<?php foreach ($repo->semua() as $row): $m = buatMahasiswa($row); ?>
<tr><td><?= e($m->tampil('lengkap')) ?></td><td><?= e($m->jenis()) ?></td><td><?= e(tampilUKT($m)) ?></td><td>
<a href="?edit=<?= (int) $row['id'] ?>">Ubah</a>
<form class="inline" method="post" onsubmit="return confirm('Hapus data?')">
<input type="hidden" name="token" value="<?= e($_SESSION['token']) ?>"><input type="hidden" name="id" value="<?= (int) $row['id'] ?>"><button name="aksi" value="hapus">Hapus</button></form></td></tr>
<?php endforeach; ?></table></html>