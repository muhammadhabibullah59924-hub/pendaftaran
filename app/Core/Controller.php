<?php
/**
 * Controller dasar.
 */
class Controller
{
    /** Render view */
    protected function view($view, $data = array(), $layout = 'layouts/main')
    {
        View::render($view, $data, $layout);
    }

    /** Redirect */
    protected function redirect($path = '')
    {
        redirect($path);
    }

    /** Respons JSON */
    protected function json($data, $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data);
        exit;
    }

    /** Simpan input lama & error, lalu kembali ke halaman sebelumnya */
    protected function back($withInput = true)
    {
        if ($withInput) {
            Session::set('_old', $_POST);
        }
        $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : url();
        header('Location: ' . $ref);
        exit;
    }

    /** Wajib login */
    protected function requireAuth()
    {
        if (!Auth::check()) {
            Session::flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }
    }

    /** Wajib role tertentu */
    protected function requireRole($roles)
    {
        $this->requireAuth();
        if (!Auth::hasRole($roles)) {
            http_response_code(403);
            echo '<h1>403 - Akses Ditolak</h1>';
            exit;
        }
    }

    /** Periksa token CSRF */
    protected function checkToken()
    {
        $token = isset($_POST['_token']) ? $_POST['_token'] : '';
        if (!Csrf::check($token)) {
            Session::flash('error', 'Token keamanan tidak valid. Silakan coba lagi.');
            redirect('/');
        }
    }

    /** Validasi sederhana */
    protected function validate($rules)
    {
        $v = new Validator($_POST);
        $v->run($rules);
        if ($v->fails()) {
            Session::set('_old', $_POST);
            Session::set('_errors', $v->errors());
            $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : url();
            header('Location: ' . $ref);
            exit;
        }
        return true;
    }
}
