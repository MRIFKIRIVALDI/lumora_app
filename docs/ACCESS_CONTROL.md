# Matriks Akses Role

Pembatasan data diterapkan di sisi server. Navigasi yang disembunyikan hanya membantu tampilan dan bukan pengganti otorisasi.

| Data atau fitur | Admin | Guru | Murid | Wali |
|---|---|---|---|---|
| Pengguna dan role | Semua pengguna | Profil sendiri | Profil sendiri | Profil sendiri |
| Relasi wali–murid | Mengatur semua relasi | Tidak mengatur | Melihat relasi sendiri | Melihat anak terhubung |
| Kelas/rombel | Semua kelas | Kelas yang diampu atau menjadi wali kelas | Kelas sendiri | Kelas anak |
| Jadwal dan mata pelajaran | Semua data | Jadwal yang diampu | Jadwal kelas sendiri | Jadwal anak |
| Pertemuan pembelajaran | Mode lihat semua data | Membuat dan mengelola hanya sesi yang diampu | Sesi terbit untuk kelas sendiri | Sesi terbit milik anak |
| Materi, tugas, kuis, presensi pertemuan | Mode lihat sesuai kebutuhan audit | Mengelola hanya setiap pertemuan pada sesi yang diampu | Pratinjau materi, unggah tugas, mengerjakan kuis kelas sendiri | Memantau konten milik anak |
| Data murid | Semua murid | Murid dalam kelas yang diampu | Nama teman satu rombel tanpa email/data profil | Anak yang terhubung |
| Presensi | Semua data | Data kelas terkait | Data sendiri | Data anak |
| Pengajuan izin/sakit | Memeriksa semua | Memeriksa murid terkait | Mengajukan dan melihat sendiri | Mengajukan dan melihat anak |
| Membuka QR guru | Ya | Tidak | Tidak | Tidak |
| Membuka QR murid | Ya | Setelah presensi guru | Tidak | Tidak |
| Tagihan SPP | Menerbitkan, melihat, menandai/batal lunas, grafik bulanan | Tidak tersedia | Melihat dan membayar tagihan sendiri | Melihat dan membayar tagihan anak |
| Notifikasi konten | Audit data pembelajaran | Memicu notifikasi saat berbagi | Menerima dari kelas sendiri | Belum menerima notifikasi kelas anak |
| Nilai dan rapor | Memeriksa, menyetujui, atau mengembalikan | Membuat dan mengubah nilai sesi yang diampu | Hanya melihat rapor sendiri yang disetujui | Hanya melihat rapor anak yang disetujui |
| Grafik dashboard | Seluruh data | Data terkait | Tidak ditampilkan | Tidak ditampilkan |
| Kalender akademik | Agenda sesuai target role | Agenda guru/umum | Agenda umum dan pelajaran | Agenda umum dan agenda anak |

## Aturan relasi

- Satu murid hanya dapat memiliki satu wali aktif.
- Satu wali dapat terhubung dengan lebih dari satu murid.
- Wali kelas menaungi satu rombel, termasuk seluruh murid dalam rombel tersebut.
- Guru mata pelajaran dapat mengajar banyak rombel dan banyak tingkat sekaligus.
- Relasi guru–murid terbentuk melalui sesi kelas: guru → sesi/mapel → rombel → murid.
- Admin mengatur struktur kelas, wali kelas, mapel, guru pengampu, dan jadwal; admin tidak mengelola isi pertemuan maupun pengumpulan tugas.
- Admin dapat melihat seluruh data. Guru hanya melihat sesi/mapel dengan `teacher_id` miliknya dan murid kelas yang diampu; sesi guru lain tidak dapat dibuka.
- Murid dapat melihat nama anggota rombel, tetapi tidak menerima email, identitas, kontak, presensi pribadi, tugas, atau profil murid lain.
- QR memiliki sasaran akun. QR guru hanya menerima akun guru, sedangkan QR murid hanya menerima akun murid.
- Pengajuan yang disetujui tidak menimpa presensi QR yang sudah tercatat hadir.
