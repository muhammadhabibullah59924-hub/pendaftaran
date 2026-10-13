<?php
/**
 * Controller autentikasi: login, register, logout
 */
class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            redirect(Auth::isAdmin() ? 'admin' : (Auth::isKepsek() ? 'pimpinan' : 'dashboard'));
        }
        $this->view('auth/login', array(), 'layouts/public');
    }

    public function login()
    {
        $this->checkToken();

        $v = new Validator($_POST);
        $v->run(
            array(
                'email'    => array('required', 'email'),
                'password' => array('required'),
            ),
            array('email' => 'Email', 'password' => 'Password')
        );

        if ($v->fails()) {
            Session::set('_errors', $v->errors());
            Session::set('_old', $_POST);
            redirect('login');
        }

        $email = input('email');
        $password = input('password');

        if (Auth::attempt($email, $password)) {
            Session::flash('success', 'Selamat datang, ' . Auth::user()['nama'] . '!');
            if (Auth::isAdmin()) {
                redirect('admin');
            } elseif (Auth::isKepsek()) {
                redirect('pimpinan');
            }
            redirect('dashboard');
        }

        // Bedakan penyebab gagal login agar wali murid tahu harus menunggu persetujuan
        $userModel = new User();
        $cek = $userModel->findByEmail($email);
        if ($cek && !$cek['aktif']) {
            Session::flash('error', 'Akun Anda <b>belum disetujui</b> panitia. '
                . 'Silakan tunggu panitia menyetujui akun, lalu coba login lagi.');
        } else {
            Session::flash('error', 'Email atau password salah.');
        }
        redirect('login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            redirect('dashboard');
        }
        $this->view('auth/register', array(), 'layouts/public');
    }

    public function register()
    {
        $this->checkToken();

        $v = new Validator($_POST);
        $v->run(
            array(
                'nama'     => array('required', 'max:100'),
                'email'    => array('required', 'email', 'unique:users'),
                'password' => array('required', 'min:6'),
                'ulangi'   => array('required', 'same:password'),
            ),
            array(
                'nama'     => 'Nama lengkap',
                'email'    => 'Email',
                'password' => 'Password',
                'ulangi'   => 'Ulangi password',
            )
        );

        if ($v->fails()) {
            Session::set('_errors', $v->errors());
            Session::set('_old', $_POST);
            redirect('register');
        }

        $user = new User();
        $user->create(array(
            'nama'     => input('nama'),
            'email'    => input('email'),
            'password' => password_hash(input('password'), PASSWORD_BCRYPT),
            'role'     => 'wali_murid',
            // Akun wali murid baru menunggu persetujuan panitia (lihat menu Kelola Pengguna)
            'aktif'    => 0,
        ));

        Session::flash('success', 'Akun berhasil dibuat dan <b>menunggu persetujuan panitia</b>. '
            . 'Anda baru bisa login setelah akun disetujui. Silakan tunggu info dari sekolah. '
            . '<b>&#9888;&#65039; Simpan password Anda &mdash; panitia tidak bisa melihat password Anda.</b>');
        redirect('login');
    }

    public function logout()
    {
        Auth::logout();
        Session::flash('success', 'Anda telah keluar.');
        redirect('/');
    }
}
