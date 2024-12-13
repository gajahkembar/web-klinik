# Web Klinik

## Deskripsi
Aplikasi web ini dirancang untuk mengelola data pasien, dokter, dan administrasi klinik. Aplikasi ini memanfaatkan PHP untuk backend dan SQL untuk pengelolaan database.

## Struktur Folder
- **admin/**: Berisi file untuk mengelola data administrasi klinik.
- **assets/**: Berisi aset seperti gambar, file CSS, dan JavaScript.
- **dokter/**: Berisi file yang terkait dengan manajemen data dokter.
- **includes/**: Berisi file pendukung seperti konfigurasi database dan fungsi umum.
- **pasien/**: Berisi file untuk manajemen data pasien.
- **index.php**: Halaman utama aplikasi.
- **klinik.sql**: File SQL untuk pembuatan dan inisialisasi database.

## Persyaratan
- PHP 7.4 atau lebih baru
- MySQL 5.7 atau lebih baru
- Server web seperti Apache atau Nginx

## Cara Instalasi
1. Clone repositori ini:
   ```bash
   git clone https://github.com/gajahkembar/web-klinik.git
   ```
2. Pastikan web server Anda diatur untuk mendukung PHP dan MySQL.
3. Import file `klinik.sql` ke database MySQL Anda.
   ```bash
   mysql -u [username] -p klinik < klinik.sql
   ```
4. Konfigurasi file database di folder `includes/` (contoh: `db_config.php`).
5. Jalankan aplikasi melalui server lokal atau hosting web Anda.

## Fitur Utama
- **Manajemen Pasien**: Tambah, ubah, dan hapus data pasien.
- **Manajemen Dokter**: Tambah, ubah, dan hapus data dokter.
- **Manajemen Administrasi**: Kelola data administrasi klinik.

## Kontribusi
Jika Anda ingin berkontribusi pada proyek ini:
1. Fork repositori ini.
2. Buat branch baru untuk fitur atau perbaikan Anda.
3. Kirimkan pull request.

## Lisensi
Proyek ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail lebih lanjut.

---

*Catatan: Pastikan untuk mengganti informasi database pada file konfigurasi sesuai dengan pengaturan Anda.*
