<?php $title = 'Daftar Akun'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="card-title mb-1 text-center">
                    <i class="bi bi-person-plus me-1"></i>Daftar Akun Wali Murid
                </h4>
                <p class="text-center text-muted small mb-4">
                    Akun digunakan untuk mendaftarkan siswa baru.<br>
                    <span class="badge bg-warning text-dark mt-1"><i class="bi bi-clock me-1"></i>Akun perlu disetujui panitia</span>
                </p>

                <form method="post" action="<?php echo url('register'); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="alert alert-warning d-flex align-items-start py-2 mb-3 border-start border-4 border-warning">
                        <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-warning"></i>
                        <div class="small mb-0">
                            <b>Catat password Anda!</b> Password ini <b>tidak bisa dilihat kembali</b> oleh siapa pun,
                            termasuk panitia. Simpan di catatan HP atau buku &mdash; jika lupa, harus minta reset ke panitia.
                        </div>
                    </div>
                    <?php echo input_field('nama', 'Nama Lengkap', array('placeholder' => 'Nama orang tua/wali', 'required' => true)); ?>
                    <?php echo input_field('email', 'Email', array('type' => 'email', 'placeholder' => 'Email aktif', 'required' => true, 'hint' => 'Email digunakan untuk login & notifikasi')); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?php echo input_field('password', 'Password', array('type' => 'password', 'placeholder' => 'Min. 6 karakter', 'required' => true)); ?>
                        </div>
                        <div class="col-md-6">
                            <?php echo input_field('ulangi', 'Ulangi Password', array('type' => 'password', 'placeholder' => 'Ulangi password', 'required' => true)); ?>
                        </div>
                    </div>
                    <div class="alert alert-info small py-2 mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        Setelah mendaftar, akun Anda <b>menunggu persetujuan panitia</b>.
                        Login baru bisa dilakukan setelah akun disetujui.
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-person-plus me-1"></i>Buat Akun
                    </button>
                </form>

                <hr>
                <p class="text-center mb-0 small">
                    Sudah punya akun? <a href="<?php echo url('login'); ?>">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>
