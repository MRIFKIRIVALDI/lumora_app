# Arsitektur dan Model Data

## Stack aktif

- Laravel 11 dan PHP 8.2+
- Blade, Tailwind CSS, dan Vite
- SQLite untuk demo lokal; struktur migrasi tetap kompatibel dengan MySQL/XAMPP
- Laravel Reverb dan Laravel Echo untuk QR dinamis realtime
- PHPUnit untuk pengujian backend

Lumora memakai modular monolith: autentikasi, akademik, presensi, profil, keluarga, dan dashboard berada dalam satu aplikasi Laravel dengan satu sumber otorisasi.

## Modul utama

- Identitas: pengguna, role, status akun, dan profil profesional.
- Akademik: tahun ajaran, rombel, wali kelas, mata pelajaran, guru pengampu, dan anggota kelas.
- Pembelajaran: pertemuan, materi, tugas, dan kuis per sesi.
- Keluarga: relasi satu wali ke banyak murid, dengan maksimal satu wali per murid.
- Presensi: masuk/pulang melalui GPS atau token QR dinamis.
- Dashboard: analitik untuk admin/guru serta beranda akademik untuk murid/wali.
- Kalender: agenda akademik umum dan agenda yang ditargetkan ke role.

## Relasi data utama

```text
academic_years
└── school_classes (homeroom_teacher)
    ├── class_students ── users (student)
    └── class_sessions ── subjects + users (teacher)
        └── learning_meetings
            ├── learning_materials
            ├── learning_assignments
            └── learning_quizzes

users (parent) ── parent_student ── users (student)

qr_stations ── qr_tokens ── attendances
users (student) ── spp_bills
```

`class_sessions` menjadi penghubung utama relasi guru, mapel, kelas, dan murid. Karena itu satu guru dapat mengajar banyak kelas tanpa mengubah wali kelas masing-masing.

## QR realtime

Stasiun QR menerbitkan token singkat melalui Reverb. Token diperbarui setiap detik, kedaluwarsa setelah delapan detik, dan hanya dapat digunakan sekali. Setiap stasiun memiliki sasaran `student` atau `teacher`. Admin dapat membuka QR guru dan murid; guru baru dapat membuka QR murid setelah presensi melalui QR admin. Server tetap memvalidasi stasiun, sasaran role, token, masa berlaku, pengguna, serta jenis presensi; animasi di browser bukan sumber validasi.

## Tema visual

Palet aplikasi mengikuti identitas logo Lumora: indigo/biru sebagai warna utama, violet dan lavender sebagai aksen, serta jingga untuk sorotan. Semua role memakai sistem warna yang sama dan mendukung light mode serta dark mode. Preferensi tema disimpan di browser.
