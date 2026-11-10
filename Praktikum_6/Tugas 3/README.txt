Wirayudhabuana Putra. M 
INI ADALAH TUGAS PBO - INHERITANCE MAHASISWA REGULER DAN BEASISWA
Persyaratan: PHP 8+ dan XAMPP/LARAGON

CARA MENJALANKAN
1. Ekstrak folder Tugas3_inherintace_fixed ke C:\xampp\htdocs\
2. Jalankan Apache melalui XAMPP Control Panel.
3. Buka http://localhost/Tugas3_inherintace_fixed/index.php

STRUKTUR
- Mahasiswa.php: parent class berisi atribut dan method umum mahasiswa.
- MahasiswaReguler.php: child class dengan atribut UKT.
- MahasiswaBeasiswa.php: child class dengan atribut UKT dan potongan.
- DataMahasiswa.php: penyimpanan data dan operasi CRUD.
- index.php: form, validasi, pencarian, tabel, serta controller.
- demo_konsep.php: contoh penggunaan class (opsional; file demo lama dapat dipakai setelah disesuaikan).

CATATAN
- NIM tidak mempunyai setter dan tidak dapat diedit.
- UKT divalidasi >= 0; potongan beasiswa 0 sampai 100 persen.
- Data tersimpan di PHP Session, sehingga bersifat sementara dan dapat hilang saat session berakhir.
- Child class tidak mendeklarasikan ulang atribut NIM, nama, prodi, atau semester.
