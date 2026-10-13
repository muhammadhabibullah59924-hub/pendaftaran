<?php
$title = 'Data Pendaftar';
$statusList = array(
    'draft' => 'Draft (belum dikirim)',
    'dikirim' => 'Menunggu Verifikasi',
    'diverifikasi' => 'Berkas Terverifikasi',
    'ditolak' => 'Berkas Ditolak',
    'diterima' => 'Diterima',
    'tidak_diterima' => 'Tidak Diterima',
);
?>

<!-- Filter -->
<form method="get" action="<?php echo url('admin/pendaftar'); ?>" class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small mb-1">Cari</label>
                <input type="text" name="cari" class="form-control" placeholder="Nama / No. pendaftaran" value="<?php echo e($filter['cari']); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <?php foreach ($statusList as $k => $v): ?>
                    <option value="<?php echo e($k); ?>" <?php echo $filter['status'] === $k ? 'selected' : ''; ?>><?php echo e($v); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Gelombang</label>
                <select name="gelombang" class="form-select">
                    <option value="">Semua</option>
                    <?php foreach ($gelombang as $g): ?>
                    <option value="<?php echo (int) $g['id']; ?>" <?php echo ((string) $filter['gelombang_id'] === (string) $g['id']) ? 'selected' : ''; ?>>
                        <?php echo e($g['nama'] . ' (' . $g['tahun_ajaran'] . ')'); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="<?php echo url('admin/pendaftar'); ?>" class="btn btn-outline-secondary" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="hide_draft" value="1" id="hide-draft"
                           <?php echo !empty($filter['hide_draft']) ? 'checked' : ''; ?>>
                    <label class="form-check-label small text-muted" for="hide-draft">
                        Sembunyikan draft (pendaftar yang belum menekan tombol Kirim)
                    </label>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
        <span>Daftar Pendaftar (<?php echo count($daftar); ?>)</span>
        <a href="<?php
            $qs = array();
            if (!empty($filter['status']))       { $qs['status'] = $filter['status']; }
            if (!empty($filter['gelombang_id'])) { $qs['gelombang'] = $filter['gelombang_id']; }
            if (!empty($filter['cari']))         { $qs['cari'] = $filter['cari']; }
            if (!empty($filter['hide_draft']))   { $qs['hide_draft'] = 1; }
            echo url('admin/laporan/csv') . ($qs ? '?' . http_build_query($qs) : '');
        ?>" class="btn btn-sm btn-success">
            <i class="bi bi-download me-1"></i>Export CSV
        </a>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($daftar)): ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px">No</th>
                        <th>No. Pendaftaran</th>
                        <th>Nama Siswa</th>
                        <th>JK</th>
                        <th>Asal Sekolah</th>
                        <th>Tgl Daftar</th>
                        <th>Status</th>
                        <th class="text-end" style="width:80px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $n = 1; foreach ($daftar as $p): ?>
                    <tr>
                        <td><?php echo $n++; ?></td>
                        <td><small class="text-muted"><?php echo e($p['no_pendaftaran']); ?></small></td>
                        <td class="fw-semibold"><?php echo e($p['nama_lengkap']); ?></td>
                        <td><?php echo e($p['jenis_kelamin']); ?></td>
                        <td class="text-truncate" style="max-width:160px"><?php echo e($p['asal_sekolah'] ?: '-'); ?></td>
                        <td><small class="text-muted"><?php echo tanggal($p['tanggal_daftar'] ?: $p['created_at']); ?></small></td>
                        <td><?php echo status_badge($p['status']); ?></td>
                        <td class="text-end">
                            <a href="<?php echo url('admin/pendaftar/' . $p['id']); ?>" class="btn btn-sm btn-light" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p class="text-muted p-4 mb-0 text-center">Tidak ada data pendaftar.</p>
        <?php endif; ?>
    </div>
</div>
