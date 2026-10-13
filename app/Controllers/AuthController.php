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

        Session::flash('error', 'Email atau password salah, atau akun belum aktif.');
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
            'aktif'    => 1,
        ));

        Session::flash('success', 'Akun berhasil dibuat. Silakan login.');
        redirect('login');
    }

    public function logout()
    {
        Auth::logout();
        Session::flash('success', 'Anda telah keluar.');
        redirect('/');
    }
}
