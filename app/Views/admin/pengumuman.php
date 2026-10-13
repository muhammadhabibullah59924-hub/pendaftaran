<?php
$title = 'Pengumuman';
$gelNama = array();
foreach ($gelombang as $g) { $gelNama[$g['id']] = $g['nama']; }
?>

<div class="card shadow-sm">
    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
        <span>Pengumuman</span>
        <a href="<?php echo url('admin/pengumuman/tambah'); ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Pengumuman
        </a>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($pengumuman)): ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:120px">Tanggal</th>
                        <th>Judul</th>
                        <th>Isi</th>
                        <th>Gelombang</th>
                        <th>Status</th>
                        <th class="text-end" style="width:110px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pengumuman as $p): ?>
                    <tr>
                        <td><small class="text-muted"><?php echo tanggal($p['tanggal']); ?></small></td>
                        <td class="fw-semibold"><?php echo e($p['judul']); ?></td>
                        <td class="text-truncate" style="max-width:260px"><?php echo e($p['isi']); ?></td>
                        <td>
                            <?php if ($p['gelombang_id'] && isset($gelNama[$p['gelombang_id']])): ?>
                                <?php echo e($gelNama[$p['gelombang_id']]); ?>
                            <?php else: ?>
                                <small class="text-muted">Umum</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo $p['status'] === 'aktif' ? 'success' : 'secondary'; ?>">
                                <?php echo e($p['status']); ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo url('admin/pengumuman/' . $p['id'] . '/edit'); ?>" class="btn btn-light" title="Ubah">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?php echo url('admin/pengumuman/' . $p['id'] . '/hapus'); ?>" class="btn btn-light" title="Hapus"
                                   onclick="return confirm('Hapus pengumuman ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p class="text-muted p-4 mb-0 text-center">Belum ada pengumuman.</p>
        <?php endif; ?>
    </div>
</div>
