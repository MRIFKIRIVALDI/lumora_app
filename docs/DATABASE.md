# Struktur Database Lumora

## Database yang digunakan

Lumora dalam mode pengembangan menggunakan **SQLite 3** melalui Laravel.
Seluruh data tersimpan dalam satu file:

```text
database/database.sqlite
```

SQLite tidak membutuhkan server database terpisah. Karena itu Apache dan MySQL
XAMPP tidak wajib dinyalakan untuk menjalankan demo Lumora. Laravel membuka file
tersebut secara langsung menggunakan konfigurasi `DB_CONNECTION=sqlite` pada
file `.env`.

Saat dokumen ini diperbarui, database memiliki 36 tabel. Sebagian tabel adalah
tabel aplikasi Lumora dan sebagian lainnya merupakan tabel internal Laravel
untuk session, cache, queue, serta migrasi.

## Gambaran relasi utama

```text
users
├── parent_student ───────────── hubungan Wali dengan Murid
├── class_students ── school_classes
│                       ├── academic_years
│                       ├── majors
│                       └── class_sessions
│                           ├── subjects
│                           ├── users (Guru)
│                           ├── learning_meetings
│                           │   ├── materials
│                           │   ├── assignments ── assignment_submissions
│                           │   ├── quizzes
│                           │   │   ├── quiz_questions
│                           │   │   └── quiz_attempts ── quiz_answers
│                           │   └── meeting_attendances
│                           └── report_cards ── report_grades
├── attendances ── qr_stations ── qr_tokens
├── leave_requests
├── spp_bills ── payment_transactions
└── user_notifications
```

## Kelompok tabel aplikasi

### Pengguna dan relasi

| Tabel | Fungsi |
|---|---|
| `users` | Menyimpan Admin, Guru, Murid, Wali, login, dan profil |
| `parent_student` | Menghubungkan satu Murid dengan satu Wali; satu Wali dapat mempunyai beberapa Murid |
| `user_notifications` | Notifikasi materi, tugas, kuis, dan informasi pengguna |

Role tidak dipisah ke empat tabel. Semua akun berada pada `users` dan dibedakan
oleh kolom `role`.

### Akademik

| Tabel | Fungsi |
|---|---|
| `academic_years` | Tahun ajaran aktif |
| `majors` | Jurusan atau program sekolah |
| `school_classes` | Rombongan belajar dan wali kelas |
| `class_students` | Keanggotaan Murid dalam kelas |
| `subjects` | Mata pelajaran |
| `class_sessions` | Hubungan kelas, mata pelajaran, Guru, hari, dan jam |
| `school_calendar` | Libur, ujian, dan agenda sekolah |

Alur relasinya adalah: Admin membuat kelas dan mata pelajaran, lalu
`class_sessions` mengaitkan sebuah kelas dengan mata pelajaran serta Guru
pengampunya.

### Pembelajaran

| Tabel | Fungsi |
|---|---|
| `learning_meetings` | Pertemuan tunggal atau hasil generate berulang |
| `materials` | Materi teks, tautan, video, atau dokumen |
| `assignments` | Tugas dalam kelas/pertemuan |
| `assignment_submissions` | Jawaban tugas setiap Murid |
| `quizzes` | Kuis yang dibuat Guru |
| `quiz_questions` | Bank soal pilihan ganda |
| `quiz_attempts` | Percobaan dan nilai kuis Murid |
| `quiz_answers` | Jawaban Murid pada setiap soal |
| `meeting_attendances` | Presensi manual pada sebuah pertemuan |

### Presensi dan izin

| Tabel | Fungsi |
|---|---|
| `qr_stations` | Stasiun QR yang dibuka Admin atau Guru |
| `qr_tokens` | Token QR dinamis yang berubah dan hanya dapat dipakai sekali |
| `attendances` | Kehadiran harian, jam masuk/pulang, lokasi, serta status |
| `leave_requests` | Pengajuan izin/sakit, bukti, dan persetujuan |

### Nilai dan rapor

| Tabel | Fungsi |
|---|---|
| `report_cards` | Header rapor per kelas/mapel/semester dan status persetujuan |
| `report_grades` | Nilai tugas, kuis, UTS, UAS, nilai akhir, dan catatan Guru |

### Keuangan

| Tabel | Fungsi |
|---|---|
| `spp_bills` | Tagihan SPP per Murid, bulan, tahun, nominal, dan status |
| `payment_transactions` | Referensi pembayaran sandbox, pembayar, metode, nominal, dan waktu bayar |

Kombinasi Murid, bulan, dan tahun pada `spp_bills` dibuat unik agar satu Murid
tidak mempunyai dua tagihan SPP untuk periode yang sama.

### Informasi

| Tabel | Fungsi |
|---|---|
| `announcements` | Berita/pengumuman sekolah dan target role |

## Tabel internal Laravel

Tabel berikut mendukung sistem dan biasanya tidak perlu diedit secara manual:

- `migrations` mencatat perubahan struktur yang sudah dijalankan;
- `sessions` menyimpan sesi login;
- `cache` dan `cache_locks` menyimpan cache;
- `jobs`, `job_batches`, dan `failed_jobs` mendukung queue;
- `password_reset_tokens` mendukung token reset kata sandi.

## Cara melihat struktur melalui terminal

Pastikan Command Prompt berada di folder proyek, lalu jalankan:

```bat
php artisan db:show --counts
```

Perintah tersebut menampilkan jenis database, lokasi file, daftar tabel, dan
jumlah baris. Untuk melihat kolom dan indeks suatu tabel, gunakan:

```bat
php artisan db:table users
php artisan db:table school_classes
php artisan db:table spp_bills
```

Struktur resmi yang membangun database berada di folder:

```text
database/migrations
```

Data demo awal berada pada:

```text
database/seeders/DatabaseSeeder.php
```

## Cara melihat secara visual

File `database/database.sqlite` dapat dibuka dengan aplikasi pembaca SQLite,
misalnya DB Browser for SQLite atau ekstensi SQLite pada Visual Studio Code.

Langkah umumnya:

1. Buka aplikasi pembaca SQLite.
2. Pilih **Open Database**.
3. Pilih `database/database.sqlite` dari folder Lumora.
4. Gunakan bagian **Database Structure** untuk melihat tabel dan kolom.
5. Gunakan bagian **Browse Data** untuk melihat isi tabel.

Sebaiknya tutup server Lumora sebelum mengubah data melalui aplikasi eksternal.
Buat salinan `database.sqlite` terlebih dahulu karena perubahan manual dapat
merusak relasi atau membuat data aplikasi tidak konsisten.

## Beralih ke MySQL

Struktur Laravel dapat dipindahkan ke MySQL dengan mengubah variabel `DB_*`
pada `.env`, membuat database kosong, lalu menjalankan migrasi. Namun untuk
pengembangan saat ini, SQLite lebih sederhana karena tidak memerlukan layanan
MySQL dan seluruh data mudah dicadangkan sebagai satu file.
