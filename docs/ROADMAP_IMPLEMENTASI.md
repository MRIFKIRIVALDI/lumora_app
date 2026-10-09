# Roadmap Implementasi Lumora dari PRD sampai Dipakai Sekolah

Dokumen ini menjelaskan urutan kerja yang disarankan untuk mengubah Lumora dari
aplikasi lokal menjadi sistem sekolah yang dipakai Admin, Guru, Murid, dan Wali.
Urutan ini dibuat agar setiap tahap memiliki hasil yang dapat diperiksa sebelum
masuk ke tahap berikutnya.

## Gambaran besar

```text
PRD
 ↓
Analisis alur dan hak akses
 ↓
Desain UI/UX dan arsitektur
 ↓
Database dan fondasi aplikasi
 ↓
Implementasi fitur per modul
 ↓
Testing dan perbaikan
 ↓
Staging online
 ↓
Migrasi data sekolah
 ↓
Pilot satu kelas/angkatan
 ↓
Produksi seluruh sekolah
 ↓
Operasional, backup, monitoring, evaluasi
```

---

## Tahap 0 — Menentukan tujuan dan batas proyek

Sebelum membuat kode, tentukan hal berikut:

- Nama sekolah dan unit yang menggunakan Lumora.
- Jumlah Admin, Guru, Murid, dan Wali.
- Apakah sistem digunakan satu sekolah atau beberapa sekolah.
- Tahun ajaran dan semester yang akan dikelola.
- Fitur yang wajib tersedia saat peluncuran pertama.
- Fitur yang boleh menyusul setelah sistem berjalan.
- Siapa pemilik keputusan ketika ada perubahan kebutuhan.
- Siapa operator harian dan siapa penanggung jawab teknis.

### Hasil tahap ini

- Tujuan proyek satu halaman.
- Daftar pengguna dan perkiraan jumlahnya.
- Daftar fitur prioritas.
- Daftar fitur yang belum masuk versi pertama.
- Penanggung jawab sekolah.

Jangan memasukkan semua ide sekaligus ke versi pertama. Untuk peluncuran awal,
prioritaskan login, pengguna, kelas, jadwal, presensi, pembelajaran, nilai,
tagihan, notifikasi, dan laporan.

---

## Tahap 1 — Menyusun dan mengunci PRD

PRD adalah sumber kebutuhan produk. Setiap fitur perlu ditulis dalam format yang
dapat diuji, bukan hanya berupa gambaran umum.

Untuk setiap fitur tuliskan:

1. Nama fitur.
2. Masalah yang diselesaikan.
3. Role yang dapat mengakses.
4. Alur pengguna dari awal sampai selesai.
5. Data yang dibuat atau diubah.
6. Kondisi berhasil.
7. Kondisi gagal dan pesan yang ditampilkan.
8. Aturan keamanan dan privasi.
9. Prioritas: wajib, penting, atau lanjutan.

### Contoh acceptance criteria presensi

- Murid dapat melakukan presensi masuk satu kali per hari.
- Murid dapat melakukan presensi pulang setelah presensi masuk.
- QR memiliki masa berlaku dan tidak dapat digunakan dua kali.
- Guru hanya melihat murid dari kelas yang diampunya.
- Admin dapat melihat rekap seluruh sekolah.
- Murid yang tidak presensi dan tidak memiliki izin diselesaikan sebagai alfa
  oleh proses terjadwal.

### Hasil tahap ini

- `PRD_Lumora.md` yang telah disetujui.
- Matriks role dan permission.
- Daftar acceptance criteria.
- Daftar keputusan yang belum ditentukan.

Jangan mulai implementasi fitur besar yang acceptance criteria-nya belum jelas.

---

## Tahap 2 — Analisis proses sekolah nyata

Validasi PRD dengan pihak sekolah, bukan hanya asumsi pengembang.

Wawancarai minimal:

- Kepala sekolah atau pemilik kebijakan.
- Admin/operator sekolah.
- Perwakilan Guru.
- Perwakilan Murid.
- Perwakilan Wali.
- Bendahara jika SPP akan digunakan.

Petakan proses nyata berikut:

- Pembuatan tahun ajaran, kelas, dan rombel.
- Penempatan wali kelas.
- Penempatan Guru dan mata pelajaran.
- Pembuatan jadwal dan pertemuan berulang.
- Presensi dan penanganan alfa.
- Pengajuan izin/sakit dan bukti dokumen.
- Materi, tugas, kuis, dan penilaian.
- Rapor dan persetujuan Admin.
- Penerbitan dan pembayaran tagihan.
- Pengumuman dan notifikasi.
- Proses pindah kelas, lulus, atau akun dinonaktifkan.

### Hasil tahap ini

- Diagram alur operasional sekolah.
- Daftar istilah resmi yang dipakai sekolah.
- Daftar aturan yang berbeda dari asumsi PRD.
- Contoh file Excel atau formulir lama yang akan diimpor.

---

## Tahap 3 — Menentukan desain UI/UX

Buat rancangan sebelum memperbanyak halaman dan komponen.

Tentukan:

- Navigasi desktop dan mobile.
- Dashboard masing-masing role.
- Warna, tipografi, ikon, status, dan komponen formulir.
- Kondisi kosong, loading, sukses, gagal, dan tanpa akses.
- Tombol kembali pada setiap halaman detail.
- Ukuran teks dan tombol untuk perangkat mobile.
- Bahasa Indonesia dan kemungkinan bahasa tambahan.
- Aksesibilitas: kontras, label formulir, fokus keyboard, dan ukuran sentuh.

### Hasil tahap ini

- Wireframe setiap alur utama.
- Design system.
- Prototipe yang dapat diklik.
- Daftar komponen UI yang digunakan ulang.

Validasi minimal dengan satu orang dari setiap role sebelum coding dilanjutkan.

---

## Tahap 4 — Menentukan arsitektur teknis

Untuk Lumora, struktur yang disarankan:

```text
Browser/PWA
    ↓ HTTPS
Nginx atau web server
    ↓
Laravel application
    ├── PostgreSQL (produksi)
    ├── Redis (cache, session, queue)
    ├── Queue worker
    ├── Scheduler
    ├── Laravel Reverb (QR realtime)
    └── Object storage (file materi/bukti/tugas)
```

### Keputusan yang perlu dibuat

- SQLite untuk lokal, PostgreSQL atau MySQL untuk produksi.
- Cloud server atau server fisik sekolah.
- Lokasi data center.
- Penyimpanan file dan batas ukuran file.
- Penyedia email/notifikasi.
- Strategi backup dan masa retensi.
- Domain resmi sekolah.
- Lingkungan local, testing, staging, dan production.
- Mekanisme monitoring dan pemberitahuan gangguan.

### Hasil tahap ini

- Diagram arsitektur.
- Daftar environment dan rahasia masing-masing.
- Rencana kapasitas awal.
- Rencana backup dan pemulihan.

Jangan memakai credential production di laptop developer, repository, atau file
`.env` yang di-commit.

---

## Tahap 5 — Menyiapkan fondasi proyek

Langkah teknis awal:

1. Buat repository Git privat.
2. Buat branch `main`, `develop`, dan branch fitur.
3. Siapkan `.env.example` tanpa password asli.
4. Siapkan Laravel, Composer, Node.js, dan Vite.
5. Aktifkan autentikasi, session, CSRF, rate limit, dan logging.
6. Buat layout, navigasi, tema, ikon, dan komponen dasar.
7. Siapkan testing database terisolasi.
8. Tambahkan CI untuk test dan build.
9. Catat aturan coding dan proses review.

### Hasil tahap ini

- Proyek dapat di-install dari repository baru.
- `php artisan test` dapat dijalankan.
- `npm run build` berhasil.
- Login dasar dan layout utama tersedia.
- Dokumentasi setup dapat diikuti orang lain.

---

## Tahap 6 — Mendesain database dan migration

Mulai dari entitas dan relasi, bukan langsung dari tampilan.

Urutan Lumora yang disarankan:

1. `users` dan role.
2. Tahun ajaran, jurusan, kelas, dan keanggotaan Murid.
3. Relasi Wali–Murid.
4. Mata pelajaran, Guru pengampu, dan jadwal.
5. Pertemuan dan materi pembelajaran.
6. Tugas, pengumpulan, kuis, dan jawaban.
7. Presensi, QR, izin, dan sakit.
8. Nilai dan rapor.
9. Tagihan dan pembayaran.
10. Notifikasi dan kalender akademik.

Setiap perubahan database harus dibuat sebagai migration baru. Jangan mengedit
migration lama yang sudah pernah berjalan di production.

### Hasil tahap ini

- Migration lengkap.
- Foreign key dan indeks.
- Aturan unik untuk mencegah data ganda.
- Seeder data demo.
- Dokumentasi struktur pada `docs/DATABASE.md`.

---

## Tahap 7 — Implementasi per modul

Kerjakan berdasarkan prioritas dan selesaikan satu alur utuh sebelum berpindah.

### Modul A — Autentikasi dan pengguna

- Login dan logout.
- Akun aktif/nonaktif.
- Penggantian password.
- Profil profesional.
- Role dan middleware.
- Relasi Wali–Murid.

### Modul B — Struktur akademik

- Tahun ajaran.
- Kelas dan rombel.
- Wali kelas.
- Mata pelajaran.
- Guru pengampu.
- Jadwal berulang.
- Kalender akademik.

### Modul C — Presensi

- QR Admin dan QR Guru.
- Scanner kamera.
- Presensi masuk/pulang.
- Presensi manual Guru.
- Izin/sakit dengan bukti.
- Persetujuan.
- Alfa otomatis.
- Rekap dan grafik.

### Modul D — Pembelajaran

- Pertemuan.
- Materi dengan preview.
- Tugas dan unggah jawaban.
- Kuis pilihan ganda.
- Nilai otomatis.
- Notifikasi Murid.

### Modul E — Nilai dan rapor

- Draft nilai oleh Guru.
- Pengajuan rapor.
- Persetujuan Admin.
- Tampilan baca Murid/Wali.

### Modul F — Keuangan

- Penerbitan SPP.
- Grafik per bulan.
- Tagihan Murid/Wali.
- Payment sandbox.
- Payment gateway produksi setelah akun resmi tersedia.

### Hasil setiap modul

- Alur role terkait selesai.
- Validasi server-side.
- Tampilan sukses/gagal/kosong.
- Test feature.
- Dokumentasi fitur dan perubahan.

---

## Tahap 8 — Testing dan quality gate

Testing dilakukan sebelum staging, bukan setelah sekolah mulai memakai.

### Jenis testing

- Unit test untuk perhitungan dan helper.
- Feature test untuk alur HTTP dan otorisasi.
- Test role: Admin, Guru, Murid, Wali.
- Test relasi data dan batas akses.
- Test upload file dan validasi ukuran.
- Test presensi QR kedaluwarsa dan penggunaan ulang.
- Test kuis dan perhitungan nilai.
- Test pembayaran sandbox.
- Test mobile dan desktop.
- Test browser utama.
- Test backup dan restore.
- Load test login, presensi, dan kuis bersamaan.

### Quality gate sebelum staging

- Tidak ada error kritis.
- Seluruh test otomatis lulus.
- Migration dapat dijalankan dari database kosong.
- Seeder dapat membuat data demo.
- Tidak ada credential rahasia di repository.
- Semua halaman memiliki otorisasi backend.
- Backup dapat dipulihkan di server lain.

---

## Tahap 9 — Menyiapkan staging online

Staging adalah server latihan yang menyerupai production tetapi tidak memakai
data pribadi asli.

Urutannya:

1. Sewa VPS/cloud server.
2. Pilih Linux LTS dan data center Indonesia jika tersedia.
3. Buat user deploy non-root.
4. Aktifkan firewall dan SSH key.
5. Pasang Nginx, PHP, Composer, Node.js, PostgreSQL, Redis, dan Supervisor.
6. Pasang aplikasi dari repository.
7. Buat database staging.
8. Isi konfigurasi environment staging.
9. Jalankan migration dan seeder demo.
10. Konfigurasi HTTPS.
11. Jalankan queue, scheduler, dan Reverb.
12. Pasang logging dan backup.
13. Uji dari komputer dan ponsel.

### Hasil tahap ini

- URL staging.
- Deployment dapat diulang.
- QR realtime berjalan.
- Backup dan restore staging berhasil.
- Operator dapat mencoba seluruh alur.

---

## Tahap 10 — Menyiapkan data sekolah

Jangan langsung mengimpor data mentah ke production.

Buat template resmi untuk:

- Akun Admin, Guru, Murid, dan Wali.
- NIK, NIS, NISN, NIP, email, dan nomor telepon.
- Relasi Wali–Murid.
- Tahun ajaran dan kelas.
- Anggota setiap kelas.
- Mata pelajaran dan Guru pengampu.
- Jadwal.
- Tagihan awal jika diperlukan.

Lakukan proses:

1. Minta data dari sekolah melalui kanal aman.
2. Hapus kolom yang tidak diperlukan.
3. Validasi email, ID, kelas, dan relasi.
4. Cari data duplikat.
5. Buat laporan error impor.
6. Impor ke staging.
7. Minta operator memeriksa sampel data.
8. Perbaiki mapping.
9. Buat backup sebelum impor production.
10. Impor ke production setelah disetujui.

Password awal sebaiknya dibuat sementara dan pengguna diwajibkan menggantinya
saat login pertama.

---

## Tahap 11 — Pilot terbatas

Jangan langsung membuka untuk seluruh sekolah.

Mulai dengan:

- Satu Admin/operator.
- Beberapa Guru.
- Satu kelas atau satu angkatan.
- Beberapa Wali.

Uji selama satu sampai dua minggu:

- Login dan profil.
- Jadwal dan pertemuan.
- Presensi pagi dan pulang.
- Izin/sakit.
- Materi, tugas, dan kuis.
- Nilai dan rapor.
- Tagihan dan payment sandbox.
- Notifikasi.
- Akses mobile dari jaringan seluler.

Catat semua masalah berdasarkan prioritas: kritis, tinggi, sedang, dan rendah.

### Kriteria lanjut ke production penuh

- Tidak ada masalah kritis.
- Operator dapat menjalankan pekerjaan utama tanpa bantuan developer.
- Presensi berhasil pada jam sibuk.
- Backup dan restore sudah diuji.
- Guru dan Wali memahami alur dasar.
- Sekolah menyetujui tampilan laporan dan data.

---

## Tahap 12 — Go-live production

Sebelum hari peluncuran:

1. Bekukan perubahan besar.
2. Buat backup database dan file.
3. Pastikan domain dan HTTPS aktif.
4. Pastikan queue, scheduler, dan Reverb aktif.
5. Pastikan kapasitas server cukup.
6. Impor data final.
7. Buat akun operator.
8. Kirim instruksi login.
9. Siapkan kanal bantuan.
10. Tentukan jadwal pemantauan intensif selama minggu pertama.

Pada hari peluncuran:

- Pantau login.
- Pantau response time.
- Pantau error log.
- Pantau presensi.
- Pantau kapasitas CPU, RAM, disk, dan database.
- Simpan daftar insiden dan waktu kejadiannya.

Jangan melakukan reset database demo di production.

---

## Tahap 13 — Operasional harian dan pemeliharaan

### Setiap hari

- Periksa server aktif.
- Periksa queue gagal.
- Periksa error log.
- Periksa kapasitas disk.
- Pastikan backup terakhir berhasil.
- Tanggapi laporan pengguna.

### Setiap minggu

- Uji sebagian backup.
- Tinjau akun yang tidak aktif.
- Tinjau presensi dan pengajuan yang tertunda.
- Tinjau penggunaan storage.
- Periksa pembaruan keamanan.

### Setiap bulan

- Uji restore backup lengkap.
- Tinjau biaya server.
- Tinjau performa database.
- Tinjau akun dan akses Admin.
- Tinjau error yang berulang.
- Rencanakan perubahan berikutnya.

### Setiap rilis

1. Buat branch fitur.
2. Jalankan test dan build.
3. Review perubahan.
4. Backup production.
5. Deploy ke staging.
6. Uji staging.
7. Deploy production pada jam rendah.
8. Jalankan migration jika ada.
9. Pantau log.
10. Siapkan rollback.

---

## Tahap 14 — Keamanan dan tata kelola data

Karena Lumora memproses data Murid, Guru, Wali, presensi, nilai, dan keuangan,
sekolah perlu menetapkan:

- Siapa yang boleh melihat setiap data.
- Berapa lama data disimpan.
- Siapa yang boleh mengekspor data.
- Prosedur penghapusan atau koreksi data.
- Persetujuan penggunaan foto dan dokumen.
- Prosedur ketika akun dibajak.
- Prosedur ketika terjadi kebocoran data.
- Daftar orang yang memiliki akses server.
- Jadwal backup dan siapa yang memeriksanya.

Gunakan HTTPS, password kuat, 2FA untuk Admin, pembatasan akses server,
backup terenkripsi, dan audit log untuk aktivitas penting.

---

## Tahap 15 — Evaluasi dan pengembangan lanjutan

Setelah satu periode berjalan, evaluasi menggunakan data nyata:

- Berapa banyak pengguna aktif.
- Jam paling sibuk.
- Rata-rata waktu respons.
- Jumlah error.
- Persentase presensi digital.
- Jumlah tugas yang dikumpulkan.
- Jumlah tiket bantuan.
- Biaya per bulan.
- Fitur yang paling sering digunakan.

Gunakan hasil tersebut untuk menentukan apakah perlu menambah kapasitas server,
memisahkan database, menambah Redis, menggunakan storage eksternal, atau
mengembangkan fitur baru.

## Checklist ringkas sebelum dipakai seluruh sekolah

- [ ] PRD disetujui sekolah.
- [ ] Role dan hak akses diverifikasi.
- [ ] Database production menggunakan PostgreSQL atau MySQL.
- [ ] Domain dan HTTPS aktif.
- [ ] Backup otomatis dan restore sudah diuji.
- [ ] Queue, scheduler, dan Reverb berjalan.
- [ ] Data sekolah tervalidasi.
- [ ] Pilot berhasil.
- [ ] Operator sudah dilatih.
- [ ] Kanal bantuan tersedia.
- [ ] Monitoring aktif.
- [ ] Rencana rollback tersedia.
- [ ] Dokumentasi operasional dibagikan kepada sekolah.
