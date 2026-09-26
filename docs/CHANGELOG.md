# Riwayat Perubahan

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
