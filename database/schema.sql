-- ============================================================
-- Skema Database — Sistem PSB-MI Ihyaul Islam
-- Jalankan via database/install.php atau phpMyAdmin
-- ============================================================

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('wali_murid','admin','kepsek') NOT NULL DEFAULT 'wali_murid',
  `aktif` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `gelombang` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `tahun_ajaran` VARCHAR(20) NOT NULL,
  `jenis` VARCHAR(50) NOT NULL,
  `tanggal_mulai` DATE NOT NULL,
  `tanggal_selesai` DATE NOT NULL,
  `kuota` INT NOT NULL DEFAULT 0,
  `status` ENUM('buka','tutup') NOT NULL DEFAULT 'tutup',
  `deskripsi` TEXT NULL,
  `created_at` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pendaftaran` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `gelombang_id` INT NULL,
  `no_pendaftaran` VARCHAR(30) NOT NULL UNIQUE,

  `nama_lengkap` VARCHAR(100) NOT NULL,
  `nama_panggilan` VARCHAR(50) NULL,
  `jenis_kelamin` ENUM('L','P') NOT NULL,
  `tempat_lahir` VARCHAR(100) NOT NULL,
  `tanggal_lahir` DATE NOT NULL,
  `agama` VARCHAR(30) NULL,
  `kewarganegaraan` VARCHAR(30) NULL DEFAULT 'Indonesia',
  `anak_ke` INT NULL,
  `jumlah_saudara` INT NULL,
  `nisn` VARCHAR(20) NULL,

  `nama_ayah` VARCHAR(100) NULL,
  `pendidikan_ayah` VARCHAR(50) NULL,
  `pekerjaan_ayah` VARCHAR(100) NULL,
  `hp_ayah` VARCHAR(20) NULL,
  `nama_ibu` VARCHAR(100) NULL,
  `pendidikan_ibu` VARCHAR(50) NULL,
  `pekerjaan_ibu` VARCHAR(100) NULL,
  `hp_ibu` VARCHAR(20) NULL,
  `nama_wali` VARCHAR(100) NULL,
  `hubungan_wali` VARCHAR(50) NULL,
  `hp_wali` VARCHAR(20) NULL,

  `asal_sekolah` VARCHAR(100) NULL,
  `npsn_asal` VARCHAR(20) NULL,
  `tahun_lulus` VARCHAR(10) NULL,

  `alamat` VARCHAR(255) NULL,
  `rt_rw` VARCHAR(20) NULL,
  `kelurahan` VARCHAR(100) NULL,
  `kecamatan` VARCHAR(100) NULL,
  `kota` VARCHAR(100) NULL,
  `provinsi` VARCHAR(100) NULL,
  `kode_pos` VARCHAR(10) NULL,

  `status` ENUM('draft','dikirim','diverifikasi','ditolak','diterima','tidak_diterima') NOT NULL DEFAULT 'draft',
  `catatan_admin` TEXT NULL,
  `tanggal_daftar` DATETIME NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,

  INDEX `idx_user` (`user_id`),
  INDEX `idx_gelombang` (`gelombang_id`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `berkas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `pendaftaran_id` INT NOT NULL,
  `jenis` VARCHAR(30) NOT NULL,
  `nama_asli` VARCHAR(255) NOT NULL,
  `path` VARCHAR(255) NOT NULL,
  `status` ENUM('menunggu','valid','tidak_valid') NOT NULL DEFAULT 'menunggu',
  `catatan` VARCHAR(255) NULL,
  `uploaded_at` DATETIME NOT NULL,

  INDEX `idx_pendaftaran` (`pendaftaran_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `riwayat_status` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `pendaftaran_id` INT NOT NULL,
  `status_lama` VARCHAR(30) NULL,
  `status_baru` VARCHAR(30) NOT NULL,
  `oleh_user_id` INT NULL,
  `catatan` TEXT NULL,
  `created_at` DATETIME NOT NULL,

  INDEX `idx_pendaftaran` (`pendaftaran_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pengumuman` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `gelombang_id` INT NULL,
  `judul` VARCHAR(150) NOT NULL,
  `isi` TEXT NOT NULL,
  `tanggal` DATE NOT NULL,
  `status` ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
