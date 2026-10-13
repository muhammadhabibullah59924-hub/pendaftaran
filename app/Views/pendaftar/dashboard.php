<?php $title = 'Dashboard'; ?>
<div class="d-flex justify-content-between align-items-start flex-wrap mb-4">
    <div>
        <h4 class="mb-1">Selamat datang, <?php echo e(Auth::user()['nama']); ?></h4>
        <p class="text-muted mb-0">Pantau proses pendaftaran siswa baru Anda.</p>
    </div>
    <?php if (!$pendaftaran): ?>
        <a href="<?php echo url('pendaftaran'); ?>" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i>Mulai Pendaftaran
        </a>
    <?php endif; ?>
</div>

<?php if (!$pendaftaran): ?>
<div class="card shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bi bi-clipboard2-plus display-1 text-primary"></i>
        <h5 class="mt-3">Anda belum mendaftar</h5>
        <p class="text-muted">Silakan isi formulir pendaftaran untuk memulai.</p>
        <a href="<?php echo url('pendaftaran'); ?>" class="btn btn-primary btn-lg">
            <i class="bi bi-pencil-square me-1"></i>Isi Formulir Sekarang
        </a>
    </div>
</div>
<?php else: ?>

<!-- Kartu status -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="text-muted small">Nomor Pendaftaran</div>
                <h3 class="fw-bold mb-2"><?php echo e($pendaftaran['no_pendaftaran']); ?></h3>
                <div class="mb-3"><?php echo status_badge($pendaftaran['status']); ?></div>
                <?php if ($pendaftaran['catatan_admin']): ?>
                <div class="alert alert-info py-2 mb-0">
                    <strong>Catatan panitia:</strong> <?php echo e($pendaftaran['catatan_admin']); ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-md-4 text-md-end">
                <?php if ($pendaftaran['tanggal_daftar']): ?>
                <div class="small text-muted">Tanggal kirim</div>
                <div class="fw-semibold"><?php echo tanggal_waktu($pendaftaran['tanggal_daftar']); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Progres -->
<?php
    $totalBerkas = count($jenis_berkas);
    $terunggah = count($berkas);
    $semuaUpload = ($terunggah >= $totalBerkas);
    $langkah = 1;
    if ($pendaftaran) $langkah = 2;
    if ($semuaUpload) $langkah = 3;
    if ($pendaftaran['status'] !== 'draft') $langkah = 4;
    if (in_array($pendaftaran['status'], array('diverifikasi', 'diterima', 'tidak_diterima'))) $langkah = 5;

    $labels = array('Isi Data', 'Unggah Berkas', 'Kirim', 'Verifikasi', 'Hasil');
?>
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white fw-bold">Progres Pendaftaran</div>
    <div class="card-body">
        <div class="progress" style="height:10px">
            <div class="progress-bar" role="progressbar" style="width:<?php echo ($langkah / 5) * 100; ?>%"></div>
        </div>
        <div class="row text-center mt-3">
            <?php for ($i = 1; $i <= 5; $i++): ?>
            <div class="col">
                <i class="bi <?php echo $i < $langkah ? 'check-circle-fill text-success' : ($i === $langkah ? 'circle-fill text-primary' : 'circle text-muted'); ?> fs-4"></i>
                <div class="small <?php echo $i <= $langkah ? 'fw-semibold' : 'text-muted'; ?>"><?php echo $labels[$i - 1]; ?></div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<!-- Aksi -->
<div class="row mb-4">
    <?php if ($pendaftaran['status'] === 'draft'): ?>
    <div class="col-md-4 mb-3">
        <a href="<?php echo url('pendaftaran'); ?>" class="btn btn-outline-primary w-100 h-100 py-3">
            <i class="bi bi-pencil-square fs-5 d-block mb-1"></i>
            <span class="fw-semibold">Isi / Ubah Data</span>
        </a>
    </div>
    <div class="col-md-4 mb-3">
        <a href="<?php echo url('pendaftaran/berkas'); ?>" class="btn btn-outline-primary w-100 h-100 py-3">
            <i class="bi bi-cloud-upload fs-5 d-block mb-1"></i>
            <span class="fw-semibold">Unggah Berkas (<?php echo $terunggah; ?>/<?php echo $totalBerkas; ?>)</span>
        </a>
    </div>
    <div class="col-md-4 mb-3">
        <form method="post" action="<?php echo url('pendaftaran/kirim'); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-success w-100 h-100 py-3" <?php echo $semuaUpload ? '' : 'disabled'; ?>>
                <i class="bi bi-send fs-5 d-block mb-1"></i>
                <span class="fw-semibold">Kirim Pendaftaran</span>
            </button>
            <?php if (!$semuaUpload): ?>
            <small class="text-muted d-block text-center mt-1">Lengkapi semua berkas dulu</small>
            <?php endif; ?>
        </form>
    </div>
    <?php else: ?>
    <div class="col-md-4 mb-3">
        <a href="<?php echo url('pendaftaran/bukti'); ?>" class="btn btn-primary w-100 h-100 py-3">
            <i class="bi bi-printer fs-5 d-block mb-1"></i>
            <span class="fw-semibold">Cetak Bukti Pendaftaran</span>
        </a>
    </div>
    <div class="col-md-4 mb-3">
        <a href="<?php echo url('pendaftaran/status'); ?>" class="btn btn-outline-primary w-100 h-100 py-3">
            <i class="bi bi-info-circle fs-5 d-block mb-1"></i>
            <span class="fw-semibold">Detail Status</span>
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Berkas -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
        <span>Berkas Persyaratan</span>
        <?php if ($pendaftaran['status'] === 'draft'): ?>
        <a href="<?php echo url('pendaftaran/berkas'); ?>" class="btn btn-sm btn-primary">Kelola Berkas</a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if (!empty($berkas)): ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr><th>Jenis</th><th>Nama Berkas</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($berkas as $b): ?>
                    <tr>
                        <td><?php echo e(isset($jenis_berkas[$b['jenis']]) ? $jenis_berkas[$b['jenis']] : $b['jenis']); ?></td>
                        <td class="text-truncate" style="max-width:250px"><?php echo e($b['nama_asli']); ?></td>
                        <td><span class="badge bg-<?php echo berkas_color($b['status']); ?>"><?php echo e($b['status']); ?></span></td>
                        <td class="text-end">
                            <a href="<?php echo url('admin/berkas/' . $b['id'] . '/unduh'); ?>" class="btn btn-sm btn-light" title="Unduh">
                                <i class="bi bi-download"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p class="text-muted mb-0">Belum ada berkas diunggah.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Riwayat -->
<div class="card shadow-sm">
    <div class="card-header bg-white fw-bold">Riwayat Status</div>
    <div class="card-body">
        <?php if (!empty($riwayat)): ?>
        <ul class="list-group list-group-flush">
            <?php foreach ($riwayat as $r): ?>
            <li class="list-group-item px-0 border-0 d-flex">
                <i class="bi bi-arrow-right-circle text-primary me-3 mt-1"></i>
                <div>
                    <div>
                        <span class="badge bg-<?php echo status_color($r['status_baru']); ?> me-1">
                            <?php echo e(status_label($r['status_baru'])); ?>
                        </span>
                        <?php if ($r['status_lama']): ?>
                        <small class="text-muted">dari <?php echo e(status_label($r['status_lama'])); ?></small>
                        <?php endif; ?>
                    </div>
                    <small class="text-muted">
                        <?php echo tanggal_waktu($r['created_at']); ?>
                        <?php if ($r['nama_petugas']): ?> &middot; <?php echo e($r['nama_petugas']); ?><?php endif; ?>
                    </small>
                    <?php if ($r['catatan']): ?>
                    <div class="small fst-italic"><?php echo e($r['catatan']); ?></div>
                    <?php endif; ?>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p class="text-muted mb-0">Belum ada riwayat.</p>
        <?php endif; ?>
    </div>
</div>

<?php endif; ?>
