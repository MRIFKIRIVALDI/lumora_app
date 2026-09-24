# Lumora

Lumora adalah LMS dan sistem manajemen sekolah responsif berdasarkan `PRD_Lumora.md`. Versi ini merupakan fondasi MVP Laravel 11 yang dapat langsung dijalankan untuk memvalidasi UI, alur empat role, dan skema data inti.

## Menjalankan demo

Mode demo memakai SQLite, jadi Apache dan MySQL tidak wajib. Jalankan dari folder proyek:

```bash
php artisan migrate:fresh --seed
npm install
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`. Semua akun demo memakai password `password`.

| Role | Email |
|---|---|
| Admin | `admin@lumora.test` |
| Guru | `guru@lumora.test` |
| Murid | `murid@lumora.test` |
| Wali | `wali@lumora.test` |

Tidak ada registrasi publik. Admin membuat dan menonaktifkan akun melalui menu **Manajemen Pengguna**.

### Menjalankan QR realtime

Gunakan satu perintah berikut untuk menjalankan aplikasi dan server WebSocket Reverb bersama-sama:

```bash
composer run dev:realtime
```

Kemudian login sebagai Admin, buka menu **Presensi**, isi nama titik, dan pilih **Buka titik QR**. Layar stasiun membuat QR baru setiap detik. Jangan menutup terminal selama aplikasi digunakan.

Untuk pemindaian dari ponsel pada Wi-Fi yang sama, ubah `APP_URL`, `REVERB_HOST`, dan `VITE_REVERB_HOST` menjadi alamat IPv4 komputer (contoh `192.168.1.10`), jalankan `npm run build`, lalu akses `http://IP-KOMPUTER:8000` dari ponsel. Izinkan port 8000 dan 8080 pada Windows Firewall bila diminta.

## Memakai MySQL XAMPP

1. Jalankan Apache dan MySQL dari XAMPP Control Panel.
2. Buat database `lumora` melalui phpMyAdmin.
3. Ubah `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lumora
DB_USERNAME=root
DB_PASSWORD=
```

4. Jalankan `php artisan config:clear` dan `php artisan migrate:fresh --seed`.
5. Untuk Apache, arahkan DocumentRoot/vhost ke folder `public`. Selama pengembangan, `php artisan serve` lebih sederhana.

## Yang sudah berjalan

- Login tertutup, logout, session, rate limit login, dan pemeriksaan akun aktif.
- Empat role dengan middleware otorisasi backend.
- Dashboard adaptif, navigasi desktop/mobile, serta PWA dasar.
- Admin membuat akun dan mengaktifkan/menonaktifkan akun.
- Admin mengubah role; satu Wali dapat memiliki banyak Murid dan tiap Murid maksimal satu Wali.
- Light/dark mode tersimpan di perangkat.
- Admin mengelola rombel, mata pelajaran, Guru pengampu, jadwal, dan kalender akademik.
- Guru/Admin mengelola pertemuan per kelas, kemudian membagikan materi, tugas, dan kuis kepada Murid rombel tersebut.
- Profil profesional opsional mencakup identitas, biodata, alamat, pekerjaan/jabatan, bio, dan kontak darurat.
- Seluruh query akademik/presensi/tugas/keuangan dibatasi berdasarkan relasi role pengguna.
- Beranda Murid berisi kalender mingguan, pekerjaan aktif, kartu kelas, Guru, jadwal, dan progres kehadiran.
- Profil mandiri: nama, email, telepon, foto, dan perubahan kata sandi tervalidasi.
- Skema serta tampilan awal rombel, jadwal, presensi multi-titik, tugas via Drive, dan SPP.
- Seeder data demo.

Laravel Reverb sudah aktif untuk QR dinamis. Integrasi Google, Midtrans/Xendit, FCM, Gemini, dan Redis produksi belum diaktifkan karena membutuhkan kredensial sekolah. Detail lengkap tersedia di [indeks dokumentasi](docs/README.md).

## Dokumentasi

- [Indeks dokumentasi](docs/README.md)
- [Fitur dan status PRD](docs/FEATURES.md)
- [Arsitektur dan model data](docs/ARCHITECTURE.md)
- [Matriks akses setiap role](docs/ACCESS_CONTROL.md)
- [Panduan operasional dan troubleshooting](docs/RUNBOOK.md)
- [Catatan teknis pengembangan](docs/DEVELOPMENT.md)
- [Riwayat perubahan](docs/CHANGELOG.md)

## Verifikasi

```bash
php artisan test
npm run build
```
