<?php
declare(strict_types=1);
require __DIR__.'/../src/bootstrap.php';
$pass=0;
function check(bool $ok, string $label): void { 
        global $pass; if(!$ok) throw new RuntimeException('FAIL: '.$label); $pass++; echo "PASS: $label\n";
        }
function fails(callable $fn, string $exception, string $label): void {
        try{$fn();}catch(Throwable $e){check($e instanceof $exception,$label);return;}throw new RuntimeException('FAIL: '.$label);
        }
$r=new StudentRepository();
$a=new RegularStudent('M01','Alya','Informatika',5000000);
$b=new ScholarshipStudent('M02','Bima','SI',5000000,40);
$c=new InternationalStudent('M05','Kenji','Informatika',5000000,2500000);

check($a instanceof Student,'Inheritance regular');
check($b instanceof Student,'Inheritance scholarship');
check($c instanceof Student,'Inheritance international');
check($a->calculateFee()===5000000.0,'Overriding biaya reguler');
check($b->calculateFee()===3000000.0,'Overriding biaya beasiswa');
check($c->calculateFee()===7500000.0,'Overriding biaya internasional');
check($c->getType()==='international','Type internasional');
check(str_contains($b->summary(),'[Beasiswa]'),'Overriding + parent:: beasiswa');
check(str_contains($c->summary(),'[Internasional]'),'Overriding + parent:: internasional');

$r->create($a);$r->create($b);$r->create($c);
check(count($r->all())===3,'Create polymorphic (termasuk InternationalStudent)');
check($r->find('M02')===$b,'Read scholarship');
check($r->find('M05')===$c,'Read international');
fails(fn()=>$r->create($a),InvalidArgumentException::class,'NIM duplikat ditolak');
$r->update('M01',new RegularStudent('M03','Cici','Informatika',4000000));
check($r->find('M01')===null && $r->find('M03')!==null,'Update NIM');
$r->delete('M03');
check(count($r->all())===2,'Delete');
$p=new StudentPresenter();
check($p->showData('Alya')==='Nama: Alya','__call satu argumen');
check(str_contains($p->showData('M01','Alya'),'NIM:'),'__call dua argumen');
check(str_contains($p->showData('M01','Alya','TI'),'Prodi:'),'__call tiga argumen');
check($p->showData('M01','Alya','TI',4)==='NIM: M01, Nama: Alya, Prodi: TI, Semester: 4','__call empat argumen (id, name, major, semester)');
fails(fn()=>$p->showData('M01','Alya','TI',0),InvalidArgumentException::class,'__call empat argumen semester < 1 ditolak');
fails(fn()=>$p->showData('M01','Alya','TI',15),InvalidArgumentException::class,'__call empat argumen semester > 14 ditolak');
fails(fn()=>$p->showData('M01','Alya','TI','4'),InvalidArgumentException::class,'__call empat argumen semester bukan int ditolak');
fails(fn()=>$p->showData('','Alya','TI',4),InvalidArgumentException::class,'__call empat argumen string kosong ditolak');
fails(fn()=>$p->showData('M01','Alya','TI',4,'extra'),InvalidArgumentException::class,'__call lima argumen ditolak');
fails(fn()=>$p->showData(),InvalidArgumentException::class,'__call argumen invalid');
fails(fn()=>$p->unknown(),BadMethodCallException::class,'__call method invalid');
fails(fn()=>new ScholarshipStudent('M04','Dina','TI',1000,101),InvalidArgumentException::class,'Diskon invalid');
fails(fn()=>new InternationalStudent('M06','Yuki','TI',5000000,-100),InvalidArgumentException::class,'Biaya internasional negatif ditolak');
check((new ReflectionMethod(Student::class,'getId'))->isFinal(),'Final method tidak boleh override');
echo "TOTAL: $pass tests passed\n";