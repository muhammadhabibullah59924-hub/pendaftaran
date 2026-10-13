<?php
/** Layout khusus cetak */
?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bukti Pendaftaran &mdash; <?php echo e(config('app_name')); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo asset('css/print.css'); ?>" rel="stylesheet">
</head>
<body>
    <?php echo $content; ?>
    <div class="no-print" style="text-align:center;margin:16px">
        <button type="button" class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak / Simpan PDF
        </button>
        <a class="btn btn-outline-secondary" href="<?php echo url('dashboard'); ?>">Kembali</a>
    </div>
</body>
</html>
