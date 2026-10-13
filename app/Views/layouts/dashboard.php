<?php
/** Layout dashboard (wali murid / admin / kepsek) */

$role = Auth::role();
$roleLabel = array(
    'wali_murid' => 'Wali Murid',
    'admin' => 'Admin / Panitia',
    'kepsek' => 'Kepala Sekolah',
);
$label = isset($roleLabel[$role]) ? $roleLabel[$role] : $role;

// Menu sidebar berdasarkan peran
$menungguAkun = 0;
$menu = array();
if ($role === 'wali_murid') {
    $menu = array(
        array('dashboard', 'Dashboard', 'bi-speedometer2'),
        array('pendaftaran', 'Formulir Pendaftaran', 'bi-pencil-square'),
        array('pendaftaran/berkas', 'Unggah Berkas', 'bi-cloud-upload'),
        array('pendaftaran/status', 'Status Pendaftaran', 'bi-info-circle'),
        array('pendaftaran/bukti', 'Cetak Bukti', 'bi-printer'),
    );
} elseif ($role === 'admin') {
    $menungguAkun = (new User())->countPending();
    $menu = array(
        array('admin', 'Dashboard', 'bi-speedometer2'),
        array('admin/pendaftar', 'Data Pendaftar', 'bi-people'),
        array('admin/pengguna', 'Kelola Pengguna', 'bi-person-gear', $menungguAkun),
        array('admin/seleksi', 'Seleksi', 'bi-funnel'),
        array('admin/gelombang', 'Gelombang', 'bi-calendar2-event'),
        array('admin/pengumuman', 'Pengumuman', 'bi-megaphone'),
        array('admin/laporan', 'Laporan', 'bi-bar-chart-line'),
    );
} elseif ($role === 'kepsek') {
    $menu = array(
        array('pimpinan', 'Dashboard', 'bi-speedometer2'),
    );
}

// Tentukan halaman aktif
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if (BASE_URL !== '' && strpos($uri, BASE_URL) === 0) {
    $uri = trim(substr($uri, strlen(BASE_URL)), '/');
}
$active = $uri;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? e($title) . ' &mdash; ' : ''; ?><?php echo e(config('app_name')); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?php echo asset('css/app.css'); ?>" rel="stylesheet">
</head>
<body class="bg-light">

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-primary text-white flex-shrink-0" id="sidebar">
        <div class="p-3 border-bottom border-light border-opacity-25">
            <div class="fw-bold"><i class="bi bi-mortarboard-fill me-1"></i><?php echo e(config('app_name')); ?></div>
            <small class="text-white-50"><?php echo e($label); ?></small>
        </div>
        <ul class="nav flex-column py-2">
            <?php foreach ($menu as $m): ?>
                <?php
                    $isActive = ($uri === $m[0]) ? true : (strlen($m[0]) > 3 && strpos($uri, $m[0]) === 0);
                ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo $isActive ? 'active' : ''; ?>" href="<?php echo url($m[0]); ?>">
                        <i class="bi <?php echo $m[2]; ?> me-2"></i><?php echo $m[1]; ?>
                        <?php if (isset($m[3]) && (int) $m[3] > 0): ?>
                        <span class="badge bg-warning text-dark float-end mt-1"><?php echo (int) $m[3]; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="p-3 mt-auto border-top border-light border-opacity-25">
            <a href="<?php echo url('/'); ?>" class="nav-link text-white-50 px-0"><i class="bi bi-house me-1"></i>Lihat Situs</a>
        </div>
    </div>

    <!-- Konten -->
    <div class="flex-grow-1 d-flex flex-column" id="page-content-wrapper">
        <nav class="navbar navbar-light bg-white border-bottom shadow-sm">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1 fw-semibold">
                    <?php echo isset($title) ? e($title) : 'Dashboard'; ?>
                </span>
                <div class="dropdown">
                    <a class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" href="#">
                        <i class="bi bi-person-circle fs-4 me-2 text-primary"></i>
                        <span class="d-none d-md-inline"><?php echo e(Auth::user()['nama']); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header"><?php echo e($label); ?></h6></li>
                        <li><a class="dropdown-item" href="<?php echo url('logout'); ?>"><i class="bi bi-box-arrow-right me-1"></i>Keluar</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="flex-grow-1 p-4">
            <?php require BASE_PATH . '/app/Views/partials/flash.php'; ?>
            <?php echo $content; ?>
        </main>

        <footer class="bg-white border-top py-2">
            <div class="container-fluid small text-muted text-center">
                &copy; <?php echo date('Y'); ?> <?php echo e(config('sekolah')); ?>
            </div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
