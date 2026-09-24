# Status Fitur Lumora

Keterangan: **Berjalan** berarti tersedia dan dapat dicoba; **Fondasi** berarti struktur atau tampilan awal sudah ada tetapi alur belum lengkap; **Belum** berarti masih berada di roadmap.

| Area | Status | Catatan |
|---|---|---|
| Login akun lokal dan registrasi tertutup | Berjalan | Akun dibuat admin; terdapat pembatasan percobaan login |
| Login Google dan 2FA | Belum | Memerlukan OAuth dan kebijakan sekolah |
| Manajemen pengguna, role, dan status | Berjalan | Admin dapat mengubah role dan status akun |
| Profil profesional dan foto | Berjalan | Field opsional, kontak, alamat, bio, dan data identitas |
| Relasi satu wali ke banyak murid | Berjalan | Setiap murid dibatasi maksimal satu wali |
| Light mode dan dark mode | Berjalan | Berlaku pada seluruh role |
| Rombel, wali kelas, mapel, dan guru | Berjalan | Pembuatan dan penempatan utama tersedia; penyuntingan massal belum lengkap |
| Relasi guru–murid lintas kelas | Berjalan | Dibentuk melalui sesi kelas dan roster |
| Pertemuan, materi, tugas, dan kuis | Berjalan | Guru dapat membagikan konten per sesi; pengerjaan kuis lengkap belum tersedia |
| Dashboard admin | Berjalan | Grafik kolom, garis, batang, dan pai dalam cakupan MVP |
| Dashboard guru | Berjalan | Analitik dibatasi pada kelas yang terhubung |
| Beranda murid | Berjalan | Kalender mingguan, agenda, tugas, pelajaran, dan kartu kelas |
| Beranda wali | Berjalan | Ringkasan anak terhubung; pemantauan terperinci masih dikembangkan |
| Kalender akademik berbasis role | Berjalan | Agenda umum dan agenda pelajaran murid ditampilkan |
| Presensi masuk dan pulang | Berjalan | Ditempatkan di bagian awal tampilan, responsif untuk mobile |
| Presensi GPS | Berjalan | Koordinat direkam; aturan radius/geofence sekolah belum lengkap |
| Stasiun QR dinamis dengan Reverb | Berjalan | Token diperbarui realtime, berlaku 8 detik, dan sekali pakai |
| Prasyarat kehadiran guru untuk QR | Belum | Perlu aturan aktivasi sesi oleh guru |
| Pemindai QR lewat kamera internal | Belum | Saat ini alur menggunakan token/stasiun yang tersedia |
| Google Drive | Fondasi | Belum terhubung kredensial sekolah |
| Tagihan SPP | Fondasi | Data dan tampilan dasar ada; payment gateway belum aktif |
| PWA | Fondasi | Aset dasar tersedia; audit offline/install belum final |
| Notifikasi login, FCM, dan email | Belum | Dropdown antarmuka tersedia; kanal eksternal belum aktif |
| AI pembuat kuis | Belum | Memerlukan penyedia AI, batas penggunaan, dan moderasi |
| Izin, laporan, dan rekap ekspor | Belum | Masuk roadmap akademik/presensi |
| Chat, rollover tahun ajaran, proctoring | Belum | Fitur lanjutan setelah alur inti stabil |

Urutan pengembangan selanjutnya dijelaskan di [DEVELOPMENT.md](DEVELOPMENT.md).
