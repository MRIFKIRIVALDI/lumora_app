# Riwayat Perubahan

## 2026-10-09

- Memperbarui ikon tombol aksi pertemuan (materi, tugas, kuis, dan presensi) agar tidak lagi memakai simbol teks lama; seluruh bentuk kartu tetap menjadi area klik.
- Memastikan seluruh pemakaian logo tetap menggunakan `logo_lumora.png` sesuai arahan; ikon aksi memakai SVG internal.
- Menambahkan `docs/ROADMAP_IMPLEMENTASI.md` sebagai urutan end-to-end dari penyusunan PRD, analisis sekolah, desain, coding, testing, staging, migrasi data, pilot, go-live, hingga operasional dan evaluasi.
- Menambahkan `docs/DATABASE.md` berisi penjelasan SQLite, diagram relasi, fungsi tabel, serta cara melihat struktur melalui terminal atau aplikasi visual.
- Menambahkan `docs/CARA_MENJALANKAN.md` sebagai tutorial lengkap menjalankan Lumora di Windows, penggunaan launcher, akun demo, dan troubleshooting localhost.
- Memperbarui launcher agar database SQLite yang benar-benar baru otomatis diisi akun serta data demo.

## 2026-10-05

- Mengganti simbol Unicode lama pada Dashboard seluruh role, kartu metrik admin/guru, aksi presensi, daftar tugas, kelas, pengumuman, kalender, dan rekap kehadiran dengan ikon SVG modern yang konsisten.
- Menambahkan ikon khusus Murid, Guru, sekolah, waktu, masuk/pulang, pengumuman, kesehatan, tambah, dan status gagal ke pustaka ikon internal Lumora.
- Mengubah grafik SPP admin menjadi grafik bulanan Januari–Desember dengan seri lunas dan menunggu berdasarkan data sebenarnya.
- Menempatkan tagihan tertunda tepat di bawah presensi Dashboard Murid dan sebagai panel pertama Dashboard Wali.
- Menambahkan payment sandbox lokal dengan pilihan QRIS, Virtual Account, serta dompet digital untuk pengujian tanpa akun gateway pihak ketiga.
- Menambahkan tabel riwayat transaksi pembayaran lengkap dengan referensi, pembayar, metode, nominal, status, dan waktu bayar.
- Membatasi halaman serta aksi pembayaran hanya untuk Murid pemilik tagihan atau Wali yang terhubung dengan murid tersebut.
- Menambahkan `START_LUMORA.bat` untuk menyiapkan dan menjalankan aplikasi, queue, scheduler, serta Laravel Reverb melalui satu kali klik.
- Membuka halaman Lumora di browser secara otomatis setelah server mendapat waktu untuk aktif.
- Launcher memeriksa PHP, Composer, npm, dependensi, application key, SQLite, migrasi, cache, dan build aset sebelum server dimulai.
- Memperjelas README bahwa mode demo SQLite tidak membutuhkan XAMPP serta membedakan penggunaan harian dari reset data demo.
- Mengganti seluruh simbol lama pada halaman Akun dengan ikon SVG modern yang sesuai fungsi, berukuran lebih jelas, dan memiliki warna kategori konsisten.
- Memperbarui avatar profil, tombol arah, hover menu, serta tampilan light/dark halaman Akun.
- Menyinkronkan README, daftar fitur, arsitektur, dan panduan pengembangan dengan sistem visual serta ikon terbaru.
- Memperbaiki kontras teks, jam, tanggal, dan status tombol pada kartu presensi bergradasi.
- Menambahkan kalender bulanan interaktif untuk guru dan murid dengan navigasi bulan sebelumnya, hari ini, dan bulan berikutnya.
- Menampilkan jadwal pelajaran serta agenda sekolah langsung pada tanggal kalender terkait.
- Menambahkan modul Nilai & Rapor dengan alur draft guru, pengisian nilai seluruh roster, pengajuan, dan persetujuan/penolakan admin.
- Menambahkan perhitungan nilai akhir berbobot Tugas 25%, Kuis 20%, UTS 25%, dan UAS 30% serta predikat A–E.
- Menambahkan menu Nilai & Rapor untuk admin/guru dan akses Dashboard khusus murid/wali.
- Membatasi murid dan wali agar hanya dapat membaca rapor terkait yang telah disetujui admin.
- Menghapus panel informasi mode aman dari halaman murid agar tampilan kuis lebih ringkas; perlindungan tetap aktif di belakang layar.
- Menambahkan kuis terbit yang belum dikerjakan ke daftar pekerjaan Dashboard murid.
- Menambahkan mode aman kuis: pembatasan seleksi, salin, klik kanan, shortcut, serta penyamaran soal saat tab kehilangan fokus.
- Mendokumentasikan bahwa browser tidak dapat sepenuhnya mencegah screenshot sistem operasi atau foto dari perangkat lain.
- Memperbaiki template kuis yang sebelumnya dapat gagal dikompilasi pada halaman guru.
- Mengembalikan ukuran dan susunan form login ke proporsi awal.
- Mengaktifkan tagihan: admin dapat menerbitkan tagihan individual/seluruh murid dan mengubah status lunas, sedangkan murid/wali melihat tagihan terkait.
- Memperbaiki kontras, susunan teks, dan ikon pada hero halaman pertemuan.
- Membuat alur pembuatan kuis langsung menuju bank soal agar kuis dapat segera digunakan.
- Menambahkan notifikasi khusus kepada murid satu kelas ketika guru membagikan materi, tugas, atau kuis.
- Memperketat data guru: sesi, mata pelajaran, guru, tugas, dan murid dibatasi pada relasi pengajaran guru tersebut.
- Menampilkan nama teman sekelas kepada murid tanpa membocorkan email atau data profil mereka.
- Menambahkan jadwal mengajar seluruh guru pada Dashboard admin.
- Mengganti ikon navigasi utama, Dashboard, header, notifikasi, tema, kembali, dan logout dengan sistem SVG modern yang konsisten.
- Menerapkan claymorphism pada kartu, tombol, input, menu, dan panel dengan kedalaman visual yang responsif.
- Menerapkan glassmorphism secara ringan pada sidebar, topbar, navigasi mobile, notifikasi, dan panel login.
- Menambahkan penyesuaian light mode, dark mode, desktop, dan mobile tanpa mengubah alur fitur.

## 2026-09-26

- Menampilkan pusat seluruh menu sesuai role di Dashboard, sementara bottom navigation tetap lima item.
- Memindahkan aksi materi, tugas, kuis, dan presensi manual ke setiap kartu pertemuan.
- Menambahkan unggah serta pratinjau materi teks, tautan, gambar, dan PDF sebelum diunduh.
- Menambahkan kuis pilihan ganda, bank soal guru, pengerjaan murid, dan penilaian otomatis.
- Menambahkan presensi manual hadir/sakit/izin/alfa untuk seluruh murid dalam kelas per pertemuan.
- Menambahkan pembuat pola pertemuan harian/mingguan dalam satu aksi, termasuk rentang tanggal, pilihan hari, jam, lokasi, dan status publikasi.
- Menambahkan pengajuan izin/sakit oleh murid atau wali dengan bukti foto/PDF.
- Menambahkan persetujuan/penolakan oleh admin atau guru yang memiliki relasi kelas.
- Menghubungkan pengajuan disetujui ke data presensi tanpa menimpa kehadiran QR.
- Menambahkan rekap harian hadir, sakit, izin, dan alfa beserta filter tanggal.
- Memperbarui grafik Dashboard dengan komposisi hadir, sakit, izin, dan alfa hari berjalan.
- Menambahkan finalisasi alfa otomatis pada pukul 00.05 untuk hari sebelumnya.

## 2026-09-25

- Mengubah navigasi portal menjadi Dashboard, Jelajahi, Kelas, Obrolan, dan Akun dengan bottom navigation khusus mobile.
- Menghapus presensi sebagai menu utama murid karena aksi masuk/pulang tersedia paling atas pada dashboard.
- Mempertahankan Kelola Presensi untuk admin dan guru sebagai akses operasional QR.
- Menambahkan halaman berita sekolah, pusat akun bergaya mobile, dan fondasi ruang obrolan.
- Menambahkan QR khusus guru yang hanya dapat dibuka admin.
- Mewajibkan guru presensi terlebih dahulu sebelum dapat membuka QR murid.
- Membatasi token QR berdasarkan sasaran role untuk mencegah QR guru dipakai murid atau sebaliknya.
- Menambahkan pemindai QR berbasis kamera untuk akun guru dan murid.
- Menambahkan tiga admin, enam guru, 21 murid dalam tiga kelas, dan sepuluh wali untuk simulasi.
- Menambahkan NIK, NIS, NISN, dan NIP pada profil sesuai role serta menghapus pekerjaan dari form murid.
- Menambahkan tombol kembali global dan pilihan Bahasa Indonesia/English pada menu Akun.
- Memisahkan tampilan akademik per rombel agar kelas 10, 11, dan 12 tidak tercampur.
- Membatasi admin pada pengaturan struktur kelas/jadwal dan guru pengampu pada pengelolaan konten pembelajaran.
- Menghapus akses menu tugas dari admin dan memperbaiki halaman tugas bagi guru, murid, serta wali.
- Menambahkan pembacaan materi lengkap dan unggah/ganti file tugas bagi murid.
- Menambahkan akses operasional admin pada bagian bawah Dashboard mobile.
- Memperluas sidebar khusus admin menjadi kelompok Akademik, Pengguna, Presensi, Keuangan & Informasi, serta Sistem.
- Menambahkan pusat kendali Dashboard admin dengan metrik nyata, kapasitas rombel, aktivitas pembelajaran, status layanan, dan pengguna terbaru.
- Menambahkan filter direktori akun Admin, Guru, Murid, dan Wali.

## 2026-09-24

- Menambahkan pembatasan data berdasarkan role: admin global, guru berdasarkan relasi kelas, murid untuk diri sendiri, serta wali untuk diri sendiri dan anak.
- Menambahkan manajemen role, relasi wali–murid, dan profil profesional.
- Menambahkan rombel, wali kelas, mapel, guru pengampu, roster, dan sesi pembelajaran.
- Menambahkan pertemuan yang dapat memuat materi, tugas, dan kuis.
- Mengubah beranda murid agar menampilkan kalender mingguan, pelajaran, agenda, dan tugas.
- Membatasi grafik dashboard untuk admin dan guru.
- Memindahkan presensi masuk/pulang ke bagian utama dan menyesuaikan tata letak mobile.
- Menambahkan grafik kolom, garis, batang, dan pai pada dashboard analitik.
- Mengaktifkan QR dinamis menggunakan Laravel Reverb dengan token berumur pendek dan sekali pakai.
- Menambahkan light mode/dark mode dan menyelaraskan warna seluruh role dengan logo Lumora.
- Memperjelas fungsi kontrol header dan notifikasi.
- Menambahkan rangkaian dokumentasi arsitektur, akses role, status fitur, operasional, dan pengembangan.

Verifikasi terakhir: 23 pengujian backend dengan 127 assertion berhasil, termasuk pola pertemuan, persetujuan izin/sakit, finalisasi alfa, Dashboard admin, unggah tugas, dan alur simulasi akademik; build frontend juga berhasil.
