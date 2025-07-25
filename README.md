📝 Project NCAGE

Project NCAGE merupakan project website berbasis Laravel yang dibuat sebagai bahan pemenuhan tanggung jawab saat magang di Pusat Kodifikasi Badan Sarana Pertahanan (Baranahan) Kementerian Pertahanan (Kemhan). Output yang diharapkan dari website ini adalah otomatisasi dalam pemberian kode NCAGE sekaligus sertifikat NCAGE kepada perusahaan-perusahaan di Indonesia. NCAGE sendiri berfungsi untuk standarisasi dan efisiensi dalam proses logistik dan pengadaan, terutama di lingkungan pertahanan dan antar-negara.

🛠️ Teknologi yang Digunakan
Berikut adalah tumpukan teknologi (tech stack) yang digunakan dalam proyek ini:
 - Laravel Framework 10.48.29
 - PHP 8.3.23
 - MySQL v8.0.41
 - Bootstrap v5.0.2
 - filament v3.3

🚀 Cara Instalasi & Setup
Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Anda.

1. Clone Repositori

git clone https://github.com/BenedictoGeraldo/Project_NCAGE.git
cd Project_NCAGE

2. Instal Dependensi Composer

Pastikan Anda sudah menginstal Composer. Jalankan perintah ini untuk menginstal semua paket PHP yang dibutuhkan.

composer install

3. Siapkan File .env

Salin file .env.example menjadi file .env baru. File ini berisi semua konfigurasi lingkungan Anda.

copy .env.example .env

4. Buat Kunci Aplikasi (Generate App Key)

Setiap aplikasi Laravel membutuhkan kunci enkripsi yang unik.

php artisan key:generate

5. Konfigurasi Database

Buka file .env dan sesuaikan pengaturan database Anda.

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=project_ncage
DB_USERNAME=postgres
DB_PASSWORD=[Isi dengan password PostgreSQL Anda]

6. Jalankan Migrasi Database

Perintah ini akan membuat semua tabel yang dibutuhkan oleh aplikasi di dalam database Anda.

php artisan migrate

7. Jalankan Server Pengembangan

Terakhir, jalankan server pengembangan Laragon (atau gunakan perintah di bawah) dan aplikasi siap diakses!

php artisan serve

Aplikasi akan berjalan di http://127.0.0.1:8000.

Dibuat dengan ❤️ oleh Jon Snow, Lord Commander of The Night's Watch.
