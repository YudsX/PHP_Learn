<?php
class DemoSiklus {
    public function __construct() { echo "Object dibuat<br>"; }
    public function tampil(): void { echo "Method dipanggil<br>"; }
    public function __destruct() { echo "Destructor berjalan<br>"; }
}
$obj = new DemoSiklus();
$obj->tampil();
unset($obj);
echo "Program dilanjutkan";