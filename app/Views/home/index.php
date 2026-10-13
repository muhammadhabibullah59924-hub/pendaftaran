<?php $title = 'Penerimaan Siswa Baru'; ?>
<div class="bg-primary text-white rounded-4 py-5 px-4 mb-4 shadow-sm">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold">Penerimaan Siswa Baru</h1>
            <p class="lead mb-0">
                <?php echo e(config('sekolah')); ?> &mdash; <?php echo e(config('jenjang')); ?><br>
                Pendaftaran online yang mudah, cepat, dan tertata rapi.
            </p>
            <div class="mt-4">
                <a href="<?php echo url('register'); ?>" class="btn btn-light btn-lg me-2">
                    <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
                </a>
                <a href="<?php echo url('cek-status'); ?>" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-search me-1"></i>Cek Status
                </a>
            </div>
        </div>
        <div class="col-lg-4 text-center d-none d-lg-block">
            <i class="bi bi-mortarboard" style="font-size:120px;opacity:.5"></i>
        </div>
    </div>
</div>

<?php if (!empty($gelombang)): ?>
<div class="row mb-4">
    <?php foreach ($gelombang as $g): ?>
    <div class="col-md-6 mb-3">
        <div class="card border-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <h5 class="card-title mb-1"><?php echo e($g['nama']); ?></h5>
                    <span class="badge bg-success">Dibuka</span>
                </div>
                <p class="text-muted mb-2">
                    <?php echo e($g['jenis']); ?> &middot; <?php echo e($g['tahun_ajaran']); ?>
                </p>
                <p class="mb-1"><i class="bi bi-calendar2-event me-1"></i>
                    <?php echo tanggal($g['tanggal_mulai']); ?> &ndash; <?php echo tanggal($g['tanggal_selesai']); ?>
                </p>
                <?php if ($g['kuota'] > 0): ?>
                <p class="mb-0"><i class="bi bi-people me-1"></i>Kuota: <?php echo (int) $g['kuota']; ?> siswa</p>
                <?php endif; ?>
                <?php if ($g['deskripsi']): ?>
                <hr class="my-2"><small><?php echo e($g['deskripsi']); ?></small>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Alur pendaftaran -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-white fw-bold">Alur Pendaftaran</div>
    <div class="card-body">
        <div class="row text-center g-3">
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mx-auto mb-2" style="width:56px;height:56px">
                    <span class="fs-4">1</span>
                </div>
                <div class="fw-semibold">Daftar Akun</div>
                <small class="text-muted">Buat akun wali murid dengan email</small>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mx-auto mb-2" style="width:56px;height:56px">
                    <span class="fs-4">2</span>
                </div>
                <div class="fw-semibold">Isi Formulir</div>
                <small class="text-muted">Lengkapi data siswa &amp; orang tua</small>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mx-auto mb-2" style="width:56px;height:56px">
                    <span class="fs-4">3</span>
                </div>
                <div class="fw-semibold">Unggah Berkas</div>
                <small class="text-muted">Scan dokumen persyaratan</small>
            </div>
            <div class="col-md-3">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mx-auto mb-2" style="width:56px;height:56px">
                    <span class="fs-4">4</span>
                </div>
                <div class="fw-semibold">Cek Hasil</div>
                <small class="text-muted">Pantau status &amp; pengumuman</small>
            </div>
        </div>
    </div>
</div>

<!-- Syarat berkas -->
<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Syarat Berkas</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php foreach ($jenis_berkas as $label): ?>
                    <li class="list-group-item px-0 border-0">
                        <i class="bi bi-file-earmark-check text-success me-2"></i><?php echo e($label); ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <small class="text-muted">Format: JPG / PNG / PDF &middot; Maksimal 2 MB per berkas</small>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Pengumuman</div>
            <div class="card-body">
                <?php if (!empty($pengumuman)): ?>
                    <?php foreach ($pengumuman as $p): ?>
                    <div class="border-start border-3 border-primary ps-3 mb-3">
                        <div class="fw-semibold"><?php echo e($p['judul']); ?></div>
                        <small class="text-muted"><?php echo tanggal($p['tanggal']); ?></small>
                        <p class="small mb-0 mt-1"><?php echo e($p['isi']); ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted mb-0">Belum ada pengumuman.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
