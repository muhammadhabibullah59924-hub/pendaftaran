<?php $title = 'Login'; ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="card-title mb-4 text-center">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Login
                </h4>

                <form method="post" action="<?php echo url('login'); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo input_field('email', 'Email', array('type' => 'email', 'placeholder' => 'Email Anda', 'required' => true)); ?>
                    <?php echo input_field('password', 'Password', array('type' => 'password', 'placeholder' => 'Password', 'required' => true)); ?>
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                    </button>
                </form>

                <hr>
                <p class="text-center mb-0 small">
                    Belum punya akun?
                    <a href="<?php echo url('register'); ?>">Daftar di sini</a>
                </p>
                <p class="text-center text-muted small mb-0">
                    Akun panitia/admin diberikan oleh sekolah.
                </p>
                <div class="alert alert-light border small py-2 mt-2 mb-0">
                    <i class="bi bi-key me-1"></i>Lupa password? Hubungi panitia untuk reset &mdash; password tidak bisa dilihat aplikasi.
                </div>
            </div>
        </div>
    </div>
</div>
