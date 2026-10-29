<?php
class warung_nasgor {
    public $kepedasan;
    public $harga;

    function __construct($kepedasan, $harga) {
        $this->kepedasan = $kepedasan;
        $this->harga = $harga;
    }

    function rincian_makanan() {
        echo "kepedasan: " . $this->kepedasan . ". harga: " . $this->harga .".<br>";
    }      
}

$nasgor = new warung_nasgor(5, 15000);
$nasgor->rincian_makanan();
?>