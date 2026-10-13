<?php
/**
 * Daftar rute aplikasi.
 * Format: $router->get/post('pola', 'Controller@method', array('role'));
 */

// ===================== HALAMAN UMUM =====================
$router->get('/', 'HomeController@index');

// Autentikasi
$router->get('login', 'AuthController@showLogin');
$router->post('login', 'AuthController@login');
$router->get('register', 'AuthController@showRegister');
$router->post('register', 'AuthController@register');
$router->get('logout', 'AuthController@logout');

// Cek status tanpa login (umum)
$router->get('cek-status', 'PendaftaranController@cekStatus');
$router->post('cek-status', 'PendaftaranController@cekStatus');

// ===================== WALI MURID =====================
$router->get('dashboard', 'PendaftaranController@dashboard', array('wali_murid'));
$router->get('pendaftaran', 'PendaftaranController@form', array('wali_murid'));
$router->post('pendaftaran', 'PendaftaranController@simpan', array('wali_murid'));
$router->get('pendaftaran/berkas', 'PendaftaranController@berkas', array('wali_murid'));
$router->post('pendaftaran/berkas', 'PendaftaranController@unggahBerkas', array('wali_murid'));
$router->get('pendaftaran/berkas/{id}/hapus', 'PendaftaranController@hapusBerkas', array('wali_murid'));
$router->post('pendaftaran/kirim', 'PendaftaranController@kirim', array('wali_murid'));
$router->get('pendaftaran/bukti', 'PendaftaranController@bukti', array('wali_murid'));
$router->get('pendaftaran/status', 'PendaftaranController@status', array('wali_murid'));

// ===================== ADMIN / PANITIA =====================
$router->get('admin', 'AdminController@dashboard', array('admin'));
$router->get('admin/pendaftar', 'AdminController@pendaftar', array('admin'));
$router->get('admin/pendaftar/{id}', 'AdminController@detail', array('admin'));
$router->post('admin/pendaftar/{id}/verifikasi', 'AdminController@verifikasi', array('admin'));
$router->post('admin/berkas/{id}', 'AdminController@verifikasiBerkas', array('admin'));
$router->get('admin/berkas/{id}/unduh', 'AdminController@unduhBerkas', array('admin'));

// Gelombang (rute statis dulu, baru dinamis)
$router->get('admin/gelombang', 'AdminController@gelombang', array('admin'));
$router->get('admin/gelombang/tambah', 'AdminController@formGelombang', array('admin'));
$router->post('admin/gelombang', 'AdminController@simpanGelombang', array('admin'));
$router->get('admin/gelombang/{id}/edit', 'AdminController@editGelombang', array('admin'));
$router->post('admin/gelombang/{id}', 'AdminController@updateGelombang', array('admin'));
$router->get('admin/gelombang/{id}/hapus', 'AdminController@hapusGelombang', array('admin'));

// Seleksi
$router->get('admin/seleksi', 'AdminController@seleksi', array('admin'));
$router->post('admin/seleksi/{id}', 'AdminController@simpanSeleksi', array('admin'));

// Pengumuman (statis dulu, lalu dinamis)
$router->get('admin/pengumuman', 'AdminController@pengumuman', array('admin'));
$router->get('admin/pengumuman/tambah', 'AdminController@formPengumuman', array('admin'));
$router->post('admin/pengumuman', 'AdminController@simpanPengumuman', array('admin'));
$router->get('admin/pengumuman/{id}/edit', 'AdminController@editPengumuman', array('admin'));
$router->post('admin/pengumuman/{id}', 'AdminController@updatePengumuman', array('admin'));
$router->get('admin/pengumuman/{id}/hapus', 'AdminController@hapusPengumuman', array('admin'));

// Laporan
$router->get('admin/laporan', 'AdminController@laporan', array('admin'));
$router->get('admin/laporan/csv', 'AdminController@exportCsv', array('admin'));

// ===================== KEPALA SEKOLAH =====================
$router->get('pimpinan', 'PimpinanController@dashboard', array('kepsek'));
