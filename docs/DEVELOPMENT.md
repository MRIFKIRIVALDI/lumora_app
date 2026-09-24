# Catatan Pengembangan Lumora

PRD versi 1.2 telah ditelaah sebagai acuan produk. Implementasi saat ini memakai monolit modular Laravel 11. SQLite menjadi default demo agar aplikasi mudah dijalankan, sedangkan migrasi disiapkan tetap kompatibel dengan MySQL/XAMPP.

Status fitur secara rinci berada di [FEATURES.md](FEATURES.md). Dokumen ini berfokus pada keputusan teknis dan urutan kerja berikutnya.

## Keputusan teknis yang berlaku

- Registrasi publik ditutup. Admin membuat akun dan mengatur role serta statusnya.
- Pemeriksaan hak akses dilakukan di server dan data dibatasi melalui scope role.
- Relasi keluarga menggunakan aturan satu murid maksimal satu wali dan satu wali dapat memiliki banyak murid.
- Relasi guru–murid tidak dibuat sebagai pasangan langsung, melainkan melalui guru → sesi/mapel → rombel → murid.
- Wali kelas terkait pada rombel; guru mapel dapat mengajar banyak rombel dan tingkat.
- Pertemuan menjadi wadah materi, tugas, dan kuis agar konten selalu memiliki konteks kelas, mapel, guru, dan waktu.
- Dashboard analitik hanya tersedia bagi admin dan guru. Murid dan wali menerima beranda operasional yang lebih relevan.
- QR presensi divalidasi server, memiliki masa berlaku delapan detik, dan hanya dapat digunakan sekali.
- Laravel Reverb menangani pembaruan token realtime; aplikasi tetap harus menolak token yang invalid meskipun koneksi realtime terputus.
- Seluruh role memakai palet logo Lumora dan satu mekanisme light/dark mode.
- Rahasia integrasi hanya disimpan di `.env` dan tidak boleh masuk repository.

## Prioritas pengembangan berikutnya

1. Melengkapi edit, hapus, validasi konflik, dan operasi massal untuk master akademik.
2. Menambahkan konfigurasi lokasi sekolah, radius geofence, jam terlambat, dan prasyarat kehadiran guru sebelum stasiun QR aktif.
3. Melengkapi penyusun soal, pengerjaan kuis, pengumpulan tugas, penilaian, dan progres nyata pada kartu kelas.
4. Mengaktifkan Google SSO tertutup dan Google Drive menggunakan kredensial sandbox sekolah.
5. Mengintegrasikan payment gateway sandbox dengan verifikasi signature dan idempotensi webhook.
6. Mengembangkan izin, rekap/ekspor presensi, laporan, raport, notifikasi eksternal, dan rollover tahun ajaran.
7. Menilai kebutuhan chat dan proctoring setelah alur inti stabil serta kebijakan privasi disepakati.

## Keputusan yang diperlukan sebelum produksi

- Identitas sekolah, domain, koordinat, radius geofence, jam operasional, dan kebijakan check-out.
- Pilihan Midtrans atau Xendit, akun Google Cloud/Workspace, dan konfigurasi domain OAuth.
- Struktur kurikulum, bobot nilai, template raport, aturan kenaikan kelas, dan arsip tahun ajaran.
- Provider OTP/2FA, prosedur pemulihan akun, consent wali, serta kebijakan retensi data dan foto.
- Infrastruktur queue, Redis bila digunakan, TLS, backup database/file, monitoring, dan prosedur pemulihan bencana.

## Aturan kualitas

- Fitur baru harus memiliki validasi request dan otorisasi server.
- Perubahan kritis pada login, akses role, presensi, pembayaran, dan relasi akademik harus disertai pengujian otomatis.
- Webhook harus memvalidasi signature dan idempotensi.
- Database pengujian menggunakan SQLite `:memory:` yang terpisah dari `database/database.sqlite`; pemisahan ini mencegah `RefreshDatabase` menghapus data demo.
- Sebelum diserahkan, jalankan `php artisan test` dan `npm run build`.
- Setelah perubahan fitur, perbarui dokumentasi sesuai aturan pada [indeks dokumentasi](README.md).
