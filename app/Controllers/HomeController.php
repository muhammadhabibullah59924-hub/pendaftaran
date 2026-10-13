<?php
/**
 * Controller halaman depan (landing page)
 */
class HomeController extends Controller
{
    public function index()
    {
        // Jika sudah login, arahkan ke dashboard
        if (Auth::check()) {
            redirect(Auth::isAdmin() ? 'admin' : (Auth::isKepsek() ? 'pimpinan' : 'dashboard'));
        }

        $gelombang = new Gelombang();
        $pengumuman = new Pengumuman();

        $data = array(
            'gelombang'   => $gelombang->aktif(),
            'pengumuman'  => $pengumuman->aktif(),
            'jenis_berkas' => jenis_berkas(),
        );

        $this->view('home/index', $data, 'layouts/public');
    }
}
