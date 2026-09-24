# Product Requirement Document (PRD): Lumora

**Nama Produk:** Lumora (Learning & Understanding Management Online for Real Achievement)
**Tipe Platform:** Web Responsif (Desktop & Mobile PWA)
**Tech Stack:** Laravel 11, MySQL (Migration), Laravel Reverb (WebSocket), Tailwind CSS
**Skema Warna:** Deep Blue `#1E3A8A` & Electric Blue `#2563EB`
**Versi Dokumen:** 1.2 (Penambahan v1.1: Rombel & Kurikulum, AI Quiz Generator, Tugas via Google Drive, Strategi Email/SSO. Penambahan v1.2: Closed-Registration & 2FA, Multi-Titik QR + Presensi Guru, Mode Kuis Aman, Keamanan & Skalabilitas Aplikasi)
**Tanggal:** 17 September 2026
**Status:** Draft untuk Review

---

## Daftar Isi

1. [Ringkasan Eksekutif](#1-ringkasan-eksekutif)
2. [Latar Belakang & Problem Statement](#2-latar-belakang--problem-statement)
3. [Ruang Lingkup Produk](#3-ruang-lingkup-produk)
4. [Peran Pengguna & Hak Akses](#4-peran-pengguna--hak-akses-role--permission-matrix)
5. [Spesifikasi Fitur Fungsional](#5-spesifikasi-fitur-fungsional)
6. [Alur Pengguna Utama](#6-alur-pengguna-utama-key-user-flows)
7. [Kebutuhan Non-Fungsional](#7-kebutuhan-non-fungsional)
8. [Tech Stack & Arsitektur Sistem](#8-tech-stack--arsitektur-sistem)
9. [Arsitektur Basis Data](#9-arsitektur-basis-data-database-schema)
10. [Integrasi Pihak Ketiga](#10-integrasi-pihak-ketiga)
11. [Pertimbangan Keamanan & Skalabilitas](#11-pertimbangan-keamanan--skalabilitas)
12. [Prinsip Desain UI/UX](#12-prinsip-desain-uiux)
13. [Metrik Keberhasilan (KPI)](#13-metrik-keberhasilan-success-metrics--kpi)
14. [Roadmap & Milestone](#14-roadmap--milestone-pengembangan)
15. [Risiko & Mitigasi](#15-risiko--mitigasi)
16. [Asumsi & Dependensi](#16-asumsi--dependensi)
17. [Lampiran: Glosarium](#17-lampiran-glosarium)

---

## 1. Ringkasan Eksekutif

Lumora adalah sistem *Learning Management System* (LMS) berbasis web responsif yang dirancang khusus untuk kebutuhan operasional sekolah secara menyeluruh — mencakup manajemen akademik, presensi digital anti-kecurangan, keuangan SPP, penilaian, hingga komunikasi antar pengguna. Lumora dibangun untuk menghubungkan empat peran utama dalam ekosistem sekolah: **Admin, Guru, Murid, dan Wali**, dalam satu platform terintegrasi yang real-time.

Nilai jual utama (*unique selling point*) Lumora terletak pada sistem presensi QR Code dinamis yang berubah setiap beberapa detik dan divalidasi dengan GPS geofencing, sehingga secara signifikan menutup celah kecurangan presensi (titip absen via foto/screenshot). Setiap aktivitas presensi murid langsung memicu notifikasi real-time ke akun Wali, memberikan transparansi penuh kepada orang tua.

Selain presensi, Lumora mengintegrasikan pembayaran SPP digital dengan payment gateway, sinkronisasi status tagihan dua arah antara akun Wali dan Murid, modul akademik (jadwal, materi, tugas, kuis), raport digital otomatis, kalender akademik, serta fitur komunikasi (chat) yang dibatasi ruang lingkupnya sesuai relasi kelas untuk menjaga privasi dan mencegah penyalahgunaan.

Pembaruan versi 1.1 menambahkan empat kapabilitas penting: **(1) struktur Rombongan Belajar (Rombel) & Kurikulum** yang menyesuaikan otomatis dengan tingkat kelas/jurusan tiap tahun ajaran; **(2) AI Quiz Generator** yang memungkinkan guru membuat kuis otomatis dari materi yang sudah dibagikan menggunakan LLM API (Google Gemini), dengan alur review manual sebelum dipublikasikan; **(3) pengumpulan tugas berbasis tautan Google Drive murid** dengan fitur preview langsung oleh guru (bukan unduh file), sehingga beban penyimpanan tidak membebani server Lumora maupun perangkat guru; serta **(4) strategi identitas & email berbasis Google SSO**, memanfaatkan Google Workspace for Education milik sekolah (bila tersedia) alih-alih Lumora membangun layanan email sendiri.

### Tujuan Produk

- Mendigitalisasi proses presensi sekolah dengan tingkat keamanan tinggi guna menghilangkan praktik titip absen.
- Memberikan transparansi real-time kepada orang tua/wali atas kehadiran, nilai, dan status keuangan anak.
- Menyederhanakan proses pembayaran SPP dan administrasi keuangan sekolah melalui otomasi payment gateway.
- Menyatukan seluruh proses akademik (jadwal, materi, tugas, kuis, raport) dalam satu platform terpadu.
- Menyediakan kanal komunikasi resmi yang aman dan terbatas ruang lingkupnya antar pengguna sekolah.

### Target Pengguna

Sekolah tingkat SD/SMP/SMA/sederajat yang membutuhkan sistem manajemen pembelajaran terpadu dengan empat kategori pengguna: Admin sekolah, Guru/Tenaga pendidik, Murid, dan Wali/Orang tua murid.

---

## 2. Latar Belakang & Problem Statement

### Latar Belakang

Banyak sekolah masih menjalankan proses administrasi (presensi, pembayaran SPP, distribusi nilai) secara manual atau melalui sistem yang terfragmentasi antar aplikasi, sehingga menyulitkan pemantauan oleh orang tua dan rawan terhadap manipulasi data, khususnya pada proses presensi manual atau QR statis yang mudah difoto dan disebarluaskan.

### Permasalahan yang Ingin Diselesaikan

| Masalah | Deskripsi |
|---|---|
| Presensi rawan manipulasi | QR/kartu presensi statis mudah difoto dan dibagikan ke grup untuk "titip absen". |
| Minim transparansi ke orang tua | Wali tidak mendapat informasi real-time terkait kehadiran, nilai, dan tagihan anak. |
| Pembayaran SPP manual | Proses pembayaran & rekonsiliasi SPP dilakukan manual, rawan selisih dan lambat. |
| Data akademik tersebar | Jadwal, materi, tugas, nilai, dan kalender akademik tidak berada dalam satu sistem. |
| Komunikasi tidak terstruktur | Komunikasi sekolah-wali-murid sering melalui grup chat pribadi yang sulit diaudit dan rawan spam. |

---

## 3. Ruang Lingkup Produk

### Dalam Lingkup (In-Scope)

- Manajemen 4 role pengguna: Admin, Guru, Murid, Wali beserta relasi Parent-Child.
- Sistem presensi QR dinamis (refresh per detik) dengan validasi GPS geofencing untuk presensi harian.
- Presensi sesi per mata pelajaran (manual checklist / QR sesi).
- Notifikasi real-time (push notification) ke akun Wali saat murid presensi.
- Pengajuan izin/sakit digital dengan unggah bukti dan approval oleh Guru/Admin.
- Modul akademik: manajemen kelas, jadwal, materi, tugas, kuis.
- Modul penilaian & raport digital dengan kalkulasi otomatis + input manual guru, serta export PDF.
- Modul keuangan: tagihan SPP digital, sinkronisasi status dua akun, integrasi payment gateway, cetak resi PDF.
- Kalender akademik & pengumuman sekolah.
- Fitur chat internal dengan pembatasan ruang lingkup (scope) sesuai relasi kelas.
- Modul kenaikan kelas / rollover tahun ajaran & pengarsipan data.
- Tampilan responsif untuk Desktop (Admin/Guru) dan Mobile PWA (Murid/Wali/Guru).
- Struktur Rombongan Belajar (Rombel) dan kurikulum mata pelajaran per tingkat kelas/jurusan, yang dapat berbeda konfigurasinya di tiap tahun ajaran.
- Pembuatan kuis otomatis berbasis AI (generate dari materi yang dibagikan guru), sebagai pelengkap kuis manual, dengan alur review guru sebelum dipublikasikan ke murid.
- Pengumpulan tugas murid melalui tautan Google Drive pribadi (tanpa upload file ke server Lumora) beserta fitur preview tugas langsung oleh guru tanpa perlu mengunduh.
- Login SSO menggunakan akun Google (baik akun institusional sekolah dari Google Workspace for Education, maupun akun Gmail pribadi).

### Luar Lingkup (Out-of-Scope) — Fase 1

- Aplikasi native iOS/Android terpisah (fase awal menggunakan PWA, bukan aplikasi native App Store/Play Store).
- Modul kepegawaian/payroll guru & staf.
- Modul perpustakaan digital dan sarana-prasarana (inventaris aset sekolah).
- Ujian daring dengan proctoring/anti-cheating kamera (di luar kuis dasar).
- Integrasi dengan Dapodik/sistem pemerintah (dapat menjadi roadmap fase berikutnya).
- Penyediaan layanan email institusional oleh Lumora sendiri (mis. akun `@namasekolah.sch.id`). Lumora bukan penyedia email — sekolah disarankan memanfaatkan **Google Workspace for Education** (gratis untuk institusi pendidikan yang memenuhi syarat) untuk kebutuhan ini; lihat Bagian 5.1 dan Bagian 16.
- Training model AI/LLM secara mandiri (self-trained) untuk fitur AI Quiz Generator. Lumora menggunakan LLM API pihak ketiga yang sudah tersedia (lihat Bagian 5.8 dan Bagian 10) karena jauh lebih efisien dari segi biaya, waktu, dan kualitas dibanding melatih model sendiri.

---

## 4. Peran Pengguna & Hak Akses (Role & Permission Matrix)

Lumora menerapkan *Role-Based Access Control* (RBAC) dengan 4 peran utama.

### 4.1 Deskripsi Peran

| Peran | Ringkasan Tanggung Jawab & Akses |
|---|---|
| **Admin** | Mengelola master data pengguna, kelas/rombel, kurikulum per tingkat-jurusan, dan tahun ajaran; memvalidasi pengajuan izin; mengelola laporan keuangan SPP; mengonfigurasi radius lokasi sekolah (GPS); mengelola kalender akademik & pengumuman. |
| **Guru** | Mengelola sesi mata pelajaran; menerbitkan QR presensi sesi; membagikan materi, tugas, dan kuis (manual maupun AI-generate dari materi); mem-preview tugas murid langsung tanpa unduh; menginput nilai raport; memvalidasi pengajuan izin murid di kelasnya. |
| **Murid** | Mengakses jadwal pelajaran sesuai kurikulum rombelnya; memindai QR presensi; mengumpulkan tugas via tautan Google Drive pribadi & mengerjakan kuis; melihat raport; melihat tagihan SPP; chat dengan guru/teman sekelas. |
| **Wali** | Memantau notifikasi presensi real-time anak; melakukan pembayaran SPP digital; mengajukan izin/sakit anak; melihat raport digital anak; chat dengan guru. Satu akun Wali dapat terhubung ke lebih dari satu akun Murid. |

### 4.2 Matriks Hak Akses per Modul

| Modul / Fitur | Admin | Guru | Murid | Wali |
|---|---|---|---|---|
| Manajemen Master Data (User, Kelas, Tahun Ajaran) | Full | - | - | - |
| Konfigurasi Radius GPS Sekolah | Full | - | - | - |
| Terbitkan QR Presensi Harian (Titik Presensi) | Full (buka/tutup titik) | Full, hanya setelah presensi diri sendiri | - | - |
| Presensi Guru (Presensi Diri Sendiri) | View | Full | - | - |
| Terbitkan QR / Absen Sesi Kelas | - | Full | - | - |
| Scan QR Presensi (sebagai murid) | - | - | Full | - |
| Approval Izin/Sakit | Full | Terbatas (kelas ampu) | Ajukan | Ajukan |
| Kelola Materi / Tugas / Kuis | View | Full (kelas ampu) | Kerjakan | View |
| Kelola Kurikulum & Rombel per Tahun Ajaran | Full | View | - | - |
| Generate Kuis via AI (dari materi) | View | Full (kelas ampu) | - | - |
| Submit Tugas via Tautan Google Drive | - | - | Full | - |
| Preview Tugas Murid (tanpa unduh) | View | Full (kelas ampu) | - | - |
| Input Nilai Raport | View | Full (kelas ampu) | View | View |
| Export Raport PDF | Full | Full (kelas ampu) | View/Unduh | View/Unduh |
| Kelola Tagihan SPP | Full | - | View | Bayar |
| Laporan Keuangan Sekolah | Full | - | - | - |
| Kalender Akademik & Pengumuman | Full (kelola) | View | View | View |
| Chat | Broadcast/Admin | Kelas ampu | Kelas & guru ampu | Guru anak |
| Modul Kenaikan Kelas / Rollover | Full | - | - | - |

---

## 5. Spesifikasi Fitur Fungsional

### 5.1 Manajemen Multi-Role & Akun

- **Sistem bersifat closed-registration (tidak ada halaman "Daftar/Sign Up" mandiri untuk publik).** Satu-satunya cara akun tercipta adalah didaftarkan oleh Admin (Admin meng-input data pengguna, role, dan email/username) — pengguna baru **hanya bisa Login**, tidak bisa mendaftar sendiri. Pendekatan ini sengaja dipilih untuk mencegah penyusup/akun tidak sah masuk ke sistem sekolah.
- Relasi Parent-Child: satu akun Wali dapat ditautkan ke lebih dari satu akun Murid (mendukung keluarga dengan beberapa anak di sekolah yang sama), melalui tabel relasi many-to-many.
- Profil pengguna dengan foto, nomor telepon (untuk notifikasi), dan status akun aktif/nonaktif — status nonaktif dapat dipakai Admin untuk langsung mencabut akses murid/guru yang keluar/pindah tanpa menghapus data historis.
- Reset password & manajemen sesi login (multi-device untuk PWA).
- **Login dengan Google (opsional, bukan Sign Up):** bila diaktifkan, tombol "Login dengan Google" hanya berfungsi sebagai *metode autentikasi* untuk akun yang emailnya **sudah didaftarkan sebelumnya oleh Admin** — sistem mencocokkan email Google yang login dengan data akun yang sudah ada. Jika email tidak ditemukan di database, akses ditolak (tidak otomatis membuat akun baru). Dua skenario email:
  1. *Sekolah memiliki Google Workspace for Education* (domain institusional, mis. `@namasekolah.sch.id`): Admin mendaftarkan akun memakai email institusional tersebut, sekaligus menjadi basis akses Google Drive institusional untuk fitur pengumpulan tugas (lihat 5.3).
  2. *Sekolah belum memiliki Workspace*: Admin tetap dapat mendaftarkan akun memakai email pribadi (termasuk Gmail) milik pengguna — Lumora tidak menyediakan/mengelola layanan email sendiri (lihat catatan Bagian 16).
- Lumora secara sengaja **tidak membangun mail server sendiri** karena menjaga deliverabilitas email (SPF/DKIM/DMARC, reputasi IP) memerlukan effort operasional besar yang sudah tersedia gratis lewat Google Workspace for Education untuk institusi pendidikan yang memenuhi syarat.
- **Keamanan akun tambahan:** Two-Factor Authentication (2FA) via OTP email/aplikasi authenticator **wajib** untuk role Admin (akses paling sensitif) dan **opsional direkomendasikan** untuk Guru; penguncian akun sementara (account lockout) otomatis setelah percobaan login gagal berulang kali (mis. 5x) untuk mencegah *brute-force*; notifikasi login dari perangkat/lokasi baru dikirim ke pemilik akun sebagai lapisan deteksi dini.

### 5.2 Presensi QR Dinamis & Geofencing

**Presensi Harian (Datang/Pulang) — Multi-Titik QR**

- QR Code ditampilkan pada layar (mis. TV/monitor di pos absensi) dan otomatis *regenerate* token keamanan setiap 1 detik menggunakan Laravel Reverb (WebSocket) agar tidak bisa difoto lalu digunakan berulang.
- **Admin dapat mengaktifkan lebih dari satu Titik/Stasiun QR Presensi secara bersamaan** (mis. layar di gerbang A, gerbang B, lobi, titik kumpul lain) — masing-masing menampilkan QR dinamis yang berbeda namun **tetap tervalidasi ke satu sumber data presensi pusat yang sama**, sehingga murid dapat memindai di titik manapun yang tersedia, mengurangi penumpukan antrian dibanding hanya mengandalkan 1 titik.
- **Guru wajib presensi kehadirannya sendiri lebih dulu** ("Presensi Guru") di awal hari, tercatat terpisah dari presensi murid namun melalui mekanisme QR/metode presensi yang sama. Setelah presensi guru tercatat berstatus Hadir, guru tersebut mendapat **wewenang tambahan untuk turut membuka Titik QR Presensi Murid** (mis. di depan kelasnya) — membantu Admin/guru piket menangani lebih banyak titik presensi secara paralel tanpa perlu login sebagai Admin.
- Seluruh Titik QR Presensi (baik dibuka Admin maupun Guru) **terhubung real-time ke satu sistem presensi pusat** via Laravel Reverb — data check-in murid dari titik manapun langsung tersinkron dan tampil di dashboard Admin tanpa risiko duplikasi atau konflik data antar titik.
- Admin memantau seluruh titik aktif dari satu dashboard terpusat: siapa yang membuka titik tersebut, lokasi/label titik, dan jumlah presensi masuk per titik secara real-time.
- Validasi GPS Geofencing: saat murid memindai QR menggunakan HP, sistem memvalidasi koordinat lokasi murid terhadap radius area sekolah yang telah dikonfigurasi Admin. Presensi ditolak jika berada di luar radius — berlaku di titik manapun QR dipindai.
- Setiap presensi berhasil (check-in/check-out) tercatat dengan timestamp, koordinat, titik/stasiun asal, dan status (Hadir/Terlambat).
- Push notification otomatis dikirim ke akun Wali via Firebase Cloud Messaging (FCM) setiap kali murid berhasil check-in maupun check-out, berisi jam presensi.

**Presensi Sesi per Mata Pelajaran**

- Guru dapat mengabsen murid per sesi kelas melalui dua metode: checklist manual oleh guru, atau menerbitkan QR unik khusus sesi tersebut.
- Data presensi sesi terpisah dari presensi harian, namun dapat direkap menjadi laporan kehadiran per mata pelajaran.

**Pengajuan Izin & Sakit Digital**

- Wali dapat mengajukan izin/sakit untuk anak melalui aplikasi dengan mengunggah bukti (surat dokter/nota izin).
- Pengajuan masuk ke antrean approval Guru (kelas terkait) atau Admin.
- Setelah disetujui, status presensi murid pada tanggal terkait otomatis berubah menjadi "Izin"/"Sakit" sehingga tidak tercatat sebagai "Alpa".

### 5.3 Modul Akademik & Ruang Kelas

**Rombongan Belajar (Rombel) & Kurikulum per Tingkat/Jurusan**

- Admin mendefinisikan struktur kurikulum di awal tiap tahun ajaran: daftar mata pelajaran wajib/pilihan untuk tiap kombinasi tingkat kelas dan jurusan (mis. Kelas XI IPA berbeda daftar mapelnya dengan XI IPS atau XI Bahasa; untuk SMK, tiap kompetensi keahlian seperti RPL/TKJ juga punya daftar mapel produktif masing-masing).
- Rombel dibentuk berdasarkan kombinasi tingkat + jurusan (mis. "XI IPA 1", "XII RPL 2"). Untuk jenjang tanpa penjurusan (SD/SMP), field jurusan dikosongkan dan seluruh rombel di tingkat tersebut memakai satu struktur kurikulum yang sama.
- Saat Guru/Admin membuat jadwal (sesi kelas), sistem **hanya menampilkan pilihan mata pelajaran yang sesuai kurikulum rombel tersebut** — mencegah kesalahan penjadwalan mapel yang tidak relevan dengan jurusan murid.
- Struktur kurikulum bersifat spesifik per tahun ajaran, sehingga perubahan kurikulum atau penambahan jurusan baru di tahun ajaran berikutnya tidak memengaruhi data historis rombel tahun-tahun sebelumnya.

**Jadwal, Materi, dan Tugas**

- Jadwal pelajaran per rombel, ditampilkan dalam tampilan kalender/harian bagi Guru, Murid, dan Wali, mengikuti mata pelajaran yang berlaku di kurikulum rombel tersebut.
- Distribusi materi pembelajaran (dokumen, tautan, video) per sesi/mata pelajaran.
- Pemberian tugas dengan tenggat waktu dan status pengumpulan (belum/sudah/terlambat).
- **Pengumpulan tugas via Google Drive (bukan upload ke server Lumora):** murid mengumpulkan tugas dengan menempelkan tautan *share* file dari Google Drive pribadinya (bukan unggah file langsung), sehingga beban penyimpanan file tugas sepenuhnya berada di akun Google Drive murid, bukan di server Lumora.
  - Sistem memvalidasi format tautan Google Drive yang dikirim, dan memverifikasi bahwa file dapat diakses (izin share minimal "Anyone with link – Viewer", atau dibagikan langsung ke akun institusional guru/sekolah bila memakai Google Workspace for Education).
  - Guru **mem-preview isi tugas langsung di dalam aplikasi** melalui Google Drive Embed Viewer (iframe preview), tanpa perlu mengunduh file ke perangkat guru — sehingga tidak membebani storage/perangkat guru, sekaligus mempercepat proses koreksi karena tidak perlu bolak-balik unduh-buka-tutup file.
  - Guru tetap bisa membuka file di tab baru/Google Drive langsung bila ingin memberi komentar/anotasi (mis. pada Google Docs/Slides).
  - Sistem **tidak menyimpan salinan file tugas murid** — yang disimpan hanya metadata (tautan, nama file, waktu submit, status verifikasi akses), sehingga modul tugas praktis tidak membebani kapasitas storage Lumora.
  - *Catatan risiko:* bila murid mengubah izin akses atau menghapus file setelah submit, guru berisiko kehilangan akses saat pemeriksaan. Sistem mencatat status "terakhir diverifikasi dapat diakses pada [timestamp]" sebagai jejak audit, dan menampilkan peringatan ke murid untuk tidak mengubah permission sampai tugas dinilai (lihat juga Bagian 11 & 15).
- Kalender Akademik: penanda hari libur, ujian, dan agenda sekolah, terlihat oleh semua role.
- Pengumuman sekolah/kelas yang dapat ditargetkan ke seluruh sekolah, kelas tertentu, atau role tertentu.

### 5.4 Penilaian & Raport Digital

- Kalkulasi nilai otomatis dari akumulasi skor kuis dan tugas per sesi/mata pelajaran.
- Input nilai manual oleh Guru untuk komponen nilai akhir (UTS/UAS/sikap) yang tidak berasal dari sistem.
- Raport digital dapat dilihat oleh Murid dan Wali secara real-time setelah dipublikasikan Guru/Admin.
- Export raport ke format PDF sesuai template standar sekolah, dapat diunduh kapan saja.

### 5.5 Kenaikan Kelas & Rollover Tahun Ajaran

- Modul rollover akhir tahun ajaran untuk memindahkan murid ke tingkat/kelas berikutnya secara massal.
- Penentuan status kelulusan untuk tingkat akhir (mis. kelas 12) dengan perubahan status akun murid menjadi alumni.
- Pengarsipan data akademik, presensi, dan keuangan dari tahun ajaran sebelumnya agar tetap dapat diakses (read-only) tanpa mengganggu data tahun berjalan.

### 5.6 Keuangan & SPP Digital

- Admin membuat tagihan SPP (bulanan/berkala) yang otomatis tampil pada portal akun Wali dan Murid terkait (dual-account sync).
- Integrasi Payment Gateway (Midtrans/Xendit) mendukung Virtual Account, QRIS, dan E-Wallet.
- Saat pembayaran berhasil dikonfirmasi oleh payment gateway (via webhook), status tagihan otomatis berubah "Lunas" dan notifikasi tagihan pada kedua akun (Wali & Murid) otomatis hilang/hilang tandanya secara bersamaan.
- Sistem mencatat siapa yang melakukan pembayaran (Wali atau Murid) pada setiap transaksi.
- Resi/bukti pembayaran PDF ter-generate otomatis dan dapat diunduh kapan saja oleh Wali.
- Laporan rekapitulasi keuangan SPP untuk Admin (per kelas, per bulan, per status).

### 5.7 Komunikasi & Chat

- Fitur chat internal antar pengguna dengan *Privacy-Scoped Chat*: Murid hanya dapat menghubungi teman satu kelas dan Guru pengajarnya, mencegah spam antar-murid beda kelas/angkatan.
- Wali dapat chat langsung dengan Guru pengajar anaknya.
- Grup chat kelas (Wali-Wali / pengumuman kelas) yang dimoderasi oleh wali kelas.
- Riwayat chat tersimpan dan dapat diaudit oleh Admin bila diperlukan (moderasi).

### 5.8 Kuis: Manual & AI Quiz Generator

Lumora mendukung dua metode pembuatan kuis yang disimpan dalam struktur data yang sama, dibedakan lewat penanda sumber (manual/AI):

**Kuis Manual**

- Guru membuat soal sendiri (pilihan ganda/esai sederhana) lengkap dengan kunci jawaban, langsung dari panel Guru.
- Penilaian otomatis untuk soal pilihan ganda; soal esai dinilai manual oleh guru.

**AI Quiz Generator (Generate dari Materi)**

- Guru memilih materi yang sudah pernah dibagikan (dokumen/PDF/teks) pada sesi terkait, lalu meminta sistem men-generate soal kuis secara otomatis dari isi materi tersebut menggunakan **LLM API pihak ketiga (Google Gemini API)** — bukan model AI yang dilatih (*trained*) sendiri oleh Lumora.
  - **Alasan memakai API pihak ketiga, bukan training model sendiri:** melatih model AI dari nol membutuhkan dataset masif dan biaya komputasi (GPU) yang sangat besar, jauh melampaui skala kebutuhan proyek sekolah; sementara tugas "menghasilkan soal dari teks materi" sudah dapat ditangani dengan sangat baik oleh LLM komersial lewat *prompting*, tanpa perlu melatih ulang model. Gemini API dipilih sebagai rekomendasi awal karena memiliki *free tier* yang cukup untuk skala satu sekolah, kemampuan Bahasa Indonesia yang baik, dan *context window* besar untuk materi panjang.
  - Provider LLM diabstraksi lewat *service layer* di backend (`QuizGenerationService`), sehingga dapat diganti ke provider lain (mis. OpenAI, atau model open-source self-hosted di masa depan bila kebutuhan privasi data meningkat) tanpa mengubah arsitektur inti.
- Guru dapat menentukan parameter generate: jumlah soal, tingkat kesulitan, tipe soal (pilihan ganda/esai singkat), dan bagian/topik tertentu dari materi.
- **Wajib melalui mode Draft/Review sebelum dipublikasikan:** hasil generate AI tidak langsung tampil ke murid. Guru meninjau, mengedit, menghapus, atau menambah soal terlebih dahulu — pendekatan *human-in-the-loop* ini penting karena LLM berpotensi menghasilkan soal yang kurang akurat ("halusinasi"), dan guru sebagai pemegang keputusan akhir sebelum konten sampai ke murid.
- Sistem mencatat log setiap permintaan generate AI (guru pemohon, materi sumber, parameter, jumlah token terpakai, status) untuk keperluan audit kualitas dan pemantauan biaya penggunaan API.
- Sistem membatasi jumlah permintaan generate AI per guru per hari (rate limiting) untuk mengendalikan biaya API, dan menampilkan pesan yang jelas jika API sedang tidak tersedia — dalam kondisi ini guru tetap dapat membuat kuis secara manual tanpa terganggu.

**Mode Kuis Aman (Anti-Kecurangan)**

Guru dapat mengaktifkan Mode Kuis Aman per kuis (baik manual maupun hasil AI Quiz Generator) dengan lapisan-lapisan berikut:

- **Kamera wajib aktif:** murid harus mengizinkan akses kamera perangkat sebelum kuis dapat dimulai. Sistem mengambil *snapshot* (foto diam) secara berkala selama pengerjaan (mis. tiap 30–60 detik) — bukan rekaman video penuh, demi efisiensi storage dan privasi — sebagai bukti kehadiran yang dapat ditinjau Guru bila ada kecurigaan kecurangan.
- **Mode layar penuh (fullscreen) dipaksa aktif** selama pengerjaan; sistem mendeteksi bila murid keluar dari fullscreen atau berpindah tab/aplikasi lain (via Page Visibility API) dan mencatatnya sebagai "pelanggaran". Setelah jumlah pelanggaran melewati ambang batas yang ditentukan Guru (mis. 3 kali), kuis otomatis dikumpulkan/dikunci.
- Klik kanan, copy-paste, dan kombinasi tombol umum (mis. PrintScreen, sejauh dapat dideteksi lewat keyboard event) dinonaktifkan sebagai lapisan *deterrent* tambahan.
- **Watermark dinamis** (nama murid + NIS + waktu) ditampilkan transparan menimpa soal — mengurangi motivasi menyebarkan screenshot karena identitas murid ikut ter-capture di dalamnya.
- Seluruh peristiwa (snapshot kamera, pelanggaran fullscreen/tab-switch) dicatat sebagai log proctoring per sesi kuis murid, dapat ditinjau Guru setelah kuis selesai.
- **Catatan teknis & etis penting:** pencegahan screenshot/perekaman layar secara **100% tidak dimungkinkan** oleh teknologi web (keterbatasan browser & sistem operasi — mis. tetap bisa difoto pakai HP lain). Fitur di atas bersifat *deterrent* dan pendeteksi pelanggaran, bukan jaminan mutlak; lihat catatan risiko di Bagian 15. Karena melibatkan pengambilan gambar wajah murid (yang mayoritas berstatus anak di bawah umur), fitur ini memerlukan **persetujuan orang tua/wali** di awal pendaftaran akun, retensi snapshot dibatasi (mis. otomatis terhapus 30 hari setelah kuis selesai kecuali ditandai sebagai bukti pelanggaran), dan akses log hanya untuk Guru pengampu & Admin — lihat Bagian 11 & 16.

---

## 6. Alur Pengguna Utama (Key User Flows)

### 6.1 Alur Presensi Harian Murid

1. Admin (atau Guru yang sudah presensi — lihat 6.6) membuka satu atau beberapa Titik QR Presensi di Admin/Guru Panel — sistem menampilkan QR Code yang otomatis refresh token setiap 1 detik via Laravel Reverb, di titik manapun yang diaktifkan.
2. Murid membuka aplikasi (PWA) dan memindai QR di titik manapun yang tersedia/terdekat menggunakan kamera HP.
3. Sistem memvalidasi: (a) keabsahan token QR saat itu, (b) koordinat GPS HP murid berada dalam radius sekolah yang dikonfigurasi Admin.
4. Jika valid, sistem mencatat presensi (status Hadir/Terlambat berdasarkan jam, beserta titik asal presensi) dan mengirim push notification (FCM) ke akun Wali berisi jam check-in. Data langsung tersinkron ke dashboard Admin dari titik manapun presensi terjadi.
5. Proses yang sama berulang saat jam pulang untuk mencatat check-out dan mengirim notifikasi jam pulang.
6. Jika token kedaluwarsa atau lokasi di luar radius, sistem menolak dan menampilkan pesan error ke murid.

### 6.2 Alur Pembayaran SPP

1. Admin membuat/menjadwalkan tagihan SPP bulanan untuk seluruh murid atau kelas tertentu.
2. Tagihan otomatis muncul di dashboard akun Wali dan akun Murid terkait secara bersamaan.
3. Wali (atau Murid) memilih tagihan dan melakukan pembayaran melalui metode yang tersedia (VA/QRIS/E-Wallet) via payment gateway.
4. Payment gateway mengirim callback/webhook status pembayaran ke sistem Lumora.
5. Sistem memvalidasi callback, mengubah status tagihan menjadi "Lunas", menghapus notifikasi tagihan di kedua akun, dan men-generate resi PDF.
6. Wali dapat mengunduh resi PDF kapan saja dari riwayat transaksi.

### 6.3 Alur Pengajuan Izin/Sakit

1. Wali membuka menu Perizinan, memilih anak (jika lebih dari satu), dan mengisi form izin/sakit beserta unggahan bukti.
2. Pengajuan masuk ke daftar approval Guru mata pelajaran/wali kelas terkait pada tanggal tersebut.
3. Guru/Admin meninjau dan menyetujui atau menolak pengajuan.
4. Jika disetujui, status presensi murid pada tanggal tersebut otomatis diperbarui menjadi Izin/Sakit dan Wali menerima notifikasi status.

### 6.4 Alur Generate Kuis via AI

1. Guru membuka menu Kuis pada sesi tertentu, memilih materi sumber yang sudah pernah dibagikan, dan menentukan parameter (jumlah soal, tingkat kesulitan, tipe soal).
2. Sistem mengirim isi materi beserta prompt terstruktur ke Gemini API untuk menghasilkan draf soal.
3. Draf soal hasil generate ditampilkan ke Guru dalam mode Review — Guru dapat mengedit, menghapus, atau menambah soal.
4. Guru mempublikasikan kuis setelah puas dengan hasil review; kuis baru muncul ke murid setelah tahap ini.
5. Jika API tidak merespons/gagal, sistem menampilkan notifikasi kegagalan dan Guru dapat beralih membuat kuis secara manual.

### 6.5 Alur Pengumpulan & Pemeriksaan Tugas via Google Drive

1. Murid mengerjakan tugas di Google Drive pribadinya, mengatur izin *share* file menjadi dapat diakses (minimal "Anyone with link – Viewer"), lalu menempelkan tautan tersebut pada form pengumpulan tugas di Lumora.
2. Sistem memvalidasi format tautan dan memverifikasi aksesibilitas file, lalu mencatat status pengumpulan beserta timestamp verifikasi akses.
3. Guru membuka daftar tugas masuk dan mem-preview isi file langsung di dalam aplikasi (embed Google Drive Viewer) tanpa mengunduh file.
4. Guru memberi nilai dan catatan langsung di Lumora; jika ingin memberi anotasi pada file (mis. Google Docs), Guru dapat membuka file di tab baru.
5. Jika sistem mendeteksi tautan tidak lagi dapat diakses saat guru membuka preview, status berubah menjadi "Tidak Dapat Diakses" dan Guru dapat meminta murid memperbaiki izin share.

### 6.6 Alur Presensi Guru & Pembukaan Titik QR Tambahan

1. Guru tiba di sekolah dan melakukan presensi kehadirannya sendiri (Presensi Guru) melalui QR/metode presensi yang sama dengan murid, di titik manapun yang aktif.
2. Setelah presensi guru tercatat "Hadir", menu "Buka Titik QR Presensi Murid" menjadi aktif pada akun guru tersebut.
3. Guru dapat membuka titik QR baru (mis. menampilkannya di layar kelas atau perangkatnya) untuk membantu menampung presensi murid, mengurangi antrian di titik utama yang dikelola Admin/guru piket.
4. Seluruh presensi murid yang masuk dari titik yang dibuka Guru ini otomatis tersinkron secara real-time ke data presensi pusat yang sama dengan titik-titik lain — Admin tetap memiliki visibilitas penuh dari dashboard terpusat.
5. Admin dapat menutup titik QR kapan saja (mis. di luar jam presensi) untuk mencegah penyalahgunaan di luar waktu yang seharusnya.

---

## 7. Kebutuhan Non-Fungsional

| Aspek | Kebutuhan |
|---|---|
| Kinerja (Performance) | Waktu respons scan QR presensi maksimal 2 detik pada kondisi jaringan 4G normal, termasuk saat banyak Titik QR aktif bersamaan. Halaman utama dashboard termuat < 3 detik. |
| Skalabilitas (Menghindari Lag) | Arsitektur *stateless* di Application Layer sehingga dapat di-*scale* horizontal (banyak instance di belakang load balancer) tanpa perubahan kode; Redis dipakai sebagai *cache*, *session store*, dan *broadcasting driver* Laravel Reverb agar WebSocket tetap konsisten meski di-scale ke banyak instance; proses berat (notifikasi FCM massal, generate PDF, panggilan AI Quiz Generator) dijalankan lewat Queue Worker terpisah agar tidak memblokir request utama; database di-*index* pada kolom yang sering di-query (student_id, date, class_id, dsb); disiapkan *read replica* database untuk laporan berat (keuangan, raport) agar tidak membebani database utama yang dipakai presensi real-time; CDN dipakai untuk aset statis (CSS/JS/gambar); *load testing* wajib dilakukan sebelum go-live khusus untuk skenario *burst traffic* jam presensi pagi (ratusan/ribuan request dalam window singkat). |
| Keamanan Aplikasi | Enkripsi password (bcrypt/argon2), HTTPS wajib di seluruh endpoint (HSTS), token QR bersifat single-use & time-bound, rate limiting & *account lockout* pada endpoint presensi/login untuk mencegah *brute-force*, proteksi CSRF bawaan Laravel di seluruh form, *output escaping* otomatis (Blade) untuk mencegah XSS, *query* terparameterisasi via Eloquent ORM untuk mencegah SQL Injection, validasi & sanitasi input di seluruh form termasuk unggahan file (whitelist tipe file), *security headers* (X-Frame-Options, X-Content-Type-Options, Content-Security-Policy), 2FA wajib untuk Admin (lihat 5.1), serta audit dependency (Composer/NPM) berkala untuk menutup celah keamanan pustaka pihak ketiga. |
| Ketersediaan (Availability) | Target uptime 99.5% pada jam operasional sekolah (06.00–17.00), didukung monitoring & alerting (mis. Sentry/Laravel Telescope untuk deteksi dini bottleneck/error produksi). |
| Privasi Data | Data lokasi (GPS), dokumen izin/sakit, dan *snapshot* kamera proctoring kuis hanya dapat diakses oleh role berwenang (Admin/Guru terkait); snapshot kamera memiliki retensi terbatas (default 30 hari, otomatis terhapus kecuali ditandai bukti pelanggaran) dan memerlukan persetujuan orang tua/wali; kepatuhan terhadap prinsip perlindungan data pribadi murid & wali secara umum. |
| Kompatibilitas | Desktop: Chrome, Edge, Firefox versi terbaru. Mobile PWA: Android Chrome & iOS Safari (mendukung instalasi ke home screen). |
| Audit & Log | Seluruh transaksi presensi, pembayaran, dan perubahan nilai memiliki log aktivitas (siapa, kapan, aksi apa) untuk keperluan audit Admin. |
| Backup & Recovery | Backup database otomatis harian dengan retensi minimal 30 hari; prosedur restore terdokumentasi. |
| Aksesibilitas | Kontras warna skema biru memenuhi standar keterbacaan (WCAG AA) pada teks dan komponen interaktif utama. |

---

## 8. Tech Stack & Arsitektur Sistem

### 8.1 Ringkasan Teknologi

| Komponen | Teknologi |
|---|---|
| Backend Framework | Laravel 11 (PHP) |
| Database | MySQL dengan Laravel Migration & Seeder |
| Realtime Engine | Laravel Reverb (WebSocket) — untuk QR dinamis & notifikasi live |
| Frontend Styling | Tailwind CSS — tema warna Deep Blue #1E3A8A & Electric Blue #2563EB |
| Platform Tampilan | Web Responsif — Desktop (Admin/Guru) & Mobile PWA (Murid/Wali) |
| Push Notification | Firebase Cloud Messaging (FCM) |
| Payment Gateway | Midtrans / Xendit (Virtual Account, QRIS, E-Wallet) |
| Generate PDF | Library PDF (mis. DomPDF/Snappy) untuk resi & raport |
| Autentikasi | Laravel Sanctum/Breeze dengan middleware role-based (spatie/laravel-permission direkomendasikan) + Google OAuth/SSO + Laravel Fortify (2FA untuk Admin) |
| Queue & Job | Laravel Queue (Redis driver) untuk notifikasi, generate PDF, dan panggilan AI Quiz Generator agar tidak memblokir request utama |
| Cache, Session & Broadcasting | Redis — dipakai sebagai cache layer, session store, dan broadcasting driver Laravel Reverb agar konsisten saat di-scale ke banyak instance server |
| AI / LLM untuk Kuis | Google Gemini API (rekomendasi awal, *free tier* tersedia) — diabstraksi lewat service layer agar mudah diganti provider |
| Integrasi File Tugas | Google Drive API (validasi akses & metadata file) + Google Drive Embed Viewer untuk preview tanpa unduh |
| Proctoring Kuis | Browser Camera API (getUserMedia), Fullscreen API, Page Visibility API — deteksi pelanggaran & snapshot berkala |
| Skalabilitas Infrastruktur | Load Balancer + multi-instance Application Server (horizontal scaling), CDN untuk aset statis, database read replica untuk laporan berat |

### 8.2 Gambaran Arsitektur

Lumora menggunakan arsitektur monolitik modular berbasis Laravel dengan pemisahan yang jelas antar modul (Presensi, Akademik, Keuangan, Komunikasi). Komunikasi real-time (QR dinamis dan notifikasi) ditangani oleh Laravel Reverb sebagai WebSocket server internal, sementara notifikasi push ke perangkat mobile diteruskan melalui Firebase Cloud Messaging. Payment gateway terintegrasi melalui webhook yang divalidasi signature-nya untuk keamanan transaksi.

- **Client Layer:** Web app responsif (Blade/Tailwind atau SPA ringan) yang berjalan sebagai PWA di perangkat mobile.
- **Application Layer:** Laravel 11 — Controllers, Services, dan Jobs untuk logika presensi, keuangan, akademik.
- **Realtime Layer:** Laravel Reverb — broadcast channel untuk refresh token QR (mendukung banyak Titik QR paralel) dan notifikasi live ke dashboard, memakai Redis sebagai *broadcasting driver* agar tetap konsisten saat di-scale ke banyak instance.
- **Integration Layer:** FCM (push notification), Payment Gateway (Midtrans/Xendit webhook), Google Gemini API (AI Quiz Generator, diakses lewat `QuizGenerationService` yang dapat diganti provider), Google Drive API & Google OAuth (validasi akses file tugas, preview, dan login SSO).
- **Data Layer:** MySQL sebagai single source of truth (dengan *read replica* untuk laporan berat), Redis untuk cache/session/queue, dengan migration terversi untuk seluruh skema.
- **Infrastructure Layer:** Load Balancer + multi-instance Application Server untuk horizontal scaling, CDN untuk aset statis — dirancang khusus untuk menyerap lonjakan *concurrent request* singkat saat jam presensi pagi tanpa membuat aplikasi terasa lag.

---

## 9. Arsitektur Basis Data (Database Schema)

Berikut rancangan skema tabel utama MySQL. Skema ini merupakan pengembangan dari struktur awal yang diberikan, dengan tambahan tabel-tabel pendukung yang diperlukan agar seluruh modul fungsional dapat berjalan.

| Tabel | Kolom Utama |
|---|---|
| `users` | id, name, email, password, role [admin, teacher, student, parent], phone, photo, is_active |
| `parent_student` | parent_id, student_id — relasi many-to-many Wali ↔ Murid |
| `academic_years` | id, year_label, start_date, end_date, is_active |
| `majors` | id, name (mis. IPA/IPS/Bahasa atau RPL/TKJ untuk SMK), code — kosong/tidak dipakai untuk jenjang tanpa penjurusan |
| `classes` (Rombel) | id, academic_year_id, grade_level, major_id (nullable), name (mis. "XI IPA 1"), homeroom_teacher_id |
| `class_students` | id, class_id, student_id, status [active, moved, graduated] |
| `subjects` | id, name, code |
| `curriculum_subjects` | id, academic_year_id, grade_level, major_id (nullable), subject_id, is_mandatory, weekly_hours — menentukan mapel yang berlaku per tingkat/jurusan/tahun ajaran |
| `class_sessions` | id, class_id, subject_id, teacher_id, room_name, day, start_time, end_time |
| `qr_stations` | id, label (mis. "Gerbang A", "Lobi Utama"), opened_by (user_id), opened_at, closed_at, status [active, inactive] — titik/stasiun presensi yang dapat dibuka paralel oleh Admin atau Guru yang sudah presensi |
| `attendances` | id, student_id, date, check_in, check_out, status [hadir, terlambat, izin, sakit, alpa], lat, long, qr_token_id, station_id (FK ke `qr_stations`) |
| `teacher_attendances` | id, teacher_id, date, check_in, check_out, method [qr, manual], station_id — presensi guru, prasyarat sebelum guru dapat membuka titik QR murid |
| `session_attendances` | id, session_id, student_id, status, method [manual, qr], recorded_by |
| `leave_requests` | id, student_id, requested_by (parent_id), type [izin, sakit], date_start, date_end, attachment_path, status [pending, approved, rejected], approved_by |
| `materials` | id, session_id, title, file_path/link, uploaded_by, created_at |
| `assignments` | id, session_id, title, description, due_date, max_score |
| `assignment_submissions` | id, assignment_id, student_id, drive_file_link, drive_file_id, drive_file_name, last_verified_at, access_status [accessible, inaccessible], submitted_at, score, status — **tidak menyimpan file fisik**, hanya metadata tautan Google Drive |
| `quizzes` | id, session_id, title, duration_minutes, generation_source [manual, ai_generated], source_material_id (nullable, FK ke `materials`), secure_mode_enabled (boolean), max_violation_count |
| `quiz_questions` | id, quiz_id, question_text, type, options_json, correct_answer |
| `quiz_results` | id, quiz_id, student_id, score, submitted_at, violation_count |
| `quiz_proctoring_logs` | id, quiz_result_id, event_type [camera_snapshot, tab_switch, fullscreen_exit, camera_off], snapshot_path (nullable, terenkripsi, retensi terbatas), occurred_at |
| `ai_generation_logs` | id, teacher_id, source_material_id, quiz_id (nullable), provider [gemini, openai], prompt_params_json, tokens_used, status, created_at — audit permintaan generate AI & pemantauan biaya |
| `grades` | id, student_id, session_id, assignment_score, quiz_score, midterm_score, final_score, manual_note |
| `report_cards` | id, student_id, academic_year_id, semester, published_at, pdf_path |
| `spp_bills` | id, student_id, month, year, amount, status [pending, paid], paid_at, paid_by, payment_method, receipt_pdf_path |
| `payment_transactions` | id, spp_bill_id, gateway, gateway_ref_id, amount, status, raw_payload, created_at |
| `school_calendar` | id, title, type [libur, ujian, agenda], date_start, date_end, target_scope |
| `announcements` | id, title, content, target_role, target_class_id, created_by, published_at |
| `chat_threads` | id, type [personal, group], scope [class, subject, general] |
| `chat_participants` | id, thread_id, user_id |
| `chat_messages` | id, thread_id, sender_id, message, attachment_path, sent_at |
| `notifications` | id, user_id, type, title, body, is_read, created_at |
| `school_settings` | id, school_name, latitude, longitude, geofence_radius_meters, logo_path |
| `activity_logs` | id, user_id, action, subject_type, subject_id, meta_json, created_at |

### Catatan Relasi Penting

- `parent_student` bersifat many-to-many agar satu Wali dapat memiliki lebih dari satu Murid, dan (opsional) satu Murid dapat memiliki lebih dari satu Wali (Ayah & Ibu).
- `attendances.qr_token_id` mereferensikan token QR dinamis yang aktif saat presensi dilakukan, untuk keperluan audit anti-fraud.
- `spp_bills` dan `payment_transactions` dipisah agar satu tagihan dapat memiliki riwayat percobaan pembayaran (retry) sebelum berhasil.
- `class_students` menyimpan status agar histori murid per kelas per tahun ajaran tetap dapat ditelusuri saat proses kenaikan kelas/rollover.
- `curriculum_subjects` menjadi sumber kebenaran (*source of truth*) untuk validasi saat `class_sessions` dibuat — mata pelajaran yang tidak terdaftar di kurikulum rombel tersebut tidak dapat dijadwalkan, mencegah kesalahan input.
- `assignment_submissions` sengaja tidak memiliki kolom `file_path` menuju storage Lumora — seluruh file tugas tetap berada di Google Drive murid; sistem hanya menyimpan referensi (`drive_file_id`) dan status verifikasi akses, sehingga tidak menambah beban storage server.
- `quizzes.generation_source` membedakan kuis manual dan hasil AI tanpa memerlukan struktur tabel terpisah — kedua jenis kuis diperlakukan sama pada tahap pengerjaan dan penilaian oleh murid.
- `qr_stations` memungkinkan banyak titik presensi aktif bersamaan tanpa memecah sumber data — semua `attendances` tetap merujuk ke satu tabel pusat, hanya `station_id` yang membedakan titik asal untuk keperluan audit/monitoring, bukan sistem terpisah.
- `teacher_attendances` sengaja dipisah dari `attendances` (yang khusus murid) karena beda konteks bisnis (guru tidak dinilai "terlambat/alpa" dengan aturan sama seperti murid), namun tervalidasi lewat mekanisme QR yang sama — status "Hadir" di tabel ini menjadi syarat sebelum guru diizinkan membuka `qr_stations` baru.
- `quiz_proctoring_logs.snapshot_path` disimpan terenkripsi dengan retensi terbatas (lihat Bagian 11) karena berisi gambar wajah murid yang sensitif secara privasi.

---

## 10. Integrasi Pihak Ketiga

| Layanan | Fungsi |
|---|---|
| Midtrans / Xendit | Payment gateway untuk SPP: Virtual Account, QRIS, E-Wallet. Webhook untuk update status pembayaran otomatis. |
| Firebase Cloud Messaging (FCM) | Push notification ke aplikasi Wali/Murid/Guru untuk presensi, tagihan, pengumuman, dan chat. |
| Laravel Reverb | WebSocket server internal untuk refresh QR dinamis dan update dashboard real-time (tanpa perlu layanan pihak ketiga eksternal). |
| Library GPS/Geolocation (Browser API) | Mengambil koordinat lokasi murid saat scan QR untuk validasi geofencing. |
| Google Gemini API | Menghasilkan draf soal kuis otomatis dari teks materi yang diunggah guru (AI Quiz Generator); diakses lewat service layer agar mudah diganti provider LLM lain. |
| Google Drive API | Memvalidasi tautan & status akses file tugas yang dikumpulkan murid, serta mengambil metadata file (nama, tipe) tanpa mengunduh isinya ke server. |
| Google Drive Embed Viewer | Menampilkan preview isi file tugas murid langsung di dalam aplikasi Guru (iframe), tanpa proses unduh. |
| Google OAuth / Google Identity Services | Login SSO memakai akun Google Workspace for Education sekolah (bila tersedia) atau akun Gmail pribadi; juga menjadi basis otorisasi akses Google Drive API. |

---

## 11. Pertimbangan Keamanan & Skalabilitas

### 11.1 Keamanan Akun & Akses (Mencegah Penyusup)

- **Sistem closed-registration**: tidak ada halaman pendaftaran mandiri; seluruh akun dibuat oleh Admin, dan Login Google (bila dipakai) hanya mengautentikasi akun yang emailnya sudah terdaftar — bukan pintu masuk otomatis bagi pihak tak dikenal.
- Role-Based Access Control (RBAC) diterapkan di setiap endpoint API/route menggunakan middleware, bukan hanya di sisi tampilan.
- Two-Factor Authentication (2FA) wajib untuk Admin, rate limiting & *account lockout* otomatis setelah percobaan login gagal berulang untuk mencegah *brute-force*, serta notifikasi login dari perangkat/lokasi baru ke pemilik akun.
- Enkripsi data sensitif (password dengan bcrypt/argon2, dokumen izin/sakit) dan penggunaan HTTPS wajib di seluruh komunikasi.

### 11.2 Keamanan Presensi & Titik QR

- Token QR presensi bersifat *time-bound* (kedaluwarsa dalam hitungan detik) dan *single-use* untuk mencegah *replay*/*screenshot attack*.
- Validasi ganda: token QR valid DAN koordinat GPS dalam radius — presensi ditolak jika salah satu syarat tidak terpenuhi, berlaku di semua Titik QR aktif.
- Guru hanya dapat membuka Titik QR presensi murid **setelah** presensi kehadirannya sendiri tervalidasi (lihat 5.2/6.6), mencegah pihak yang belum terverifikasi hadir untuk membuka titik presensi baru; Admin dapat menutup titik QR kapan saja.

### 11.3 Keamanan Mode Kuis Aman & Privasi Data Anak

- *Snapshot* kamera saat proctoring kuis disimpan terenkripsi dengan retensi terbatas (default 30 hari, otomatis terhapus kecuali ditandai bukti pelanggaran), dan hanya dapat diakses Guru pengampu kuis terkait & Admin.
- **Persetujuan orang tua/wali wajib diperoleh** di awal pendaftaran akun murid sebelum fitur kamera proctoring dapat diaktifkan pada kuis, mengingat mayoritas pengguna murid berstatus anak di bawah umur.
- Fitur anti-*tab-switch*, anti-*copy-paste*, dan watermark dinamis bersifat sebagai lapisan *deterrent* dan pendeteksi pelanggaran — **bukan jaminan mutlak** terhadap kecurangan, karena pencegahan screenshot/rekam layar 100% tidak dimungkinkan oleh teknologi web (lihat catatan teknis di 5.8 dan risiko di Bagian 15).

### 11.4 Keamanan Umum Aplikasi (Web Security)

- Proteksi CSRF bawaan Laravel pada seluruh form; *output escaping* otomatis (Blade) untuk mencegah XSS; *query* terparameterisasi via Eloquent ORM untuk mencegah SQL Injection.
- Validasi & sanitasi input di seluruh form, termasuk *whitelist* tipe file pada unggahan dokumen (mis. bukti izin/sakit).
- *Security headers* diterapkan di seluruh respons: HSTS, X-Frame-Options, X-Content-Type-Options, Content-Security-Policy.
- Validasi *signature* webhook payment gateway untuk mencegah pemalsuan notifikasi pembayaran lunas.
- Chat dibatasi ruang lingkupnya (*scope-based*) sesuai relasi kelas untuk mencegah kontak tidak sah antar pengguna.
- Activity log untuk semua aksi sensitif (approval izin, perubahan nilai, konfirmasi pembayaran, pembukaan/penutupan Titik QR) guna keperluan audit trail.
- Audit dependency (Composer/NPM) berkala untuk menutup celah keamanan pustaka pihak ketiga.
- Verifikasi aksesibilitas tautan Google Drive dilakukan saat submit dan dicatat waktunya (`last_verified_at`); sistem menampilkan peringatan bila status berubah menjadi tidak dapat diakses saat guru membuka preview.
- Materi yang dikirim ke Gemini API untuk AI Quiz Generator dibatasi hanya materi milik sekolah/guru pemohon (tidak ada data murid personal dalam prompt), dan permintaan generate dibatasi rate limit per guru untuk mencegah penyalahgunaan/pembengkakan biaya API.
- Hasil generate AI wajib melalui tahap review guru sebelum publish — mencegah konten yang tidak akurat langsung diterima murid sebagai bahan penilaian.

### 11.5 Skalabilitas & Performa (Menghindari Lag)

- Arsitektur *stateless* pada Application Layer agar dapat di-*scale* horizontal (banyak instance server di belakang *load balancer*) tanpa perubahan kode, khususnya untuk menghadapi lonjakan bersamaan saat jam presensi pagi (banyak Titik QR aktif + banyak murid scan dalam window singkat).
- Redis dipakai sebagai *cache*, *session store*, dan *broadcasting driver* untuk Laravel Reverb, sehingga WebSocket (refresh token QR, notifikasi live) tetap konsisten meski aplikasi di-scale ke banyak instance server.
- Proses berat (pengiriman notifikasi FCM massal, generate PDF raport/resi, pemanggilan AI Quiz Generator ke Gemini API) dijalankan lewat Queue Worker terpisah dari *request-response cycle* utama, agar tidak membuat aplikasi terasa lag bagi pengguna.
- *Database indexing* pada kolom yang sering di-*query* (`student_id`, `date`, `class_id`, `station_id`, dsb); *read replica* database disiapkan untuk laporan berat (keuangan, raport) agar tidak membebani database utama yang dipakai presensi real-time.
- CDN dipakai untuk aset statis (CSS/JS/gambar) agar waktu muat halaman tetap cepat meski diakses dari banyak titik/perangkat bersamaan.
- Monitoring & *alerting* produksi (mis. Sentry) untuk mendeteksi *bottleneck*/error sebelum berdampak luas ke pengguna.
- *Load testing* wajib dilakukan sebelum go-live, khusus mensimulasikan skenario *burst traffic* jam presensi pagi dan jam pengumpulan tugas mendekati tenggat.

---

## 12. Prinsip Desain UI/UX

- Skema warna dominan biru: Deep Blue (#1E3A8A) untuk elemen utama/header/navigasi, Electric Blue (#2563EB) untuk aksen, tombol aksi, dan highlight.
- Dua mode tampilan: Desktop (dioptimalkan untuk Admin & Guru — data density lebih tinggi, tabel & dashboard kompleks) dan Mobile PWA (dioptimalkan untuk Murid & Wali — navigasi bottom-bar, kartu ringkas, aksi cepat scan QR).
- Dashboard ringkas per role menampilkan informasi paling relevan di atas (mis. Wali: notifikasi presensi hari ini & tagihan aktif; Murid: jadwal hari ini & tugas mendekati tenggat).
- Komponen QR presensi ditampilkan besar dan jelas di layar guru/pos absensi dengan indikator visual countdown refresh token.
- Notifikasi (bell icon) terpusat untuk presensi, tagihan, tugas baru, dan pesan chat.

---

## 13. Metrik Keberhasilan (Success Metrics / KPI)

| Metrik | Target |
|---|---|
| Adopsi Presensi Digital | ≥ 95% murid melakukan presensi via QR dalam 1 bulan pertama peluncuran |
| Penurunan Kecurangan Presensi | Penurunan signifikan laporan/insiden "titip absen" dibanding metode sebelumnya |
| Kecepatan Pembayaran SPP | ≥ 80% tagihan SPP terbayar melalui payment gateway dalam 7 hari sejak tagihan terbit |
| Waktu Proses Raport | Waktu penyusunan & publikasi raport berkurang ≥ 50% dibanding proses manual |
| Keterlibatan Wali | ≥ 90% akun Wali membuka notifikasi presensi anak setiap hari sekolah |
| Uptime Sistem | ≥ 99.5% selama jam operasional sekolah |

---

## 14. Roadmap & Milestone Pengembangan

### Fase 1 — MVP (Core)

- Manajemen multi-role & akun (closed-registration, akun didaftarkan Admin), relasi Parent-Child, Login Google untuk akun terdaftar, 2FA Admin.
- Presensi QR dinamis multi-titik + geofencing + Presensi Guru + notifikasi FCM ke Wali.
- Modul akademik dasar: rombel & kurikulum per tingkat/jurusan, jadwal, materi.
- Pengumpulan tugas via tautan Google Drive + preview guru tanpa unduh.
- SPP digital dengan integrasi payment gateway dasar.
- Fondasi keamanan & skalabilitas dasar: rate limiting, RBAC, caching Redis, arsitektur stateless siap horizontal scaling.

### Fase 2 — Penyempurnaan Akademik & Keuangan

- Kuis online manual dengan penilaian otomatis untuk pilihan ganda.
- AI Quiz Generator (generate soal dari materi via Gemini API) beserta alur review guru.
- Mode Kuis Aman (kamera wajib, deteksi tab-switch/fullscreen, watermark) untuk kuis manual maupun AI.
- Raport digital & export PDF.
- Pengajuan izin/sakit digital dengan approval workflow.
- Laporan keuangan sekolah untuk Admin.

### Fase 3 — Komunikasi & Skalabilitas

- Fitur chat privacy-scoped penuh (personal & grup kelas).
- Modul kenaikan kelas / rollover tahun ajaran & pengarsipan.
- Optimasi performa untuk skala multi-sekolah (jika dibutuhkan sebagai SaaS): load balancer, read replica database, CDN, load testing menyeluruh.
- Dashboard analitik lanjutan untuk Admin (tren kehadiran, tren pembayaran, tren penggunaan Titik QR).

---

## 15. Risiko & Mitigasi

| Risiko | Mitigasi |
|---|---|
| GPS tidak akurat di dalam gedung | Sediakan toleransi radius yang dapat dikonfigurasi Admin per sekolah, serta mode fallback presensi manual oleh guru piket bila diperlukan. |
| Ketergantungan pada koneksi internet murid saat scan QR | Optimasi payload request seringan mungkin; sediakan indikator jaringan & retry otomatis pada PWA. |
| Downtime payment gateway | Simpan status transaksi pending dan lakukan rekonsiliasi otomatis via polling/API status saat webhook gagal diterima. |
| Penyalahgunaan fitur chat | Terapkan scope-based permission ketat serta kemampuan moderasi/reporting oleh Admin. |
| Resistensi adopsi oleh guru/wali yang kurang familiar teknologi | Sediakan onboarding, tutorial dalam aplikasi, serta pelatihan awal bagi staf sekolah. |
| Soal hasil AI Quiz Generator kurang akurat / "halusinasi" LLM | Wajibkan alur review-edit oleh guru sebelum kuis dipublikasikan; tidak ada publish otomatis tanpa persetujuan manusia. |
| Murid mengubah izin akses atau menghapus file Google Drive setelah submit tugas | Verifikasi akses saat submit & saat guru membuka preview, catat status terakhir diverifikasi, serta edukasi murid melalui pesan sistem agar tidak mengubah permission sampai dinilai. |
| Biaya API Gemini membengkak akibat penggunaan berlebihan | Terapkan rate limiting permintaan generate AI per guru per hari, serta pantau `tokens_used` di `ai_generation_logs` untuk kendali biaya. |
| Sekolah belum memiliki Google Workspace for Education | Sistem tetap berfungsi penuh dengan email/akun pribadi (Gmail atau lainnya) untuk login; hanya kapasitas storage institusional yang tidak otomatis tersedia — fitur submit tugas tetap berjalan karena memakai akun Google pribadi murid, bukan akun institusional. |
| Fitur anti-screenshot/anti-tab-switch pada Mode Kuis Aman tidak 100% mencegah kecurangan (keterbatasan teknologi web/browser) | Kombinasikan dengan watermark identitas murid, log pelanggaran untuk investigasi manual guru, dan edukasi kebijakan akademik terkait konsekuensi pelanggaran — komunikasikan sejak awal bahwa fitur ini bersifat pencegah & pendeteksi, bukan jaminan mutlak. |
| Concern privasi atas snapshot kamera murid (terutama anak di bawah umur) saat proctoring kuis | Wajibkan persetujuan orang tua/wali di awal pendaftaran, batasi retensi penyimpanan (default 30 hari), enkripsi penyimpanan, dan batasi akses hanya untuk Guru pengampu & Admin. |
| Guru yang belum presensi tapi mencoba membuka Titik QR presensi murid | Validasi status `teacher_attendances` di backend sebelum mengizinkan pembukaan `qr_stations` baru — bukan hanya validasi di sisi tampilan, mencegah bypass lewat manipulasi request langsung. |
| Lonjakan traffic serentak saat jam presensi pagi (ratusan/ribuan request dalam window singkat) menyebabkan lag/downtime | Terapkan caching (Redis), queue untuk proses berat, horizontal scaling di belakang load balancer, dan wajib load testing skenario burst traffic sebelum go-live. |

---

## 16. Asumsi & Dependensi

- Sekolah memiliki koneksi internet stabil di area presensi (untuk WebSocket Reverb) dan seluruh murid/wali memiliki akses smartphone dengan browser modern.
- Sekolah menyediakan data koordinat lokasi (latitude/longitude) area sekolah untuk konfigurasi geofencing.
- Sekolah telah memiliki akun merchant aktif di Midtrans atau Xendit untuk integrasi pembayaran.
- Template raport PDF mengikuti format standar yang disepakati bersama pihak sekolah pada tahap awal implementasi.
- Kebijakan privasi data (lokasi, dokumen izin) disetujui dan dikomunikasikan kepada orang tua/wali sebelum peluncuran.
- **Lumora tidak menyediakan layanan email sendiri.** Sekolah disarankan mendaftar **Google Workspace for Education** (gratis untuk institusi pendidikan yang memenuhi syarat) agar staf dan murid memiliki akun institusional (mis. `@namasekolah.sch.id`) beserta Google Drive berkapasitas besar. Bila sekolah belum memiliki Workspace, seluruh fungsi utama Lumora tetap berjalan dengan akun email pribadi (termasuk Gmail) untuk login, dan akun Google pribadi murid untuk pengumpulan tugas via Drive.
- Setiap murid dan guru memiliki (atau dapat membuat) akun Google pribadi untuk memanfaatkan fitur pengumpulan tugas via Google Drive; sekolah perlu mengomunikasikan tata cara pengaturan izin *share* file kepada murid pada awal implementasi.
- Penggunaan AI Quiz Generator bergantung pada ketersediaan dan kebijakan *free tier* Google Gemini API yang berlaku saat implementasi; bila kuota atau kebijakan berubah, arsitektur service layer memungkinkan migrasi ke provider LLM lain tanpa merombak sistem inti.
- Struktur kurikulum (mata pelajaran per tingkat/jurusan) disiapkan dan divalidasi oleh pihak sekolah sebelum tahun ajaran dimulai, sebagai data masukan ke tabel `curriculum_subjects`.
- Orang tua/wali memberikan persetujuan (consent) tertulis atas penggunaan kamera perangkat murid untuk keperluan Mode Kuis Aman (proctoring), yang dikomunikasikan dan diminta persetujuannya saat awal pendaftaran akun murid oleh Admin.
- Sekolah menyediakan perangkat (HP/laptop dengan kamera berfungsi) dan koneksi internet yang cukup stabil bagi murid saat mengerjakan kuis dengan Mode Kuis Aman aktif.
- Sekolah menyediakan minimal satu perangkat/layar tambahan (TV/monitor/laptop) untuk setiap Titik QR Presensi tambahan yang ingin diaktifkan Admin atau Guru, di luar titik utama yang sudah ada.
- Kapasitas infrastruktur server (jumlah instance, spesifikasi database) disesuaikan dengan estimasi jumlah pengguna aktual sekolah; RAB awal mengasumsikan skala hingga ±1.000 pengguna aktif — skala lebih besar memerlukan penyesuaian anggaran infrastruktur (load balancer, instance tambahan).

---

## 17. Lampiran: Glosarium

| Istilah | Definisi |
|---|---|
| PWA | Progressive Web App — aplikasi web yang dapat diinstal & berjalan seperti aplikasi native di perangkat mobile. |
| Geofencing | Teknik validasi lokasi berbasis radius koordinat GPS untuk memastikan pengguna berada di area tertentu. |
| Dynamic QR | QR Code yang token/isinya berubah secara berkala (dalam hitungan detik) untuk mencegah penyalahgunaan. |
| FCM | Firebase Cloud Messaging — layanan push notification lintas platform dari Google. |
| Laravel Reverb | Server WebSocket resmi Laravel untuk komunikasi real-time antara backend dan client. |
| Rollover Tahun Ajaran | Proses pemindahan massal data murid ke tingkat kelas berikutnya di awal tahun ajaran baru. |
| RBAC | Role-Based Access Control — model kontrol akses berdasarkan peran pengguna. |
| Rombel | Rombongan Belajar — satuan kelas yang dibentuk berdasarkan kombinasi tingkat dan jurusan (mis. "XI IPA 1"), mengikuti struktur kurikulum tahun ajaran berjalan. |
| LLM | Large Language Model — model AI generatif (mis. Google Gemini) yang dipakai Lumora untuk menghasilkan draf soal kuis dari teks materi. |
| Human-in-the-loop | Pendekatan di mana keluaran AI (mis. draf soal kuis) wajib ditinjau dan disetujui manusia (guru) sebelum digunakan secara resmi. |
| Google Workspace for Education | Paket layanan Google (email, Drive, dan produktivitas) gratis untuk institusi pendidikan yang memenuhi syarat, menjadi dasar rekomendasi strategi email/SSO Lumora. |
| Google Drive Embed Viewer | Komponen tampilan (iframe) dari Google yang menampilkan isi file Drive langsung di halaman web lain tanpa perlu mengunduh file tersebut. |
| Closed-Registration | Model akun di mana sistem tidak menyediakan pendaftaran mandiri untuk publik; seluruh akun hanya dapat dibuat oleh Admin, guna mencegah penyusup/akun tidak sah. |
| Titik/Stasiun QR (QR Station) | Satu layar/titik tampilan QR presensi yang dapat diaktifkan Admin atau Guru; banyak titik dapat aktif bersamaan namun tetap terhubung ke satu sumber data presensi pusat. |
| Proctoring | Pengawasan digital selama ujian/kuis berlangsung (mis. lewat kamera, deteksi pindah tab) untuk menjaga integritas akademik. |
| 2FA (Two-Factor Authentication) | Lapisan keamanan login tambahan berupa kode verifikasi kedua (OTP) di luar password, dipakai wajib untuk akun Admin di Lumora. |
| Fullscreen API / Page Visibility API | API browser yang dipakai Lumora untuk memaksa mode layar penuh dan mendeteksi perpindahan tab selama Mode Kuis Aman berlangsung. |

---

*— Akhir Dokumen —*
