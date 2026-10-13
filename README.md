# 📚 Sistem Informasi PSB — MI Ihyaul Islam

**Sistem Informasi Penerimaan Siswa Baru (PSB) berbasis web** untuk jenjang **Madrasah Ibtidaiyah (setara SD)**.

Sistem ini dirancang untuk menggantikan **metode pendaftaran manual** yang berpotensi menimbulkan kendala
(data mudah hilang/duplikasi, antre panjang, verifikasi lambat), agar memudahkan **wali murid** dan
**pihak sekolah** dalam mengelola data siswa baru.

---

## ✨ Fitur

### Wali Murid
- Daftar akun (email + password)
- Formulir pendaftaran online (data siswa, orang tua/wali, asal sekolah, alamat)
- Unggah berkas persyaratan (akta kelahiran, KK, KTP ortu, foto, ijazah/skl TK) — JPG/PNG/PDF, maks 2 MB
- **Nomor pendaftaran otomatis** (format `PSB-2026-0001`)
- Kirim pendaftaran, pantau status & riwayat
- Cetak **bukti pendaftaran** (siap print / simpan PDF)

### Admin / Panitia
- Dashboard statistik & rekap per gelombang
- Data pendaftar + filter (cari, status, gelombang)
- Detail pendaftar + verifikasi **per-berkas** (valid / tidak valid)
- Verifikasi pendaftaran (verifikasi / tolak + catatan)
- Manajemen **gelombang/jalur** pendaftaran (buka/tutup, kuota, periode)
- Penetapan hasil **seleksi** (diterima / tidak diterima)
- Kelola **pengumuman**
- **Laporan & statistik** + export **CSV** (Excel)

### Kepala Sekolah
- Dashboard **read-only**: statistik, grafik per bulan, rekap, pengumuman

---

## ⚙️ Kebutuhan
| Komponen | Versi |
|---|---|
| PHP | 5.6+ (teruji di **PHP 5.6.36** XAMPP) |
| MySQL / MariaDB | 10.x (teruji di **MariaDB 10.1.32** XAMPP) |
| Web server | Apache (XAMPP) |
| Browser | Chrome / Firefox / Edge terbaru |

> **Tanpa Composer** — aplikasi ini PHP Native (framework kecil khusus proyek ini).

---

## 🚀 Cara Instalasi

1. **Pastikan XAMPP berjalan** — nyalakan **Apache** dan **MySQL** di XAMPP Control Panel.

2. **Salin folder** proyek ini ke `C:\xampp\htdocs\` sehingga menjadi:
   ```
   C:\xampp\htdocs\pendaftaran
   ```
   *(sudah berada di lokasi ini jika Anda sedang membaca file ini di folder htdocs)*

3. **Buka installer database** di browser:
   ```
   http://localhost/pendaftaran/database/install.php
   ```
   Klik **[Install Database]** — ini akan membuat database `psb_mi`, 6 tabel, dan data awal.

4. **Selesai!** Buka halaman utama:
   ```
   http://localhost/pendaftaran
   ```

---

## 🔑 Akun Login

| Peran | Email | Password |
|---|---|---|
| **Admin / Panitia** | `admin@mihiyaulislam.sch.id` | `admin123` |
| **Kepala Sekolah** | `kepsek@mihiyaulislam.sch.id` | `kepsek123` |
| **Contoh Wali Murid** | `wali.contoh@email.com` | `wali123` |

> ⚠️ **Segera ganti password** akun admin & kepsek setelah login pertama (untuk produksi).
> Pendaftar umum mendaftar sendiri lewat tombol **Daftar**.

---

## 🧩 Struktur Proyek

```
pendaftaran/
├── index.php              ← front controller (titik masuk)
├── .htaccess              ← rewrite URL ke index.php
├── app/
│   ├── Core/              ← Router, Database (PDO), Auth, Csrf, Validator, Upload, View, helpers
│   ├── Controllers/       ← Home, Auth, Pendaftaran, Admin, Pimpinan
│   ├── Models/            ← User, Gelombang, Pendaftaran, Berkas, Riwayat, Pengumuman
│   └── Views/             ← layouts/ partials/ home/ auth/ pendaftar/ admin/ pimpinan/
├── config/
│   ├── config.php         ← nama sekolah, kontak, zona waktu
│   └── database.php       ← koneksi DB (sesuaikan jika perlu)
├── database/
│   ├── schema.sql         ← struktur tabel
│   ├── seed.sql           ← data awal (akun, gelombang, contoh pendaftar)
│   └── install.php        ← instalator (jalankan via browser)
├── public/assets/css/     ← app.css, print.css
└── storage/uploads/       ← berkas pendaftar (diakses via controller, .htaccess blokir akses langsung)
```

---

## 🔐 Keamanan yang Diterapkan
- **PDO Prepared Statements** di semua query → aman dari SQL Injection
- **password_hash / password_verify** (bcrypt) → password tidak disimpan plain text
- **CSRF token** pada setiap form
- **Validasi input** (Validator) & cek role per halaman (middleware otomatis di Router)
- **Unggah aman**: whitelist ekstensi (JPG/PNG/PDF), batas 2 MB, deteksi MIME asli, nama file acak
- Berkas tersimpan di `storage/uploads` yang **diblokir .htaccess**, hanya bisa diunduh lewat controller admin
- `session_regenerate_id()` saat login/logout → anti session fixation

---

## 🛠️ Peta Status Pendaftaran
```
draft → dikirim → diverifikasi / ditolak → diterima / tidak_diterima
```
Setiap perubahan dicatat di tabel **`riwayat_status`** (audit trail).

---

## ❓ Pemecahan Masalah

| Masalah | Solusi |
|---|---|
| **404 saat buka halaman** | Pastikan `mod_rewrite` aktif di XAMPP. Cek `apache/conf/httpd.conf` → `LoadModule rewrite_module` (hapus tanda `#`). |
| **Koneksi database gagal** | Pastikan **MySQL sudah Start**. Cek `config/database.php`. |
| **Halaman utama kosong / error CSS** | Jalankan ulang install.php, pastikan struktur folder lengkap. |
| **Upload gagal** | Cek folder `storage/uploads` bisa ditulis. Ukuran maks 2 MB. |
| **Database kosong / belum ada** | Buka `http://localhost/pendaftaran/database/install.php` lagi. |

---

## 📌 Cara Ganti Nama Sekolah
Edit **`config/config.php`**:
```php
'app_name' => 'PSB-NamaSekolah Anda',
'sekolah'  => 'NamaSekolah Anda',
```
Seluruh tampilan otomatis berubah.

---

**Dibuat untuk keperluan magang** — Sistem Informasi PSB berbasis web untuk mempermudah wali murid & pihak sekolah dalam mengelola data siswa baru.
