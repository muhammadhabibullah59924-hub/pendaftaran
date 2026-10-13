<?php
$title = 'Gelombang Pendaftaran';
$gelModel = new Gelombang();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
        <span>Gelombang / Jalur Pendaftaran</span>
        <a href="<?php echo url('admin/gelombang/tambah'); ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Gelombang
        </a>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($gelombang)): ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Tahun Ajaran</th>
                        <th>Jenis</th>
                        <th>Periode</th>
                        <th class="text-end">Kuota</th>
                        <th class="text-end">Pendaftar</th>
                        <th>Status</th>
                        <th class="text-end" style="width:110px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($gelombang as $g): ?>
                        <?php
                            $masuk = $gelModel->countPendaftar($g['id']);
                            $aktif = ($g['status'] === 'buka' && $g['tanggal_mulai'] <= date('Y-m-d') && $g['tanggal_selesai'] >= date('Y-m-d'));
                        ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($g['nama']); ?></td>
                        <td><?php echo e($g['tahun_ajaran']); ?></td>
                        <td><?php echo e($g['jenis']); ?></td>
                        <td><small><?php echo tanggal($g['tanggal_mulai']); ?> &ndash; <?php echo tanggal($g['tanggal_selesai']); ?></small></td>
                        <td class="text-end"><?php echo (int) $g['kuota']; ?></td>
                        <td class="text-end">
                            <?php echo (int) $masuk; ?>
                            <?php if ($g['kuota'] > 0): ?>
                            <small class="text-muted d-block">(sisa <?php echo max(0, (int) $g['kuota'] - $masuk); ?>)</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($aktif): ?>
                                <span class="badge bg-success">Dibuka</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?php echo e($g['status']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="<?php echo url('admin/gelombang/' . $g['id'] . '/edit'); ?>" class="btn btn-sm btn-light" title="Ubah">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="post" action="<?php echo url('admin/gelombang/' . $g['id'] . '/hapus'); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-light" title="Hapus"
                                            onclick="return confirm('Hapus gelombang ini? (hanya jika belum ada pendaftar)')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p class="text-muted p-4 mb-0 text-center">Belum ada gelombang.</p>
        <?php endif; ?>
    </div>
</div>
