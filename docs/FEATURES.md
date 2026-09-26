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
| Bahasa Indonesia/English | Berjalan | Pilihan bahasa tersedia di menu Akun; navigasi inti telah dilokalkan |
| Navigasi portal lima menu | Berjalan | Bottom navigation tetap lima menu; seluruh fitur tambahan tersedia sebagai pusat menu pada Dashboard sesuai role |
| Jelajahi/berita sekolah | Berjalan | Menampilkan pengumuman dan berita sesuai target role |
| Obrolan | Fondasi | Menu dan ruang komunikasi tersedia; pengiriman pesan realtime belum aktif |
| Rombel, wali kelas, mapel, dan guru | Berjalan | Kelas ditampilkan terpisah; struktur hanya diatur admin, penyuntingan massal belum lengkap |
| Relasi guru–murid lintas kelas | Berjalan | Dibentuk melalui sesi kelas dan roster |
| Pertemuan, materi, tugas, dan kuis | Berjalan | Setiap pertemuan memiliki aksi materi, tugas, kuis PG, dan presensi manual; kuis dinilai otomatis |
| Pratinjau materi | Berjalan | Teks, tautan, gambar, dan PDF dipratinjau sebelum unduh; dokumen Office menampilkan metadata dan unduhan |
| Presensi manual per pertemuan | Berjalan | Guru pengampu memilih hadir/sakit/izin/alfa untuk seluruh roster kelas |
| Pola pertemuan berulang | Berjalan | Guru membuat pertemuan harian/mingguan lengkap dengan rentang tanggal, hari, jam, lokasi, dan status |
| Pengajuan izin/sakit | Berjalan | Murid/wali mengunggah bukti; guru terkait atau admin menyetujui/menolak |
| Rekap hadir/sakit/izin/alfa | Berjalan | QR menghasilkan hadir, persetujuan menghasilkan sakit/izin, sisanya difinalisasi sebagai alfa |
| Dashboard admin | Berjalan | Grafik kolom, garis, batang, dan pai dalam cakupan MVP |
| Pusat kendali admin | Berjalan | Sidebar bertingkat, metrik pengguna/akademik/presensi, kapasitas kelas, layanan, dan pengguna terbaru |
| Dashboard guru | Berjalan | Analitik dibatasi pada kelas yang terhubung |
| Beranda murid | Berjalan | Kalender mingguan, agenda, tugas, pelajaran, dan kartu kelas |
| Beranda wali | Berjalan | Ringkasan anak terhubung; pemantauan terperinci masih dikembangkan |
| Kalender akademik berbasis role | Berjalan | Agenda umum dan agenda pelajaran murid ditampilkan |
| Presensi masuk dan pulang | Berjalan | Ditempatkan di bagian awal tampilan, responsif untuk mobile |
| Presensi GPS | Berjalan | Koordinat direkam; aturan radius/geofence sekolah belum lengkap |
| Stasiun QR dinamis dengan Reverb | Berjalan | Token diperbarui realtime, berlaku 8 detik, dan sekali pakai |
| Presensi guru dan prasyarat QR murid | Berjalan | Admin membuka QR guru; guru yang telah hadir dapat membuka QR murid |
| Pemindai QR lewat kamera internal | Berjalan | Guru dan murid dapat membuka kamera untuk membaca QR Lumora |
| Google Drive | Fondasi | Belum terhubung kredensial sekolah |
| Tagihan SPP | Fondasi | Data dan tampilan dasar ada; payment gateway belum aktif |
| PWA | Fondasi | Aset dasar tersedia; audit offline/install belum final |
| Notifikasi login, FCM, dan email | Belum | Dropdown antarmuka tersedia; kanal eksternal belum aktif |
| AI pembuat kuis | Belum | Memerlukan penyedia AI, batas penggunaan, dan moderasi |
| Izin, laporan, dan rekap ekspor | Belum | Masuk roadmap akademik/presensi |
| Chat, rollover tahun ajaran, proctoring | Belum | Fitur lanjutan setelah alur inti stabil |

Urutan pengembangan selanjutnya dijelaskan di [DEVELOPMENT.md](DEVELOPMENT.md).
