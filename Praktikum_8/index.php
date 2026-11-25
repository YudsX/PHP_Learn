<?php
declare(strict_types=1);
require_once __DIR__.'/src/bootstrap.php';

session_start();

if (!isset($_SESSION['repository']) || !($_SESSION['repository'] instanceof StudentRepository)) {
    $repo       = new StudentRepository();
    
    $repo->create(new RegularStudent('M001', 'Alya', 'Informatika', 5000000));
    $repo->create(new ScholarshipStudent('M002', 'Bima', 'Sistem Informasi', 5000000, 40));
    $repo->create(new InternationalStudent('M003', 'Kenji', 'Informatika', 5000000, 2500000));
    $_SESSION['repository'] = $repo;
}
if (!isset($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(24));
/** @var StudentRepository $repo */
$repo = $_SESSION['repository'];
function e(mixed $s): string { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function money(float $n): string { return 'Rp'.number_format($n, 0, ',', '.'); }
function makeStudent(array $data): Student {
    $id=trim((string)($data['id']??'')); $name=trim((string)($data['name']??'')); $major=trim((string)($data['major']??''));
    $fee=filter_var($data['fee']??null, FILTER_VALIDATE_FLOAT);
    if ($fee === false) throw new InvalidArgumentException('Biaya dasar harus berupa angka.');
    $type=(string)($data['type']??'');
    if ($type === 'regular') return new RegularStudent($id,$name,$major,(float)$fee);
    if ($type === 'scholarship') {
        $discount=filter_var($data['discount']??null, FILTER_VALIDATE_FLOAT);
        if ($discount === false) throw new InvalidArgumentException('Diskon harus berupa angka.');
        return new ScholarshipStudent($id,$name,$major,(float)$fee,(float)$discount);
    }
    if ($type === 'international') {
        $intlFee=filter_var($data['international_fee']??2500000, FILTER_VALIDATE_FLOAT);
        if ($intlFee === false) throw new InvalidArgumentException('Biaya internasional harus berupa angka.');
        return new InternationalStudent($id,$name,$major,(float)$fee,(float)$intlFee);
    }
    throw new InvalidArgumentException('Jenis mahasiswa tidak dikenal.');
}
$error=''; $notice='';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!hash_equals((string)$_SESSION['csrf'],(string)($_POST['csrf']??''))) throw new RuntimeException('Token keamanan tidak valid.');
        $action=(string)($_POST['action']??'');
        if ($action==='create') { $repo->create(makeStudent($_POST)); $notice='Mahasiswa berhasil ditambahkan.'; }
        elseif ($action==='update') { $repo->update((string)($_POST['old_id']??''),makeStudent($_POST)); $notice='Mahasiswa berhasil diperbarui.'; }
        elseif ($action==='delete') { $repo->delete((string)($_POST['id']??'')); $notice='Mahasiswa berhasil dihapus.'; }
        else throw new InvalidArgumentException('Aksi tidak valid.');
        $_SESSION['repository']=$repo;
    } catch (Throwable $ex) { $error=$ex->getMessage(); }
}
$edit = isset($_GET['edit']) ? $repo->find((string)$_GET['edit']) : null;
$search=trim((string)($_GET['q']??''));
$students=array_values(array_filter($repo->all(), fn(Student $s)=>$search==='' || stripos($s->getId().' '.$s->getName().' '.$s->getMajor(),$search)!==false));
$presenter=new StudentPresenter();
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>CRUD Mahasiswa | Praktikum PBO 8</title><style>
:root{font-family:system-ui,-apple-system,Segoe UI,sans-serif;color:#142238;background:#f3f6fb}*{box-sizing:border-box}body{margin:0}.wrap{max-width:1100px;margin:auto;padding:28px 18px}header{background:#11243a;color:white;padding:26px 0}header h1{margin:4px 0;font-size:28px}header p{color:#cbd5e1;margin:0}.grid{display:grid;grid-template-columns:360px 1fr;gap:20px;margin-top:22px}.card{background:white;border:1px solid #dfe6f0;border-radius:15px;padding:20px;box-shadow:0 4px 14px #1d2a3a0b}h2{margin:0 0 14px;font-size:19px}label{display:block;font-weight:600;font-size:13px;margin:12px 0 5px}input,select{width:100%;padding:10px;border:1px solid #b9c5d5;border-radius:8px;font:inherit}button,.btn{background:#1269d3;color:white;border:0;border-radius:8px;padding:10px 14px;font-weight:650;cursor:pointer;text-decoration:none;display:inline-block}.btn.secondary{background:#eaf0f8;color:#163252}.btn.danger{background:#b42335}.actions{display:flex;gap:6px;align-items:center;flex-wrap:wrap}table{width:100%;border-collapse:collapse;font-size:14px}td,th{text-align:left;padding:12px 8px;border-bottom:1px solid #e5eaf1;vertical-align:top}th{color:#51627a;font-size:12px}.tag{font-size:12px;background:#e7f5ef;color:#167a50;padding:4px 7px;border-radius:5px}.alert{padding:12px 15px;border-radius:9px;margin:12px 0}.ok{background:#e7f5ef}.err{background:#feecef}.small{font-size:13px;color:#52637a}.snippet{background:#f3f6fb;border-radius:9px;padding:12px;margin-top:12px;overflow-wrap:anywhere}@media(max-width:850px){.grid{grid-template-columns:1fr}.card{overflow-x:auto}table{min-width:630px}}
</style></head><body><header><div class="wrap"><div class="small" style="color:#9cc9ff">PBO • PERTEMUAN 8 • PHP 8+</div><h1>CRUD Data Mahasiswa</h1><p>Overloading melalui __call() • Overriding melalui inheritance</p></div></header><main class="wrap">
<?php if($error):?><div class="alert err"><?=e($error)?></div><?php endif;?>
<?php if($notice):?><div class="alert ok"><?=e($notice)?></div><?php endif;?>
<div class="grid"><section class="card"><h2><?= $edit ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' ?></h2><form method="post"><input type="hidden" name="csrf" value="<?=e($_SESSION['csrf'])?>"><input type="hidden" name="action" value="<?=$edit?'update':'create'?>"><input type="hidden" name="old_id" value="<?=e($edit?->getId()??'')?>">
<label>NIM</label><input name="id" required maxlength="30" value="<?=e($edit?->getId()??'')?>"><label>Nama</label><input name="name" required maxlength="100" value="<?=e($edit?->getName()??'')?>"><label>Program Studi</label><input name="major" required maxlength="100" value="<?=e($edit?->getMajor()??'')?>"><label>Biaya Dasar (Rp)</label><input name="fee" type="number" min="0" step="1" required value="<?=e($edit?->getBaseFee()??5000000)?>"><label>Jenis Mahasiswa</label><select name="type"><option value="regular" <?=(!$edit || $edit->getType()==='regular')?'selected':''?>>Reguler</option><option value="scholarship" <?=$edit?->getType()==='scholarship'?'selected':''?>>Beasiswa</option><option value="international" <?=$edit?->getType()==='international'?'selected':''?>>Internasional</option></select><label>Diskon Beasiswa (%)</label><input name="discount" type="number" min="0" max="100" step="0.1" value="<?=e($edit?->getDiscount()??0)?>"><p class="small">Diskon hanya digunakan untuk mahasiswa beasiswa.</p><label>Biaya Tambahan Internasional (Rp)</label><input name="international_fee" type="number" min="0" step="1" value="<?=e($edit instanceof InternationalStudent ? $edit->getInternationalFee() : 2500000)?>"><p class="small">Biaya tambahan hanya digunakan untuk mahasiswa internasional.</p><div class="actions"><button type="submit"><?=$edit?'Simpan Perubahan':'Tambah Data'?></button><?php if($edit):?><a class="btn secondary" href="index.php">Batal</a><?php endif;?></div></form></section>
<section class="card"><h2>Daftar Mahasiswa (<?=count($students)?>)</h2><form method="get" style="display:flex;gap:8px;margin-bottom:12px"><input name="q" placeholder="Cari NIM, nama, prodi" value="<?=e($search)?>"><button>Cari</button><a href="index.php" class="btn secondary">Reset</a></form><table><thead><tr><th>Mahasiswa</th><th>Jenis</th><th>Biaya Akhir</th><th>Aksi</th></tr></thead><tbody><?php foreach($students as $student):?><tr><td><strong><?=e($student->summary())?></strong><div class="small"><?=e($student->getMajor())?></div></td><td><span class="tag"><?=e($student->getType())?></span><?php if($student->getDiscount()>0):?><div class="small">Diskon <?=e($student->getDiscount())?>%</div><?php endif;?><?php if($student instanceof InternationalStudent):?><div class="small">Tambahan <?=e(money($student->getInternationalFee()))?></div><?php endif;?></td><td><strong><?=e(money($student->calculateFee()))?></strong><div class="small">Dasar <?=e(money($student->getBaseFee()))?></div></td><td><div class="actions"><a class="btn secondary" href="?edit=<?=urlencode($student->getId())?>">Edit</a><form method="post" onsubmit="return confirm('Hapus mahasiswa ini?')"><input type="hidden" name="csrf" value="<?=e($_SESSION['csrf'])?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=e($student->getId())?>"><button class="btn danger">Hapus</button></form></div></td></tr><?php endforeach;?><?php if(!$students):?><tr><td colspan="4">Data tidak ditemukan.</td></tr><?php endif;?></tbody></table></section></div>
<section class="card" style="margin-top:20px"><h2>Laboratorium Konsep OOP</h2><p><strong>Overriding:</strong> <code>calculateFee()</code> dan <code>summary()</code> dijalankan sesuai class object sebenarnya. <strong>Overloading PHP:</strong> <code>showData()</code> tidak dideklarasikan; panggilannya ditangani <code>StudentPresenter::__call()</code>.</p><div class="snippet"><div><?=e($presenter->showData('Alya'))?></div><div><?=e($presenter->showData('M001','Alya'))?></div><div><?=e($presenter->showData('M001','Alya','Informatika'))?></div><div><?=e($presenter->showData('M001','Alya','Informatika',4))?></div></div><p class="small">Penyimpanan menggunakan PHP Session untuk tujuan praktikum. Tidak memakai database; data dapat hilang saat session berakhir.</p></section></main></body></html>