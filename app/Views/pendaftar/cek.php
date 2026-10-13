<?php $title = 'Cek Status Pendaftaran'; ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-search me-1"></i>Cek Status Pendaftaran
            </div>
            <div class="card-body">
                <p class="text-muted small">Masukkan nomor pendaftaran Anda (contoh: <code>PSB-<?php echo date('Y'); ?>-0001</code>).</p>
                <form method="post" action="<?php echo url('cek-status'); ?>" class="row g-2">
                    <?php echo csrf_field(); ?>
                    <div class="col-md-8">
                        <input type="text" name="no_pendaftaran" class="form-control form-control-lg"
                               placeholder="Nomor Pendaftaran" value="<?php echo e($nomor); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-search me-1"></i>Cek
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($hasil): ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <div class="text-muted small">Status Pendaftaran</div>
                <div class="my-3"><?php echo status_badge($hasil['status']); ?></div>
                <h5 class="fw-bold mb-1"><?php echo e($hasil['nama_lengkap']); ?></h5>
                <div class="text-muted">No. <?php echo e($hasil['no_pendaftaran']); ?></div>
                <?php if ($hasil['catatan_admin']): ?>
                <div class="alert alert-info mt-3 py-2">
                    <strong>Catatan panitia:</strong> <?php echo e($hasil['catatan_admin']); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php elseif (method() === 'POST'): ?>
        <div class="alert alert-danger">Nomor pendaftaran tidak ditemukan. Pastikan nomor yang Anda masukkan benar.</div>
        <?php endif; ?>
    </div>
</div>
