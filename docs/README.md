# Dokumentasi Lumora

Folder ini mencatat kondisi aplikasi yang benar-benar telah diimplementasikan. `PRD_Lumora.md` tetap menjadi sumber kebutuhan produk; ketika implementasi dan PRD berbeda, status aktual dicatat di `FEATURES.md`.

| Dokumen | Isi |
|---|---|
| [README utama](../README.md) | Instalasi singkat, akun demo, dan cara menjalankan aplikasi |
| [PRD](../PRD_Lumora.md) | Kebutuhan dan visi produk |
| [FEATURES](FEATURES.md) | Cakupan fitur, status implementasi, dan pekerjaan lanjutan |
| [ARCHITECTURE](ARCHITECTURE.md) | Stack, modul, model data, realtime, dan tema |
| [ACCESS_CONTROL](ACCESS_CONTROL.md) | Hak akses Admin, Guru, Murid, dan Wali |
| [RUNBOOK](RUNBOOK.md) | Operasional lokal, pengujian, jaringan, dan troubleshooting |
| [DEVELOPMENT](DEVELOPMENT.md) | Keputusan teknis dan prioritas pengembangan |
| [CHANGELOG](CHANGELOG.md) | Ringkasan perubahan yang telah dikerjakan |

## Aturan pemeliharaan dokumentasi

Setiap perubahan fitur harus memperbarui `FEATURES.md` dan `CHANGELOG.md`. Perubahan tabel atau relasi juga memperbarui `ARCHITECTURE.md`; perubahan otorisasi memperbarui `ACCESS_CONTROL.md`; sedangkan perubahan perintah, environment, atau prosedur menjalankan aplikasi memperbarui `RUNBOOK.md` dan README utama.
