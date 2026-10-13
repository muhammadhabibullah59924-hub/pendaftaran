<?php
$title = 'Detail Pendaftar';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo url('admin/pendaftar'); ?>">Data Pendaftar</a></li>
        <li class="breadcrumb-item active"><?php echo e($pendaftaran['no_pendaftaran']); ?></li>
    </ol>
</nav>

<div class="row">
    <!-- Data -->
    <div class="col-lg-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold mb-0">Data Siswa</span>
                <?php echo status_badge($pendaftaran['status']); ?>
            </div>
            <div class="card-body">
                <div class="text-muted small">Nomor Pendaftaran</div>
                <h5 class="fw-bold"><?php echo e($pendaftaran['no_pendaftaran']); ?></h5>
                <?php if ($gelombang): ?>
                <div class="small text-muted mb-3">
                    <?php echo e($gelombang['nama']); ?> &middot; <?php echo e($gelombang['tahun_ajaran']); ?> &middot; <?php echo e($gelombang['jenis']); ?>
                </div>
                <?php endif; ?>

                <h6 class="border-bottom pb-1 mt-3">Siswa</h6>
                <table class="table table-sm table-borderless">
                    <tr><td class="text-muted" style="width:150px">Nama Lengkap</td><td class="fw-semibold">: <?php echo e($pendaftaran['nama_lengkap']); ?></td></tr>
                    <tr><td class="text-muted">Nama Panggilan</td><td>: <?php echo e($pendaftaran['nama_panggilan'] ?: '-'); ?></td></tr>
                    <tr><td class="text-muted">Jenis Kelamin</td><td>: <?php echo e($pendaftaran['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'); ?></td></tr>
                    <tr><td class="text-muted">Tempat, Tgl Lahir</td><td>: <?php echo e($pendaftaran['tempat_lahir']); ?>, <?php echo tanggal($pendaftaran['tanggal_lahir']); ?></td></tr>
                    <tr><td class="text-muted">Agama</td><td>: <?php echo e($pendaftaran['agama']); ?></td></tr>
                    <tr><td class="text-muted">Anak Ke- / Saudara</td><td>: <?php echo (int) $pendaftaran['anak_ke']; ?> / <?php echo (int) $pendaftaran['jumlah_saudara']; ?></td></tr>
                    <tr><td class="text-muted">NISN</td><td>: <?php echo e($pendaftaran['nisn'] ?: '-'); ?></td></tr>
                </table>

                <h6 class="border-bottom pb-1 mt-3">Orang Tua</h6>
                <table class="table table-sm table-borderless">
                    <tr><td class="text-muted" style="width:150px">Ayah</td><td>: <?php echo e($pendaftaran['nama_ayah']); ?> (<?php echo e($pendaftaran['pekerjaan_ayah']); ?>, <?php echo e($pendaftaran['pendidikan_ayah'] ?: '-'); ?>) &middot; <?php echo e($pendaftaran['hp_ayah']); ?></td></tr>
                    <tr><td class="text-muted">Ibu</td><td>: <?php echo e($pendaftaran['nama_ibu']); ?> (<?php echo e($pendaftaran['pekerjaan_ibu']); ?>, <?php echo e($pendaftaran['pendidikan_ibu'] ?: '-'); ?>) &middot; <?php echo e($pendaftaran['hp_ibu']); ?></td></tr>
                    <?php if ($pendaftaran['nama_wali']): ?>
                    <tr><td class="text-muted">Wali</td><td>: <?php echo e($pendaftaran['nama_wali']); ?> (<?php echo e($pendaftaran['hubungan_wali']); ?>) &middot; <?php echo e($pendaftaran['hp_wali']); ?></td></tr>
                    <?php endif; ?>
                </table>

                <h6 class="border-bottom pb-1 mt-3">Asal Sekolah &amp; Alamat</h6>
                <table class="table table-sm table-borderless">
                    <tr><td class="text-muted" style="width:150px">Asal TK/RA</td><td>: <?php echo e($pendaftaran['asal_sekolah']); ?> <?php if ($pendaftaran['npsn_asal']): ?><small class="text-muted">(NPSN <?php echo e($pendaftaran['npsn_asal']); ?>)</small><?php endif; ?></td></tr>
                    <tr><td class="text-muted">Tahun Lulus</td><td>: <?php echo e($pendaftaran['tahun_lulus'] ?: '-'); ?></td></tr>
                    <tr><td class="text-muted">Alamat</td><td>: <?php echo e($pendaftaran['alamat']); ?>, RT/RW <?php echo e($pendaftaran['rt_rw'] ?: '-'); ?>, <?php echo e($pendaftaran['kelurahan']); ?>, <?php echo e($pendaftaran['kecamatan']); ?>, <?php echo e($pendaftaran['kota']); ?>, <?php echo e($pendaftaran['provinsi']); ?> <?php echo e($pendaftaran['kode_pos']); ?></td></tr>
                </table>

                <?php if ($pendaftaran['status'] === 'draft'): ?>
                <div class="alert alert-secondary py-2 small mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    Data ini masih <b>draft</b> — wali murid sedang mengisi formulir dan belum menekan tombol <b>Kirim Pendaftaran</b>.
                    Nama &amp; isi formulir bisa masih berubah, dan berkas kemungkinan belum lengkap.
                </div>
                <?php endif; ?>

                <?php if ($pendaftaran['tanggal_daftar']): ?>
                <div class="small text-muted mt-2">Tanggal kirim: <?php echo tanggal_waktu($pendaftaran['tanggal_daftar']); ?></div>
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
                    <li class="list-group-item px-0 border-0">
                        <span class="badge bg-<?php echo status_color($r['status_baru']); ?>"><?php echo e(status_label($r['status_baru'])); ?></span>
                        <?php if ($r['status_lama']): ?><small class="text-muted ms-1">dari <?php echo e(status_label($r['status_lama'])); ?></small><?php endif; ?>
                        <div><small class="text-muted"><?php echo tanggal_waktu($r['created_at']); ?> &middot; <?php echo e($r['nama_petugas'] ?: '-'); ?></small></div>
                        <?php if ($r['catatan']): ?><small class="fst-italic"><?php echo e($r['catatan']); ?></small><?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p class="text-muted mb-0">Belum ada riwayat.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Berkas & Aksi -->
    <div class="col-lg-5">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Berkas Persyaratan</div>
            <div class="card-body">
                <?php if (!empty($berkas)): ?>
                <div class="list-group">
                    <?php foreach ($berkas as $b): ?>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold small"><?php echo e(isset($jenis_berkas[$b['jenis']]) ? $jenis_berkas[$b['jenis']] : $b['jenis']); ?></div>
                                <div class="text-truncate small text-muted" style="max-width:180px"><?php echo e($b['nama_asli']); ?></div>
                                <div><span class="badge bg-<?php echo berkas_color($b['status']); ?>"><?php echo e($b['status']); ?></span></div>
                            </div>
                            <a href="<?php echo url('admin/berkas/' . $b['id'] . '/unduh'); ?>" class="btn btn-sm btn-light" title="Unduh">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                        <!-- Verifikasi berkas -->
                        <form method="post" action="<?php echo url('admin/berkas/' . $b['id']); ?>" class="mt-2">
                            <?php echo csrf_field(); ?>
                            <div class="input-group input-group-sm">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="menunggu" <?php echo $b['status'] === 'menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                                    <option value="valid" <?php echo $b['status'] === 'valid' ? 'selected' : ''; ?>>Valid</option>
                                    <option value="tidak_valid" <?php echo $b['status'] === 'tidak_valid' ? 'selected' : ''; ?>>Tidak Valid</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p class="text-muted mb-0">Belum ada berkas diunggah.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Aksi -->
        <?php if ($pendaftaran['status'] === 'dikirim' || $pendaftaran['status'] === 'ditolak'): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Verifikasi Berkas</div>
            <div class="card-body">
                <form method="post" action="<?php echo url('admin/pendaftar/' . $pendaftaran['id'] . '/verifikasi'); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo input_field('catatan', 'Catatan (opsional)', array('type' => 'textarea', 'rows' => 2, 'placeholder' => 'Catatan untuk pendaftar')); ?>
                    <div class="d-grid gap-2">
                        <button type="submit" name="aksi" value="verifikasi" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i>Verifikasi Berkas
                        </button>
                        <button type="submit" name="aksi" value="tolak" class="btn btn-outline-danger">
                            <i class="bi bi-x-circle me-1"></i>Tolak Berkas
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <?php if (in_array($pendaftaran['status'], array('diverifikasi', 'ditolak', 'dikirim'))): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">Hasil Seleksi</div>
            <div class="card-body">
                <form method="post" action="<?php echo url('admin/seleksi/' . $pendaftaran['id']); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo input_field('catatan', 'Catatan (opsional)', array('type' => 'textarea', 'rows' => 2)); ?>
                    <div class="d-grid gap-2">
                        <button type="submit" name="hasil" value="diterima" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i>Terima Siswa
                        </button>
                        <button type="submit" name="hasil" value="tidak_diterima" class="btn btn-outline-dark">
                            <i class="bi bi-x-lg me-1"></i>Tidak Terima
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
