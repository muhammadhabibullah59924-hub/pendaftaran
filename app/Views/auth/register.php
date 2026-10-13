<?php $title = 'Daftar Akun'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="card-title mb-1 text-center">
                    <i class="bi bi-person-plus me-1"></i>Daftar Akun Wali Murid
                </h4>
                <p class="text-center text-muted small mb-4">
                    Akun digunakan untuk mendaftarkan siswa baru.
                </p>

                <form method="post" action="<?php echo url('register'); ?>">
                    <?php echo csrf_field(); ?>
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
