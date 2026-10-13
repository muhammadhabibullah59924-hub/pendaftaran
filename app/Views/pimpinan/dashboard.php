<?php
$title = 'Dashboard Pimpinan';

$bulanMap = array();
foreach ($per_bulan as $b) { $bulanMap[(int) $b['bulan']] = (int) $b['jumlah']; }
$namaBulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
$maxBulan = 1;
foreach ($bulanMap as $j) { if ($j > $maxBulan) { $maxBulan = $j; } }
$totalSetahun = array_sum($bulanMap);
?>

<div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
    <div>
        <h4 class="mb-1">Ringkasan Penerimaan Siswa Baru</h4>
        <p class="text-muted mb-0"><?php echo e(config('sekolah')); ?> &middot; Tahun <?php echo (int) $tahun; ?></p>
    </div>
    <form method="get" action="<?php echo url('pimpinan'); ?>" class="row g-2 align-items-end">
        <div class="col-auto">
            <input type="number" name="tahun" class="form-control" value="<?php echo (int) $tahun; ?>" min="2000" max="2100">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">Lihat</button>
        </div>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 mb-3"><div class="card shadow-sm"><div class="card-body">
        <div class="h4 mb-0"><?php echo (int) $stats['total']; ?></div><small class="text-muted">Total Pendaftar</small>
    </div></div></div>
    <div class="col-md-3 mb-3"><div class="card shadow-sm"><div class="card-body">
        <div class="h4 mb-0 text-warning"><?php echo (int) $per_status['dikirim']; ?></div><small class="text-muted">Menunggu Verifikasi</small>
    </div></div></div>
    <div class="col-md-3 mb-3"><div class="card shadow-sm"><div class="card-body">
        <div class="h4 mb-0 text-info"><?php echo (int) $per_status['diverifikasi']; ?></div><small class="text-muted">Berkas Terverifikasi</small>
    </div></div></div>
    <div class="col-md-3 mb-3"><div class="card shadow-sm"><div class="card-body">
        <div class="h4 mb-0 text-success"><?php echo (int) $per_status['diterima']; ?></div><small class="text-muted">Diterima</small>
    </div></div></div>
</div>

<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Pendaftar per Bulan (<?php echo (int) $tahun; ?>)</div>
            <div class="card-body">
                <?php if ($totalSetahun > 0): ?>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <?php $j = isset($bulanMap[$m]) ? $bulanMap[$m] : 0; ?>
                        <?php $pct = $maxBulan > 0 ? round(($j / $maxBulan) * 100) : 0; ?>
                        <div class="d-flex align-items-center mb-2">
                            <div class="text-muted small" style="width:90px"><?php echo $namaBulan[$m]; ?></div>
                            <div class="progress flex-grow-1" style="height:20px">
                                <div class="progress-bar" style="width:<?php echo $pct; ?>%"><?php echo $j > 0 ? $j : ''; ?></div>
                            </div>
                        </div>
                    <?php endfor; ?>
                <?php else: ?>
                <p class="text-muted mb-0">Belum ada pendaftar pada tahun <?php echo (int) $tahun; ?>.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Rekap per Gelombang</div>
            <div class="card-body p-0">
                <?php if (!empty($per_gelombang)): ?>
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Gelombang</th><th class="text-end">Pendaftar</th><th class="text-end">Diterima</th></tr></thead>
                    <tbody>
                        <?php foreach ($per_gelombang as $g): ?>
                        <tr>
                            <td><?php echo e($g['nama']); ?> <small class="text-muted d-block"><?php echo e($g['tahun_ajaran']); ?></small></td>
                            <td class="text-end"><?php echo (int) $g['jumlah']; ?></td>
                            <td class="text-end"><?php echo (int) $g['diterima']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="text-muted p-3 mb-0">Belum ada data.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">Pendaftar Terbaru</div>
            <div class="card-body p-0">
                <?php if (!empty($terbaru)): ?>
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Nama</th><th>No. Pendaftaran</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach ($terbaru as $p): ?>
                        <tr>
                            <td><?php echo e($p['nama_lengkap']); ?></td>
                            <td><small class="text-muted"><?php echo e($p['no_pendaftaran']); ?></small></td>
                            <td><?php echo status_badge($p['status']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="text-muted p-3 mb-0">Belum ada pendaftar.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">Pengumuman Aktif</div>
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
