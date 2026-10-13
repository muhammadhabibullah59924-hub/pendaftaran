<?php
$title = $pengumuman ? 'Ubah Pengumuman' : 'Tambah Pengumuman';
$isEdit = !empty($pengumuman);
$action = $isEdit ? url('admin/pengumuman/' . $pengumuman['id']) : url('admin/pengumuman');

$gelList = array();
foreach ($gelombang as $g) {
    $gelList[$g['id']] = $g['nama'] . ' (' . $g['tahun_ajaran'] . ')';
}
$statusList = array('aktif' => 'Aktif', 'nonaktif' => 'Nonaktif');
?>

<form method="post" action="<?php echo e($action); ?>">
    <?php echo csrf_field(); ?>

    <div class="row">
        <div class="col-md-8">
            <?php echo input_field('judul', 'Judul', array('required' => true, 'maxlength' => 150)); ?>
        </div>
        <div class="col-md-4">
            <?php echo input_field('tanggal', 'Tanggal', array('type' => 'date', 'required' => true)); ?>
        </div>
    </div>

    <?php echo select_field('gelombang_id', 'Gelombang (opsional)', $gelList, array('placeholder' => 'Umum (tidak terikat gelombang)')); ?>

    <?php echo input_field('isi', 'Isi Pengumuman', array('type' => 'textarea', 'rows' => 5, 'required' => true)); ?>

    <?php echo select_field('status', 'Status', $statusList, array('required' => true)); ?>

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i><?php echo $isEdit ? 'Perbarui' : 'Simpan'; ?>
    </button>
    <a href="<?php echo url('admin/pengumuman'); ?>" class="btn btn-outline-secondary">Batal</a>
</form>
