<?php
$title = 'Bukti Pendaftaran';
$tgl = $pendaftaran['tanggal_daftar'] ? tanggal($pendaftaran['tanggal_daftar']) : tanggal(date('Y-m-d'));
?>

<div class="bg-white border rounded-3 p-4 mb-4">
    <!-- Kop surat -->
    <div class="text-center border-bottom pb-3 mb-4">
        <div class="fw-bold fs-4"><?php echo e(config('sekolah')); ?></div>
        <div class="text-muted small">
            <?php echo e(config('jenjang')); ?> &middot; <?php echo e(config('alamat')); ?> &middot; <?php echo e(config('telp')); ?>
        </div>
        <h5 class="fw-bold mt-3">BUKTI PENDAFTARAN SISWA BARU</h5>
        <?php if ($gelombang): ?>
        <div class="small text-muted"><?php echo e($gelombang['nama']); ?> &middot; <?php echo e($gelombang['tahun_ajaran']); ?> &middot; <?php echo e($gelombang['jenis']); ?></div>
        <?php endif; ?>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td class="text-muted" style="width:150px">Nomor Pendaftaran</td>
                    <td class="fw-bold">: <?php echo e($pendaftaran['no_pendaftaran']); ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Tanggal Daftar</td>
                    <td>: <?php echo e($tgl); ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Status</td>
                    <td>: <?php echo e(status_label($pendaftaran['status'])); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <h6 class="border-bottom pb-1 mb-2">Data Siswa</h6>
    <div class="row">
        <div class="col-md-6">
            <table class="table table-sm table-borderless mb-0">
                <tr><td class="text-muted" style="width:140px">Nama Lengkap</td><td>: <?php echo e($pendaftaran['nama_lengkap']); ?></td></tr>
                <tr><td class="text-muted">Nama Panggilan</td><td>: <?php echo e($pendaftaran['nama_panggilan'] ?: '-'); ?></td></tr>
                <tr><td class="text-muted">Jenis Kelamin</td><td>: <?php echo e($pendaftaran['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'); ?></td></tr>
                <tr><td class="text-muted">Tempat, Tgl Lahir</td><td>: <?php echo e($pendaftaran['tempat_lahir']); ?>, <?php echo tanggal($pendaftaran['tanggal_lahir']); ?></td></tr>
                <tr><td class="text-muted">Agama</td><td>: <?php echo e($pendaftaran['agama']); ?></td></tr>
                <tr><td class="text-muted">Anak Ke- / Saudara</td><td>: <?php echo (int) $pendaftaran['anak_ke']; ?> / <?php echo (int) $pendaftaran['jumlah_saudara']; ?></td></tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-sm table-borderless mb-0">
                <tr><td class="text-muted" style="width:140px">NISN</td><td>: <?php echo e($pendaftaran['nisn'] ?: '-'); ?></td></tr>
                <tr><td class="text-muted">Asal TK/RA</td><td>: <?php echo e($pendaftaran['asal_sekolah'] ?: '-'); ?></td></tr>
                <tr><td class="text-muted">Tahun Lulus</td><td>: <?php echo e($pendaftaran['tahun_lulus'] ?: '-'); ?></td></tr>
            </table>
        </div>
    </div>

    <h6 class="border-bottom pb-1 mb-2 mt-3">Data Orang Tua / Wali</h6>
    <div class="row">
        <div class="col-md-6">
            <table class="table table-sm table-borderless mb-0">
                <tr><td class="text-muted" style="width:140px">Ayah</td><td>: <?php echo e($pendaftaran['nama_ayah']); ?> (<?php echo e($pendaftaran['pekerjaan_ayah']); ?>)</td></tr>
                <tr><td class="text-muted">Ibu</td><td>: <?php echo e($pendaftaran['nama_ibu']); ?> (<?php echo e($pendaftaran['pekerjaan_ibu']); ?>)</td></tr>
                <tr><td class="text-muted">No. HP</td><td>: <?php echo e($pendaftaran['hp_ayah']); ?> / <?php echo e($pendaftaran['hp_ibu']); ?></td></tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-sm table-borderless mb-0">
                <tr><td class="text-muted" style="width:140px">Alamat</td><td>: <?php echo e($pendaftaran['alamat']); ?>, RT/RW <?php echo e($pendaftaran['rt_rw'] ?: '-'); ?>,
                    <?php echo e($pendaftaran['kelurahan']); ?>, <?php echo e($pendaftaran['kecamatan']); ?>, <?php echo e($pendaftaran['kota']); ?>, <?php echo e($pendaftaran['provinsi']); ?> <?php echo e($pendaftaran['kode_pos']); ?></td></tr>
                <?php if ($pendaftaran['nama_wali']): ?>
                <tr><td class="text-muted">Wali</td><td>: <?php echo e($pendaftaran['nama_wali']); ?> (<?php echo e($pendaftaran['hubungan_wali']); ?>)</td></tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <h6 class="border-bottom pb-1 mb-2 mt-3">Daftar Berkas</h6>
    <table class="table table-sm table-bordered mb-0">
        <thead class="table-light">
            <tr><th style="width:40px">No</th><th>Jenis Berkas</th><th>Nama Berkas</th><th style="width:140px">Status</th></tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php if (!empty($berkas)): ?>
                <?php foreach ($berkas as $b): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo e(isset($jenis_berkas[$b['jenis']]) ? $jenis_berkas[$b['jenis']] : $b['jenis']); ?></td>
                    <td><?php echo e($b['nama_asli']); ?></td>
                    <td><?php echo e($b['status']); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center text-muted">Belum ada berkas</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="row mt-5">
        <div class="col-md-6"></div>
        <div class="col-md-6 text-center">
            <p class="mb-4">Petugas Penerimaan</p>
            <p class="fw-bold">( ______________________ )</p>
        </div>
    </div>
</div>

<p class="text-center small text-muted">
    Simpan bukti ini sebagai alat bukti pendaftaran. Nomor pendaftaran:
    <strong><?php echo e($pendaftaran['no_pendaftaran']); ?></strong>
</p>
