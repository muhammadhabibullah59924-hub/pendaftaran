<?php
$title = 'Dashboard';
$statCards = array(
    array('Total Pendaftar', $stats['total'], 'bi-people', 'primary'),
    array('Draft / Belum Kirim', $per_status['draft'], 'bi-pencil-square', 'secondary'),
    array('Menunggu Verifikasi', $per_status['dikirim'], 'bi-hourglass-split', 'warning'),
    array('Berkas Terverifikasi', $per_status['diverifikasi'], 'bi-check2-circle', 'info'),
    array('Diterima', $per_status['diterima'], 'bi-check-circle', 'success'),
    array('Tidak Diterima', $per_status['tidak_diterima'], 'bi-x-circle', 'dark'),
);
?>

<div class="row g-3 mb-4">
    <?php foreach ($statCards as $c): ?>
    <div class="col-6 col-md-4 col-xl-2 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-<?php echo $c[3]; ?> text-white d-flex align-items-center justify-content-center me-3"
                     style="width:48px;height:48px">
                    <i class="bi <?php echo $c[2]; ?> fs-4"></i>
                </div>
                <div>
                    <div class="h4 mb-0"><?php echo (int) $c[1]; ?></div>
                    <small class="text-muted"><?php echo e($c[0]); ?></small>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                <span>Pendaftar Terbaru</span>
                <a href="<?php echo url('admin/pendaftar'); ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($terbaru)): ?>
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr><th>No.</th><th>Nama</th><th>No. Pendaftaran</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php $n = 1; foreach ($terbaru as $p): ?>
                        <tr>
                            <td><?php echo $n++; ?></td>
                            <td class="text-truncate" style="max-width:180px"><?php echo e($p['nama_lengkap']); ?></td>
                            <td><small class="text-muted"><?php echo e($p['no_pendaftaran']); ?></small></td>
                            <td><?php echo status_badge($p['status']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="text-muted p-3 mb-0">Belum ada pendaftar.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Rekap per Gelombang</div>
            <div class="card-body p-0">
                <?php if (!empty($per_gelombang)): ?>
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr><th>Gelombang</th><th class="text-end">Pendaftar</th><th class="text-end">Diterima</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($per_gelombang as $g): ?>
                        <tr>
                            <td>
                                <?php echo e($g['nama']); ?>
                                <div><small class="text-muted"><?php echo e($g['tahun_ajaran']); ?></small></div>
                            </td>
                            <td class="text-end"><?php echo (int) $g['jumlah']; ?></td>
                            <td class="text-end"><?php echo (int) $g['diterima']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="text-muted p-3 mb-0">Belum ada gelombang.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
