<?php
$title = 'Formulir Pendaftaran';

// Daftar pilihan
$agama = array('Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya');
$agamaList = array();
foreach ($agama as $a) { $agamaList[$a] = $a; }

$jk = array('L' => 'Laki-laki', 'P' => 'Perempuan');

$pendidikan = array('SD/Sederajat', 'SMP/Sederajat', 'SMA/SMK', 'S1/Sederajat', 'S2', 'S3', 'Lainnya');
$pendidikanList = array();
foreach ($pendidikan as $p) { $pendidikanList[$p] = $p; }

$gelombangList = array();
foreach ($gelombang as $g) {
    $gelombangList[$g['id']] = $g['nama'] . ' (' . $g['jenis'] . ' — ' . $g['tahun_ajaran'] . ')';
}
?>

<?php if (empty($gelombangList)): ?>
<div class="alert alert-warning">Belum ada gelombang pendaftaran yang dibuka.</div>
<?php else: ?>

<form method="post" action="<?php echo url('pendaftaran'); ?>" class="needs-validation" novalidate>
    <?php echo csrf_field(); ?>

    <!-- Data Siswa -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold">
            <i class="bi bi-person me-1"></i>Data Siswa
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?php echo input_field('nama_lengkap', 'Nama Lengkap', array('required' => true, 'placeholder' => 'Sesuai akta kelahiran')); ?>
                </div>
                <div class="col-md-6">
                    <?php echo input_field('nama_panggilan', 'Nama Panggilan', array('placeholder' => 'Nama sehari-hari')); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?php echo select_field('jenis_kelamin', 'Jenis Kelamin', $jk, array('required' => true)); ?>
                </div>
                <div class="col-md-4">
                    <?php echo input_field('tempat_lahir', 'Tempat Lahir', array('required' => true)); ?>
                </div>
                <div class="col-md-4">
                    <?php echo input_field('tanggal_lahir', 'Tanggal Lahir', array('type' => 'date', 'required' => true)); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <?php echo select_field('agama', 'Agama', $agamaList, array('required' => true)); ?>
                </div>
                <div class="col-md-3">
                    <?php echo input_field('kewarganegaraan', 'Kewarganegaraan', array('placeholder' => 'Indonesia')); ?>
                </div>
                <div class="col-md-3">
                    <?php echo input_field('anak_ke', 'Anak Ke-', array('type' => 'number', 'required' => true)); ?>
                </div>
                <div class="col-md-3">
                    <?php echo input_field('jumlah_saudara', 'Jumlah Saudara', array('type' => 'number', 'required' => true)); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?php echo input_field('nisn', 'NISN', array('placeholder' => 'opsional')); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Orang Tua -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold">
            <i class="bi bi-people me-1"></i>Data Orang Tua
        </div>
        <div class="card-body">
            <h6 class="text-muted">Ayah</h6>
            <div class="row">
                <div class="col-md-4"><?php echo input_field('nama_ayah', 'Nama Ayah', array('required' => true, 'maxlength' => 100)); ?></div>
                <div class="col-md-3"><?php echo select_field('pendidikan_ayah', 'Pendidikan', $pendidikanList); ?></div>
                <div class="col-md-3"><?php echo input_field('pekerjaan_ayah', 'Pekerjaan', array('required' => true)); ?></div>
                <div class="col-md-2"><?php echo input_field('hp_ayah', 'No. HP', array('required' => true)); ?></div>
            </div>
            <hr>
            <h6 class="text-muted">Ibu</h6>
            <div class="row">
                <div class="col-md-4"><?php echo input_field('nama_ibu', 'Nama Ibu', array('required' => true, 'maxlength' => 100)); ?></div>
                <div class="col-md-3"><?php echo select_field('pendidikan_ibu', 'Pendidikan', $pendidikanList); ?></div>
                <div class="col-md-3"><?php echo input_field('pekerjaan_ibu', 'Pekerjaan', array('required' => true)); ?></div>
                <div class="col-md-2"><?php echo input_field('hp_ibu', 'No. HP', array('required' => true)); ?></div>
            </div>
        </div>
    </div>

    <!-- Data Wali -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold">
            <i class="bi bi-person-badge me-1"></i>Data Wali <small class="text-muted">(opsional, jika wali bukan orang tua)</small>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"><?php echo input_field('nama_wali', 'Nama Wali'); ?></div>
                <div class="col-md-4"><?php echo input_field('hubungan_wali', 'Hubungan'); ?></div>
                <div class="col-md-4"><?php echo input_field('hp_wali', 'No. HP'); ?></div>
            </div>
        </div>
    </div>

    <!-- Asal Sekolah -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold">
            <i class="bi bi-building me-1"></i>Asal Sekolah
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5"><?php echo input_field('asal_sekolah', 'Asal TK / RA', array('required' => true)); ?></div>
                <div class="col-md-3"><?php echo input_field('npsn_asal', 'NPSN', array('placeholder' => 'opsional')); ?></div>
                <div class="col-md-2"><?php echo input_field('tahun_lulus', 'Tahun Lulus', array('placeholder' => 'cth: 2026')); ?></div>
            </div>
        </div>
    </div>

    <!-- Alamat -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold">
            <i class="bi bi-geo-alt me-1"></i>Alamat
        </div>
        <div class="card-body">
            <?php echo input_field('alamat', 'Alamat Lengkap', array('type' => 'textarea', 'rows' => 2, 'required' => true, 'placeholder' => 'Jalan, nomor rumah')); ?>
            <div class="row">
                <div class="col-md-3"><?php echo input_field('rt_rw', 'RT / RW', array('placeholder' => '00/00')); ?></div>
                <div class="col-md-3"><?php echo input_field('kelurahan', 'Kelurahan / Desa', array('required' => true)); ?></div>
                <div class="col-md-3"><?php echo input_field('kecamatan', 'Kecamatan', array('required' => true)); ?></div>
                <div class="col-md-3"><?php echo input_field('kota', 'Kota / Kabupaten', array('required' => true)); ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?php echo input_field('provinsi', 'Provinsi', array('required' => true)); ?></div>
                <div class="col-md-2"><?php echo input_field('kode_pos', 'Kode Pos', array('required' => true)); ?></div>
            </div>
        </div>
    </div>

    <!-- Gelombang & Submit -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold">Gelombang Pendaftaran</div>
        <div class="card-body">
            <?php echo select_field('gelombang_id', 'Pilih Gelombang', $gelombangList, array('required' => true)); ?>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>Simpan Data
        </button>
        <a href="<?php echo url('dashboard'); ?>" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>

<?php endif; ?>
