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
- Pembelajaran: pertemuan, pratinjau materi, tugas, kuis PG, jawaban, dan penilaian otomatis per sesi.
- Keluarga: relasi satu wali ke banyak murid, dengan maksimal satu wali per murid.
- Presensi: masuk/pulang melalui GPS atau token QR dinamis.
- Dashboard: analitik untuk admin/guru serta beranda akademik untuk murid/wali.
- Kalender: agenda akademik umum dan agenda yang ditargetkan ke role.
- Keuangan: penerbitan tagihan SPP individual/massal dan rekonsiliasi status lunas.
- Notifikasi internal: pemberitahuan materi, tugas, dan kuis berdasarkan anggota rombel.
- Rapor: draft nilai per sesi/mapel, komponen nilai per murid, pengajuan guru, dan persetujuan admin.

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

Untuk akun guru, `UserDataScope::sessionIds()` selalu memfilter `class_sessions.teacher_id` menggunakan ID guru aktif. Pembatasan server ini digunakan oleh akademik, pertemuan, materi, tugas, kuis, roster, dan analitik.

## Alur pembelajaran dan notifikasi

1. Guru membuat pertemuan tunggal atau berulang pada sesi yang diampu.
2. Materi, tugas, dan kuis selalu terikat ke sebuah pertemuan.
3. Saat konten dibagikan, server membuat `user_notifications` hanya untuk anggota aktif rombel terkait.
4. Materi teks, gambar, PDF, dan TXT dapat dipratinjau melalui endpoint terotorisasi. Format Office menyediakan ringkasan dan unduhan karena dukungan render bergantung pada browser.
5. Guru mengisi bank soal PG. Murid mengirim satu percobaan dan server menghitung nilai berdasarkan jawaban benar.

## Alur tagihan

Admin menerbitkan tagihan untuk satu murid atau seluruh murid aktif. Kombinasi murid, bulan, dan tahun dibuat unik agar tidak menggandakan tagihan. Admin mengelola status `pending/paid` serta waktu pelunasan. Murid dan wali membaca tagihan melalui cakupan relasi `UserDataScope::studentIds()`.

## Alur nilai dan rapor

1. Guru membuat `report_cards` untuk kombinasi sesi kelas dan semester yang diampu.
2. Guru mengisi `report_grades` seluruh roster: tugas 25%, kuis 20%, UTS 25%, dan UAS 30%.
3. Server menghitung nilai akhir serta predikat A–E; guru tidak dapat mengubah data saat status `submitted` atau `approved`.
4. Guru mengajukan rapor kepada admin. Admin menyetujui atau mengembalikan dengan catatan.
5. Murid dan wali hanya dapat membaca baris nilai terkait setelah status rapor `approved`.

## QR realtime

Stasiun QR menerbitkan token singkat melalui Reverb. Token diperbarui setiap detik, kedaluwarsa setelah delapan detik, dan hanya dapat digunakan sekali. Setiap stasiun memiliki sasaran `student` atau `teacher`. Admin dapat membuka QR guru dan murid; guru baru dapat membuka QR murid setelah presensi melalui QR admin. Server tetap memvalidasi stasiun, sasaran role, token, masa berlaku, pengguna, serta jenis presensi; animasi di browser bukan sumber validasi.

## Tema visual

Palet aplikasi mengikuti identitas logo Lumora. Claymorphism dipakai sebagai permukaan utama dan glassmorphism ringan pada navigasi/header. Ikon memakai komponen SVG internal agar konsisten lintas perangkat. Semua role mendukung light/dark mode dan preferensinya disimpan di browser.

Komponen ikon berada di `resources/views/components/icon.blade.php`. Ikon dirender sebagai SVG garis berbasis `currentColor`, sehingga ukuran dan warnanya dikendalikan melalui atribut serta CSS tanpa pustaka eksternal. Halaman Akun menggunakan warna kategori berbeda untuk profil, akademik, tugas, presensi, keuangan, tampilan, bahasa, informasi, dan logout.
