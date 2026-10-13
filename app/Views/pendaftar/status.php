<?php $title = 'Status Pendaftaran'; ?>
<div class="card shadow-sm mb-4">
    <div class="card-body text-center py-4">
        <div class="text-muted small">Status Pendaftaran</div>
        <div class="my-3"><?php echo status_badge($pendaftaran['status']); ?></div>
        <h4 class="fw-bold mb-1"><?php echo e($pendaftaran['nama_lengkap']); ?></h4>
        <div class="text-muted">No. <?php echo e($pendaftaran['no_pendaftaran']); ?></div>
        <?php if ($pendaftaran['catatan_admin']): ?>
        <div class="alert alert-info mt-3 py-2">
            <strong>Catatan panitia:</strong> <?php echo e($pendaftaran['catatan_admin']); ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Riwayat</div>
            <div class="card-body">
                <?php if (!empty($riwayat)): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($riwayat as $r): ?>
                    <li class="list-group-item px-0 border-0">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-arrow-right-circle text-primary me-2 mt-1"></i>
                            <div>
                                <span class="badge bg-<?php echo status_color($r['status_baru']); ?>"><?php echo e(status_label($r['status_baru'])); ?></span>
                                <?php if ($r['status_lama']): ?>
                                <small class="text-muted ms-1">dari <?php echo e(status_label($r['status_lama'])); ?></small>
                                <?php endif; ?>
                                <div><small class="text-muted"><?php echo tanggal_waktu($r['created_at']); ?>
                                <?php if ($r['nama_petugas']): ?> &middot; <?php echo e($r['nama_petugas']); ?><?php endif; ?></small></div>
                                <?php if ($r['catatan']): ?>
                                <small class="fst-italic"><?php echo e($r['catatan']); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p class="text-muted mb-0">Belum ada riwayat.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Berkas (<?php echo count($berkas); ?>)</div>
            <div class="card-body">
                <?php if (!empty($berkas)): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($berkas as $b): ?>
                    <li class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small"><?php echo e(isset($jenis_berkas[$b['jenis']]) ? $jenis_berkas[$b['jenis']] : $b['jenis']); ?></div>
                            <div class="text-truncate text-muted" style="max-width:200px"><?php echo e($b['nama_asli']); ?></div>
                        </div>
                        <span class="badge bg-<?php echo berkas_color($b['status']); ?>"><?php echo e($b['status']); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p class="text-muted mb-0">Belum ada berkas.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<a href="<?php echo url('pendaftaran/bukti'); ?>" class="btn btn-outline-primary">
    <i class="bi bi-printer me-1"></i>Cetak Bukti Pendaftaran
</a>
