<?php
$title = 'Unggah Berkas';
$total = count($jenis_berkas);
$terunggah = count($berkas);
$belumList = is_array($belum) ? $belum : array();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0">
            Nomor: <strong><?php echo e($pendaftaran['no_pendaftaran']); ?></strong>
        </p>
    </div>
    <span class="badge bg-primary fs-6"><?php echo $terunggah; ?>/<?php echo $total; ?> berkas</span>
</div>

<div class="progress mb-4" style="height:8px">
    <div class="progress-bar" style="width:<?php echo ($terunggah / $total) * 100; ?>%"></div>
</div>

<div class="row g-3">
    <?php foreach ($jenis_berkas as $kode => $label): ?>
        <?php
            $sudah = null;
            foreach ($berkas as $b) {
                if ($b['jenis'] === $kode) { $sudah = $b; break; }
            }
        ?>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-0"><i class="bi bi-file-earmark me-1"></i><?php echo e($label); ?></h6>
                        <?php if ($sudah): ?>
                            <span class="badge bg-<?php echo berkas_color($sudah['status']); ?>"><?php echo e($sudah['status']); ?></span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Belum diunggah</span>
                        <?php endif; ?>
                    </div>

                    <?php if ($sudah): ?>
                        <p class="small text-truncate mb-1" title="<?php echo e($sudah['nama_asli']); ?>">
                            <i class="bi bi-file-earmark-check text-success me-1"></i><?php echo e($sudah['nama_asli']); ?>
                        </p>
                        <div class="d-flex gap-2">
                            <form method="post" action="<?php echo url('pendaftaran/berkas'); ?>" enctype="multipart/form-data" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="jenis" value="<?php echo e($kode); ?>">
                                <input type="file" name="berkas" class="form-control form-control-sm d-inline-block w-auto" required>
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-arrow-repeat me-1"></i>Ganti
                                </button>
                            </form>
                            <form method="post" action="<?php echo url('pendaftaran/berkas/' . $sudah['id'] . '/hapus'); ?>" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus berkas ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <form method="post" action="<?php echo url('pendaftaran/berkas'); ?>" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="jenis" value="<?php echo e($kode); ?>">
                            <input type="file" name="berkas" class="form-control form-control-sm mb-2" accept=".jpg,.jpeg,.png,.pdf" required>
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="bi bi-cloud-upload me-1"></i>Unggah
                            </button>
                        </form>
                    <?php endif; ?>

                    <small class="text-muted d-block mt-2 mb-0">JPG / PNG / PDF &middot; maks. 2 MB</small>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php if ($terunggah >= $total): ?>
<div class="alert alert-success mt-4">
    <i class="bi bi-check-circle me-1"></i>Semua berkas wajib sudah diunggah.
    Anda dapat <a href="<?php echo url('pendaftaran/kirim'); ?>">mengirim pendaftaran</a> dari dashboard.
</div>
<?php endif; ?>
