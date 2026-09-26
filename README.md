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

Buka `http://127.0.0.1:8000`. Seluruh akun simulasi memakai password `password`.

### Akun admin dan guru

| Role | Akun |
|---|---|
| Admin | `admin1@lumora.test`, `admin2@lumora.test`, `admin3@lumora.test` |
| Guru | `guru1@lumora.test`, `guru2@lumora.test`, `guru3@lumora.test`, `guru4@lumora.test`, `guru5@lumora.test`, `guru6@lumora.test` |

### Akun murid per kelas

| Kelas | Akun |
|---|---|
| Kelas 10 A | `murid10.1@lumora.test`, `murid10.2@lumora.test`, `murid10.3@lumora.test`, `murid10.4@lumora.test`, `murid10.5@lumora.test`, `murid10.6@lumora.test`, `murid10.7@lumora.test` |
| Kelas 11 A | `murid11.1@lumora.test`, `murid11.2@lumora.test`, `murid11.3@lumora.test`, `murid11.4@lumora.test`, `murid11.5@lumora.test`, `murid11.6@lumora.test`, `murid11.7@lumora.test` |
| Kelas 12 A | `murid12.1@lumora.test`, `murid12.2@lumora.test`, `murid12.3@lumora.test`, `murid12.4@lumora.test`, `murid12.5@lumora.test`, `murid12.6@lumora.test`, `murid12.7@lumora.test` |

### Akun wali

`wali1@lumora.test` sampai `wali10@lumora.test`. Wali 1–4 masing-masing memiliki tiga anak, wali 5–7 masing-masing memiliki dua anak, dan wali 8–10 masing-masing memiliki satu anak.

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
- Bottom navigation tetap lima item; semua menu tambahan sesuai role ditampilkan pada Dashboard.
- Dashboard admin memiliki sidebar pengelolaan sekolah bertingkat, pusat kendali operasional, metrik akademik/presensi, kapasitas rombel, dan filter direktori per role.
- Admin membuat akun dan mengaktifkan/menonaktifkan akun.
- Admin mengubah role; satu Wali dapat memiliki banyak Murid dan tiap Murid maksimal satu Wali.
- Light/dark mode tersimpan di perangkat.
- Pilihan Bahasa Indonesia/English tersedia di menu Akun.
- Pemindai kamera QR tersedia bagi Guru dan Murid.
- Admin mengelola rombel, mata pelajaran, Guru pengampu, jadwal, dan kalender akademik.
- Guru mengelola materi, tugas, kuis PG, dan presensi manual pada setiap pertemuan tunggal maupun hasil generate berulang.
- Murid dapat mempratinjau materi, mengunduh berkas, mengunggah tugas, mengerjakan kuis, dan menerima nilai otomatis.
- Admin mengatur struktur kelas dan jadwal; hanya Guru pengampu yang mengelola pertemuan, materi, tugas, dan kuis.
- Guru dapat menghasilkan pola pertemuan harian atau mingguan dalam satu klik, lengkap dengan rentang tanggal, hari, jam, dan lokasi.
- Murid atau Wali dapat mengajukan izin/sakit dengan bukti; Guru terkait atau Admin menyetujui/menolak.
- Rekap presensi harian dan grafik Admin membedakan hadir, sakit, izin, dan alfa; alfa difinalisasi otomatis oleh scheduler.
- Murid dapat membaca isi materi serta mengunggah atau mengganti file jawaban tugas (maksimal 10 MB).
- Profil profesional opsional mencakup NIK, biodata, alamat, bio, dan kontak darurat; profil Murid memiliki NIS/NISN tanpa pekerjaan, sedangkan Guru memiliki NIP dan jabatan/keahlian.
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
