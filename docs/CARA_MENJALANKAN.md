# Tutorial Menjalankan Lumora di Localhost

Panduan ini ditujukan untuk Windows. Lumora menggunakan Laravel 11, SQLite,
Node.js, dan Laravel Reverb. Untuk mode demo, **Apache dan MySQL XAMPP tidak
perlu dinyalakan**.

## Cara paling mudah: satu kali klik

1. Buka folder proyek:

   ```text
   D:\tugas kuliah\semester 7\lumora_app
   ```

2. Klik dua kali file `START_LUMORA.bat`.
3. Tunggu proses persiapan selesai. Pada penggunaan pertama proses dapat lebih
   lama karena dependensi dan aset perlu disiapkan.
4. Setelah muncul tulisan berikut, browser akan dibuka otomatis:

   ```text
   Server running on [http://0.0.0.0:8000]
   ```

5. Jika browser tidak terbuka otomatis, buka:

   [http://127.0.0.1:8000](http://127.0.0.1:8000)

6. **Jangan tutup jendela terminal Lumora.** Website akan berhenti jika
   terminal tersebut ditutup.
7. Untuk mematikan aplikasi, buka terminal Lumora lalu tekan `Ctrl+C` dan
   konfirmasi jika diminta.

Launcher menjalankan aplikasi web, QR realtime/Reverb, queue, dan scheduler.
Launcher juga memeriksa PHP, Composer, npm, `.env`, database, migrasi, serta
build tampilan.

## Menjalankan melalui terminal

1. Buka folder proyek di File Explorer.
2. Klik kolom alamat folder, ketik `cmd`, lalu tekan Enter.
3. Jalankan:

   ```bat
   composer run dev:realtime
   ```

4. Biarkan terminal terbuka dan kunjungi
   [http://127.0.0.1:8000](http://127.0.0.1:8000).

Gunakan Command Prompt apabila PowerShell menampilkan pesan bahwa `npm.ps1`
tidak dapat dijalankan. Alternatif perintah build di PowerShell adalah:

```powershell
npm.cmd run build
```

## Persiapan pertama kali

Komputer harus mempunyai:

- PHP 8.2 atau lebih baru;
- Composer;
- Node.js versi LTS beserta npm.

PHP dari XAMPP boleh digunakan, tetapi folder `C:\xampp\php` harus tersedia di
PATH Windows. Periksa instalasi melalui Command Prompt:

```bat
php -v
composer --version
node -v
npm -v
```

Jika proyek baru disalin dan dependensi belum tersedia, jalankan:

```bat
composer install
npm install
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
```

Perintah `migrate:fresh --seed` menghapus seluruh data dan membuat ulang data
demo. Gunakan hanya untuk instalasi baru atau ketika memang ingin mereset demo.

## Akun untuk mencoba aplikasi

Semua akun demo menggunakan kata sandi `password`.

| Role | Contoh email |
|---|---|
| Admin | `admin1@lumora.test` |
| Guru | `guru1@lumora.test` |
| Murid | `murid10.1@lumora.test` |
| Wali | `wali1@lumora.test` |

## Solusi ketika localhost tidak terbuka

### `ERR_FAILED` atau “Situs ini tidak dapat dijangkau”

Penyebab paling umum adalah server belum berjalan atau terminal telah ditutup.

1. Tutup tab browser yang gagal.
2. Jalankan kembali `START_LUMORA.bat`.
3. Tunggu sampai tulisan `Server running` muncul.
4. Buka atau refresh `http://127.0.0.1:8000` dengan `Ctrl+F5`.

### Port 8000 sudah digunakan

Pesan `Failed to listen ... port 8000` atau `address already in use` berarti
ada server lama yang masih berjalan. Cari terminal Lumora sebelumnya dan tekan
`Ctrl+C`. Setelah proses berhenti, jalankan launcher kembali.

Untuk memeriksa port melalui PowerShell:

```powershell
Get-NetTCPConnection -LocalPort 8000 -ErrorAction SilentlyContinue
```

### Tampilan lama masih muncul

Jalankan dari folder proyek:

```bat
php artisan optimize:clear
npm run build
```

Kemudian gunakan `Ctrl+F5` pada browser.

### Login akun demo gagal

Reset database demo hanya jika data lama boleh dihapus:

```bat
php artisan migrate:fresh --seed
```

Setelah itu login kembali dengan salah satu akun demo dan password `password`.

### QR realtime tidak aktif

Pastikan aplikasi dijalankan melalui `START_LUMORA.bat` atau:

```bat
composer run dev:realtime
```

Menjalankan `php artisan serve` saja tidak menyalakan Laravel Reverb, queue, dan
scheduler.

## Ringkasan penggunaan harian

```text
Mulai    : klik START_LUMORA.bat
Buka     : http://127.0.0.1:8000
Hentikan : Ctrl+C pada terminal Lumora
Catatan  : terminal harus tetap terbuka
XAMPP    : tidak wajib untuk mode SQLite
```
