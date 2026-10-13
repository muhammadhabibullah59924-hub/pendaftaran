<?php $title = 'Seleksi Pendaftaran'; ?>
<p class="text-muted">
    Pendaftar yang berkasnya sudah diverifikasi (atau ditolak) siap diproses seleksi.
    Klik <b>Proses</b> untuk menetapkan hasil.
</p>

<div class="card shadow-sm">
    <div class="card-header bg-white fw-bold">Daftar Calon Siswa (<?php echo count($daftar); ?>)</div>
    <div class="card-body p-0">
        <?php if (!empty($daftar)): ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px">No</th>
                        <th>No. Pendaftaran</th>
                        <th>Nama Siswa</th>
                        <th>Asal Sekolah</th>
                        <th>Status Berkas</th>
                        <th class="text-end" style="width:110px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $n = 1; foreach ($daftar as $p): ?>
                    <tr>
                        <td><?php echo $n++; ?></td>
                        <td><small class="text-muted"><?php echo e($p['no_pendaftaran']); ?></small></td>
                        <td class="fw-semibold"><?php echo e($p['nama_lengkap']); ?></td>
                        <td class="text-truncate" style="max-width:180px"><?php echo e($p['asal_sekolah'] ?: '-'); ?></td>
                        <td><?php echo status_badge($p['status']); ?></td>
                        <td class="text-end">
                            <a href="<?php echo url('admin/pendaftar/' . $p['id']); ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-funnel me-1"></i>Proses
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p class="text-muted p-4 mb-0 text-center">Belum ada calon siswa yang menunggu seleksi.</p>
        <?php endif; ?>
    </div>
</div>
