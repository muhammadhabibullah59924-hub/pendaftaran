<?php
/** Layout untuk halaman umum (landing, auth) */
?><!DOCTYPE html>
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

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo url('/'); ?>">
            <i class="bi bi-mortarboard-fill me-1"></i><?php echo e(config('app_name')); ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navPub">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navPub">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?php echo url('/'); ?>">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo url('cek-status'); ?>">Cek Status</a></li>
            </ul>
            <ul class="navbar-nav">
                <?php if (Auth::check()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url(Auth::isAdmin() ? 'admin' : (Auth::isKepsek() ? 'pimpinan' : 'dashboard')); ?>">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('logout'); ?>">
                            <i class="bi bi-box-arrow-right me-1"></i>Keluar
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo url('login'); ?>">Login</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-sm btn-outline-light ms-2" href="<?php echo url('register'); ?>">Daftar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-5" style="min-height:60vh">
    <?php require BASE_PATH . '/app/Views/partials/flash.php'; ?>
    <?php echo $content; ?>
</main>

<footer class="bg-white border-top py-3">
    <div class="container text-center small text-muted">
        &copy; <?php echo date('Y'); ?> <?php echo e(config('sekolah')); ?> &middot; Sistem Informasi Penerimaan Siswa Baru
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
