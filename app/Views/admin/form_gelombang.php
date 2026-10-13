<?php
$title = $gelombang ? 'Ubah Gelombang' : 'Tambah Gelombang';
$isEdit = !empty($gelombang);
$action = $isEdit ? url('admin/gelombang/' . $gelombang['id']) : url('admin/gelombang');
$statusList = array('buka' => 'Dibuka', 'tutup' => 'Ditutup');
?>

<form method="post" action="<?php echo e($action); ?>">
    <?php echo csrf_field(); ?>

    <?php echo input_field('nama', 'Nama Gelombang', array('required' => true, 'placeholder' => 'Contoh: Gelombang 1')); ?>

    <div class="row">
        <div class="col-md-4">
            <?php echo input_field('tahun_ajaran', 'Tahun Ajaran', array('required' => true, 'placeholder' => 'Contoh: 2026/2027')); ?>
        </div>
        <div class="col-md-4">
            <?php echo input_field('jenis', 'Jenis / Jalur', array('required' => true, 'placeholder' => 'Contoh: Umum / Prestasi')); ?>
        </div>
        <div class="col-md-4">
            <?php echo input_field('kuota', 'Kuota', array('type' => 'number', 'required' => true, 'placeholder' => '0 = tanpa kuota')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php echo input_field('tanggal_mulai', 'Tanggal Mulai', array('type' => 'date', 'required' => true)); ?>
        </div>
        <div class="col-md-4">
            <?php echo input_field('tanggal_selesai', 'Tanggal Selesai', array('type' => 'date', 'required' => true)); ?>
        </div>
        <div class="col-md-4">
            <?php echo select_field('status', 'Status', $statusList, array('required' => true)); ?>
        </div>
    </div>

    <?php echo input_field('deskripsi', 'Deskripsi', array('type' => 'textarea', 'rows' => 3)); ?>

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i><?php echo $isEdit ? 'Perbarui' : 'Simpan'; ?>
    </button>
    <a href="<?php echo url('admin/gelombang'); ?>" class="btn btn-outline-secondary">Batal</a>
</form>
