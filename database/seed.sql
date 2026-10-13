-- ============================================================
-- Data Awal (seed) — PSB-MI Ihyaul Islam
-- ============================================================

-- Akun pengguna
-- Password admin: admin123 | kepsek: kepsek123 | wali: wali123
INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `aktif`, `created_at`, `updated_at`) VALUES
(1, 'Panitia PSB', 'admin@mihiyaulislam.sch.id',
 '$2y$10$2XKb5Y0Wg7eKuvmC59./2uHNfHR1AwTQDpiGDARH2Iyim9CwCSvSe', 'admin', 1, NOW(), NULL),
(2, 'Kepala Sekolah', 'kepsek@mihiyaulislam.sch.id',
 '$2y$10$QUaqHgvS0rXbQ/F23yY5/uU25FcZtXbACnMtnCZG85TS/axKhyRKm', 'kepsek', 1, NOW(), NULL),
(3, 'Ahmad Orang Tua', 'wali.contoh@email.com',
 '$2y$10$FjpT8le/CmKt8YtOLegDD.SZLNsRDeWOp2paF2xVQfo6MVkwLQSoq', 'wali_murid', 1, NOW(), NULL);

-- Gelombang pendaftaran
INSERT INTO `gelombang` (`id`, `nama`, `tahun_ajaran`, `jenis`, `tanggal_mulai`, `tanggal_selesai`, `kuota`, `status`, `deskripsi`, `created_at`) VALUES
(1, 'Gelombang 1', '2026/2027', 'Reguler', '2026-09-01', '2026-12-31', 120, 'buka',
 'Pendaftaran siswa baru kelas 1 MI tahun ajaran 2026/2027.', NOW());

-- Pengumuman contoh
INSERT INTO `pengumuman` (`gelombang_id`, `judul`, `isi`, `tanggal`, `status`, `created_at`) VALUES
(1, 'Penerimaan Siswa Baru Telah Dibuka',
 'Pendaftaran siswa baru kelas 1 MI Ihyaul Islam tahun ajaran 2026/2027 telah dibuka. Silakan daftarkan akun dan lengkapi berkas persyaratan.',
 '2026-09-01', 'aktif', NOW());

-- Contoh pendaftaran (milik user id 3)
INSERT INTO `pendaftaran` (
  `user_id`, `gelombang_id`, `no_pendaftaran`,
  `nama_lengkap`, `nama_panggilan`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`,
  `agama`, `kewarganegaraan`, `anak_ke`, `jumlah_saudara`,
  `nama_ayah`, `pekerjaan_ayah`, `hp_ayah`,
  `nama_ibu`, `pekerjaan_ibu`, `hp_ibu`,
  `asal_sekolah`, `tahun_lulus`,
  `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `kota`, `provinsi`, `kode_pos`,
  `status`, `tanggal_daftar`, `created_at`
) VALUES (
  3, 1, 'PSB-2026-0001',
  'Ahmad Fauzan', 'Fauzan', 'L', 'Jakarta', '2019-05-12',
  'Islam', 'Indonesia', 1, 1,
  'Budi Santoso', 'Karyawan', '081234567890',
  'Siti Aminah', 'Ibu Rumah Tangga', '081234567891',
  'TK Aisyiyah', '2025',
  'Jl. Melati No. 10', '05/03', 'Sukajadi', 'Sukajadi', 'Kota Contoh', 'Banten', '42111',
  'dikirim', NOW(), NOW()
);

-- Riwayat contoh
INSERT INTO `riwayat_status` (`pendaftaran_id`, `status_lama`, `status_baru`, `oleh_user_id`, `catatan`, `created_at`) VALUES
(1, 'draft', 'dikirim', 3, 'Pendaftaran dikirim oleh wali murid.', NOW());
