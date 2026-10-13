<?php
$title = 'Kelola Pengguna';

$roleList = array(
    'wali_murid' => 'Wali Murid',
    'admin'      => 'Admin / Panitia',
    'kepsek'     => 'Kepala Sekolah',
);
$statusList = array(
    'menunggu' => 'Menunggu Persetujuan',
    'aktif'    => 'Aktif',
    'nonaktif' => 'Nonaktif',
);

$myId = (int) Auth::id();
?>

<?php if ($menunggu > 0): ?>
<div class="alert alert-warning d-flex align-items-start mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
    <div>
        Ada <b><?php echo (int) $menunggu; ?> akun wali murid</b> yang menunggu persetujuan dan belum bisa login.
        Periksa datanya, lalu klik <b>Setujui</b> untuk mengaktifkan.
    </div>
</div>
<?php endif; ?>

<!-- Filter -->
<form method="get" action="<?php echo url('admin/pengguna'); ?>" class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small mb-1">Cari</label>
                <input type="text" name="cari" class="form-control" placeholder="Nama / Email" value="<?php echo e($filter['cari']); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Peran</label>
                <select name="role" class="form-select">
                    <option value="">Semua</option>
                    <?php foreach ($roleList as $k => $v): ?>
                    <option value="<?php echo e($k); ?>" <?php echo $filter['role'] === $k ? 'selected' : ''; ?>><?php echo e($v); ?></option>
                    <?php endforeach; ?>
                </select>
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
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="<?php echo url('admin/pengguna'); ?>" class="btn btn-outline-secondary" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-header bg-white fw-bold">
        Daftar Pengguna (<?php echo count($daftar); ?>)
    </div>
    <div class="card-body p-0">
        <?php if (!empty($daftar)): ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th class="text-end">Pendaftaran</th>
                        <th>Terdaftar</th>
                        <th>Status</th>
                        <th class="text-end" style="width:200px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $n = 1; foreach ($daftar as $u): ?>
                    <?php $sendiri = ((int) $u['id'] === $myId); ?>
                    <tr class="<?php echo (!$u['aktif'] && $u['role'] === 'wali_murid') ? 'table-warning' : ''; ?>">
                        <td><?php echo $n++; ?></td>
                        <td class="fw-semibold">
                            <?php echo e($u['nama']); ?>
                            <?php if ($sendiri): ?><small class="text-muted d-block">(Anda)</small><?php endif; ?>
                        </td>
                        <td><small><?php echo e($u['email']); ?></small></td>
                        <td><?php echo e(isset($roleLabel[$u['role']]) ? $roleLabel[$u['role']] : $u['role']); ?></td>
                        <td class="text-end"><?php echo (int) $u['jumlah_pendaftaran']; ?></td>
                        <td><small class="text-muted"><?php echo tanggal($u['created_at']); ?></small></td>
                        <td>
                            <?php if ($u['aktif']): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php elseif ($u['role'] === 'wali_murid'): ?>
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <?php if (!$u['aktif'] && !$sendiri): ?>
                                <form method="post" action="<?php echo url('admin/pengguna/' . $u['id'] . '/aktifkan'); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-success" title="Setujui & aktifkan akun">
                                        <i class="bi bi-check-lg me-1"></i>Setujui
                                    </button>
                                </form>
                                <?php endif; ?>

                                <?php if ($u['aktif'] && !$sendiri): ?>
                                <form method="post" action="<?php echo url('admin/pengguna/' . $u['id'] . '/nonaktifkan'); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Nonaktifkan akun"
                                            onclick="return confirm('Nonaktifkan akun <?php echo e($u['nama']); ?>? Ia tidak akan bisa login.')">
                                        <i class="bi bi-slash-circle me-1"></i>Nonaktifkan
                                    </button>
                                </form>
                                <?php endif; ?>

                                <?php if (!$sendiri && $u['role'] === 'wali_murid'): ?>
                                <form method="post" action="<?php echo url('admin/pengguna/' . $u['id'] . '/hapus'); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus akun"
                                            onclick="return confirm('Hapus permanen akun <?php echo e($u['nama']); ?>?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>

                                <?php if ($sendiri): ?>
                                <span class="text-muted small align-self-center" title="Ini akun Anda sendiri">&mdash;</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p class="text-muted p-4 mb-0 text-center">Tidak ada pengguna yang cocok dengan filter.</p>
        <?php endif; ?>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-header bg-white fw-bold">Cara Kerja Persetujuan Akun</div>
    <div class="card-body small text-muted mb-0">
        <ol class="mb-0 ps-3">
            <li>Wali murid mendaftar sendiri lewat halaman <b>Daftar</b> — akun langsung berstatus <b>Menunggu</b> dan belum bisa login.</li>
            <li>Panitia memeriksa nama &amp; email di sini, lalu klik <b>Setujui</b>.</li>
            <li>Setelah disetujui, wali murid bisa login dan mengisi formulir pendaftaran.</li>
            <li><b>Nonaktifkan</b> dipakai bila akun bermasalah (bisa diaktifkan kembali kapan saja).</li>
            <li>Akun dengan data pendaftaran tidak bisa dihapus agar riwayat tetap tersimpan — gunakan <b>Nonaktifkan</b>.</li>
        </ol>
    </div>
</div>
