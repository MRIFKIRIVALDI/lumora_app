# Matriks Akses Role

Pembatasan data diterapkan di sisi server. Navigasi yang disembunyikan hanya membantu tampilan dan bukan pengganti otorisasi.

| Data atau fitur | Admin | Guru | Murid | Wali |
|---|---|---|---|---|
| Pengguna dan role | Semua pengguna | Profil sendiri | Profil sendiri | Profil sendiri |
| Relasi wali–murid | Mengatur semua relasi | Tidak mengatur | Melihat relasi sendiri | Melihat anak terhubung |
| Kelas/rombel | Semua kelas | Kelas yang diampu atau menjadi wali kelas | Kelas sendiri | Kelas anak |
| Jadwal dan mata pelajaran | Semua data | Jadwal yang diampu | Jadwal kelas sendiri | Jadwal anak |
| Pertemuan pembelajaran | Semua data | Membuat dan mengelola sesi yang diampu | Sesi terbit untuk kelas sendiri | Sesi terbit milik anak |
| Materi, tugas, dan kuis | Semua data | Mengelola pada sesi terkait | Mengakses milik kelas sendiri | Memantau milik anak |
| Data murid | Semua murid | Murid dalam kelas terkait | Diri sendiri | Anak yang terhubung |
| Presensi | Semua data | Data kelas terkait | Data sendiri | Data anak |
| Tagihan SPP | Semua data | Tidak tersedia | Tagihan sendiri | Tagihan anak |
| Grafik dashboard | Seluruh data | Data terkait | Tidak ditampilkan | Tidak ditampilkan |
| Kalender akademik | Agenda sesuai target role | Agenda guru/umum | Agenda umum dan pelajaran | Agenda umum dan agenda anak |

## Aturan relasi

- Satu murid hanya dapat memiliki satu wali aktif.
- Satu wali dapat terhubung dengan lebih dari satu murid.
- Wali kelas menaungi satu rombel, termasuk seluruh murid dalam rombel tersebut.
- Guru mata pelajaran dapat mengajar banyak rombel dan banyak tingkat sekaligus.
- Relasi guru–murid terbentuk melalui sesi kelas: guru → sesi/mapel → rombel → murid.
- Admin dapat melihat seluruh data. Guru hanya melihat data yang memiliki relasi pengajaran atau wali kelas. Murid hanya melihat data sendiri. Wali melihat data sendiri dan anak yang terhubung.
