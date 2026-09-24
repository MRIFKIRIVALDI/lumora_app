# Panduan Operasional

## Menjalankan dari awal

Prasyarat: PHP 8.2+, Composer, Node.js/npm, serta ekstensi PHP yang dibutuhkan Laravel.

```bash
composer install
npm install
php artisan migrate:fresh --seed
npm run build
composer run dev:realtime
```

Buka `http://127.0.0.1:8000`. Perintah terakhir menjalankan server Laravel, worker queue, Vite, dan Laravel Reverb. `migrate:fresh` menghapus seluruh tabel dan hanya aman untuk data demo/development.

Untuk penggunaan harian tanpa menghapus data:

```bash
php artisan migrate
composer run dev:realtime
```

## Akun demo

Semua akun memakai kata sandi `password`.

| Role | Email |
|---|---|
| Admin | `admin@lumora.test` |
| Guru | `teacher@lumora.test` |
| Murid | `student@lumora.test` |
| Wali | `parent@lumora.test` |

## Pengujian

```bash
php artisan test
npm run build
```

Pengujian otomatis memakai database terisolasi/in-memory, sehingga tidak boleh bergantung pada isi database demo lokal.

## Akses dari ponsel di jaringan lokal

1. Pastikan komputer dan ponsel berada pada Wi-Fi yang sama.
2. Jalankan Laravel pada semua interface, misalnya `php artisan serve --host=0.0.0.0`.
3. Gunakan alamat IPv4 komputer, bukan `127.0.0.1`, dari ponsel.
4. Sesuaikan `APP_URL`, `VITE_REVERB_HOST`, dan konfigurasi Reverb dengan alamat tersebut, lalu bangun ulang aset.
5. Izinkan port aplikasi dan Reverb pada Windows Firewall bila diperlukan.

## Troubleshooting

### Tidak dapat login

- Pastikan migrasi dan seeder sudah berjalan.
- Periksa akun berstatus aktif dan gunakan email demo yang tepat.
- Jalankan `php artisan optimize:clear` setelah mengubah `.env`.
- Pastikan aplikasi menunjuk database yang sama dengan database yang diisi seeder.

### QR tidak bergerak atau status Reverb offline

- Jalankan `composer run dev:realtime`, bukan hanya `php artisan serve`.
- Cocokkan `REVERB_APP_ID`, key, secret, host, port, dan scheme antara backend dan variabel `VITE_REVERB_*`.
- Setelah mengubah environment frontend, jalankan ulang Vite atau `npm run build`.
- Periksa apakah port Reverb dipakai proses lain atau diblokir firewall.

### Perubahan tampilan tidak muncul

- Jalankan `npm run dev` saat mengembangkan atau `npm run build` untuk aset produksi.
- Bersihkan cache dengan `php artisan optimize:clear`.
- Lakukan hard refresh pada browser.

### MySQL/XAMPP

Ubah `DB_CONNECTION`, host, port, nama database, pengguna, dan kata sandi di `.env`, lalu jalankan `php artisan migrate --seed`. Jangan menjalankan `migrate:fresh` pada database yang berisi data penting.
