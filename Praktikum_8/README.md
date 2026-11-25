# Praktikum PBO Pertemuan 8 — CRUD Data Mahasiswa (PHP)

**Topik:** Overloading dan Overriding — Konsep Fundamental, Perbandingan Implementasi, dan Praktikum Menggunakan PHP.

## Menjalankan

1. Gunakan PHP 8.0+ (disarankan PHP 8.2+).
2. Ekstrak folder ke `htdocs` XAMPP lalu buka `http://localhost/PBO08_CRUD_Mahasiswa_PHP/index.php`, atau jalankan dari folder proyek: `php -S localhost:8000`, lalu buka `http://localhost:8000`.
3. Jalankan pengujian dari folder proyek: `php tests/run.php`.

## Struktur
- `src/Student.php`           : abstract parent class, method `final getId()`.
- `src/RegularStudent.php`    : overriding `calculateFee()`.
- `src/ScholarshipStudent.php`: overriding `calculateFee()` dan `summary()` memakai `parent::`.
- `src/StudentRepository.php` : CRUD polymorphic (`Student` sebagai tipe parameter).
- `src/StudentPresenter.php`   : PHP-style overloading via `__call()`.
- `index.php`: UI CRUD, pencarian, session storage, CSRF token, escaping output.
- `tests/run.php`: pengujian konsep dan operasi CRUD.

## Materi yang wajib dipahami

**Overriding:** subclass mendefinisikan ulang method yang diwariskan, dengan signature kompatibel. Dalam aplikasi, `calculateFee()` menghasilkan nominal berbeda untuk RegularStudent dan ScholarshipStudent. `summary()` pada ScholarshipStudent memakai `parent::summary()`.

**Overloading PHP:** tidak sama dengan method overloading berbasis signature di Java/C++. `StudentPresenter` sengaja **tidak** memiliki method `showData()`. Pemanggilan tersebut diarahkan ke `__call()` untuk 1, 2, atau 3 string. Magic method digunakan sebagai demonstrasi, bukan anjuran mengganti seluruh API bisnis dengan `__call()`.

**Variadic:** satu method dengan `...$args` bisa menerima banyak argumen, tetapi bukan traditional method overloading.

## Latihan
1. Tambahkan subclass `InternationalStudent` dengan biaya kuliah berbeda tanpa mengubah `StudentRepository`.
2. Tambahkan pola `showData(id, name, major, semester)` pada `__call()` beserta validasi dan tes.
3. Coba ubah signature `calculateFee()` menjadi tidak kompatibel pada salinan file terpisah. Catat error PHP.
4. Coba override `getId()` (method `final`) di salinan terpisah. Catat error PHP.
5. Jelaskan mengapa `StudentRepository` menerima `Student` dan bukan hanya `RegularStudent`.

## Batasan
Ini aplikasi edukasi, bukan aplikasi produksi. Data hanya disimpan dalam PHP Session (bukan MySQL), belum ada login/otorisasi, audit trail, atau persistence lintas session. Form input diproses dan divalidasi server-side; pengamanan lebih lanjut diperlukan untuk produksi.