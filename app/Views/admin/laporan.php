<?php
$title = 'Laporan';

// Peta jumlah per bulan
$bulanMap = array();
foreach ($per_bulan as $b) {
    $bulanMap[(int) $b['bulan']] = (int) $b['jumlah'];
}
$namaBulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
$maxBulan = 1;
for ($m = 1; $m <= 12; $m++) {
    if (isset($bulanMap[$m]) && $bulanMap[$m] > $maxBulan) { $maxBulan = $bulanMap[$m]; }
}
$totalSetahun = array_sum($bulanMap);
?>

<!-- Filter tahun -->
<form method="get" action="<?php echo url('admin/laporan'); ?>" class="row g-2 align-items-end mb-4">
    <div class="col-md-3">
        <label class="form-label small mb-1">Tahun</label>
        <input type="number" name="tahun" class="form-control" value="<?php echo (int) $tahun; ?>" min="2000" max="2100">
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-funnel me-1"></i>Lihat
        </button>
        <a href="<?php echo url('admin/laporan/csv'); ?>" class="btn btn-success">
            <i class="bi bi-download me-1"></i>Export CSV
        </a>
    </div>
</form>

<div class="row g-3 mb-4">
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm"><div class="card-body">
            <div class="h4 mb-0"><?php echo (int) $stats['total']; ?></div>
            <small class="text-muted">Total Pendaftar</small>
        </div></div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm"><div class="card-body">
            <div class="h4 mb-0"><?php echo (int) $stats['laki']; ?></div>
            <small class="text-muted">Laki-laki</small>
        </div></div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm"><div class="card-body">
            <div class="h4 mb-0"><?php echo (int) $stats['perempuan']; ?></div>
            <small class="text-muted">Perempuan</small>
        </div></div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm"><div class="card-body">
            <div class="h4 mb-0 text-success"><?php echo (int) $stats['diterima']; ?></div>
            <small class="text-muted">Diterima</small>
        </div></div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
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

    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Grafik Pendaftar per Bulan — <?php echo (int) $tahun; ?> (<?php echo $totalSetahun; ?> total)</div>
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
                <p class="text-muted mb-0">Tidak ada pendaftar pada tahun <?php echo (int) $tahun; ?>.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
