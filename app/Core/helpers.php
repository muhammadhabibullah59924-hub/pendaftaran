<?php
/**
 * Kumpulan fungsi bantu (helper) global.
 * Ditulis kompatibel dengan PHP 5.6.
 */

/** Baca nilai konfigurasi */
function config($key = null, $default = null)
{
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require BASE_PATH . '/config/config.php';
    }
    if ($key === null) {
        return $cfg;
    }
    return isset($cfg[$key]) ? $cfg[$key] : $default;
}

/** Escape output HTML */
function e($value)
{
    if ($value === null) {
        return '';
    }
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/** URL relatif ke root aplikasi */
function url($path = '')
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/** URL aset */
function asset($path)
{
    return BASE_URL . '/public/assets/' . ltrim($path, '/');
}

/** Redirect lalu hentikan skrip */
function redirect($path = '')
{
    header('Location: ' . url($path));
    exit;
}

/** Ambil HTTP method */
function method()
{
    return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
}

/** Nilai input POST yang sudah di-trim */
function input($key, $default = '')
{
    if (!isset($_POST[$key])) {
        return $default;
    }
    $val = $_POST[$key];
    if (is_string($val)) {
        return trim($val);
    }
    return $val;
}

/** Nilai input GET yang sudah di-trim */
function query($key, $default = '')
{
    if (!isset($_GET[$key])) {
        return $default;
    }
    return is_string($_GET[$key]) ? trim($_GET[$key]) : $_GET[$key];
}

/** Nilai lama input (setelah validasi gagal) */
function old($key, $default = '')
{
    if (isset($_SESSION['_old'][$key])) {
        return $_SESSION['_old'][$key];
    }
    return $default;
}

/** Daftar error validasi */
function errors()
{
    return isset($_SESSION['_errors']) ? $_SESSION['_errors'] : array();
}

/** Cek apakah suatu field punya error */
function has_error($key)
{
    $e = errors();
    return isset($e[$key]);
}

/** Ambil pesan error suatu field */
function error_msg($key)
{
    $e = errors();
    return isset($e[$key]) ? $e[$key] : '';
}

/** Field CSRF */
function csrf_field()
{
    return Csrf::field();
}

/** User yang sedang login */
function auth()
{
    return Auth::user();
}

/** Format tanggal Indonesia */
function tanggal($date, $with_day = false)
{
    if (!$date || $date === '0000-00-00') {
        return '-';
    }
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    );
    $hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
    $t = strtotime($date);
    $out = date('d', $t) . ' ' . $bulan[(int) date('n', $t)] . ' ' . date('Y', $t);
    if ($with_day) {
        $out = $hari[(int) date('w', $t)] . ', ' . $out;
    }
    return $out;
}

/** Format tanggal-waktu */
function tanggal_waktu($date)
{
    if (!$date) {
        return '-';
    }
    return tanggal($date) . ' ' . date('H:i', strtotime($date)) . ' WIB';
}

/** Label status pendaftaran */
function status_label($status)
{
    $map = array(
        'draft'         => 'Draft (belum dikirim)',
        'dikirim'       => 'Menunggu Verifikasi',
        'diverifikasi'  => 'Berkas Terverifikasi',
        'ditolak'       => 'Berkas Ditolak',
        'diterima'      => 'DITERIMA',
        'tidak_diterima' => 'Tidak Diterima',
    );
    return isset($map[$status]) ? $map[$status] : $status;
}

/** Warna badge Bootstrap untuk status */
function status_color($status)
{
    $map = array(
        'draft'          => 'secondary',
        'dikirim'        => 'warning',
        'diverifikasi'   => 'info',
        'ditolak'        => 'danger',
        'diterima'       => 'success',
        'tidak_diterima' => 'dark',
    );
    return isset($map[$status]) ? $map[$status] : 'secondary';
}

/** Badge HTML status */
function status_badge($status)
{
    return '<span class="badge bg-' . status_color($status) . '">' . e(status_label($status)) . '</span>';
}

/** Label status berkas */
function berkas_color($status)
{
    $map = array('menunggu' => 'warning', 'valid' => 'success', 'tidak_valid' => 'danger');
    return isset($map[$status]) ? $map[$status] : 'secondary';
}

/** Tipe berkas yang wajib diunggah */
function jenis_berkas()
{
    return array(
        'akta'  => 'Akta Kelahiran',
        'kk'    => 'Kartu Keluarga (KK)',
        'foto'  => 'Pas Foto 3x4',
        'ijazah' => 'Ijazah / SKL TK-RA',
    );
}

/** Render input form dengan label & error */
function input_field($name, $label, $options = array())
{
    $type = isset($options['type']) ? $options['type'] : 'text';
    $value = isset($options['value']) ? $options['value'] : old($name);
    $placeholder = isset($options['placeholder']) ? $options['placeholder'] : '';
    $required = !empty($options['required']);
    $hint = isset($options['hint']) ? $options['hint'] : '';
    $rows = isset($options['rows']) ? $options['rows'] : 3;
    $class = ($type === 'textarea' ? 'form-control' : 'form-control') . (has_error($name) ? ' is-invalid' : '');

    $html = '<div class="mb-3">';
    $html .= '<label for="f-' . $name . '" class="form-label">' . e($label)
           . ($required ? ' <span class="text-danger">*</span>' : '') . '</label>';
    if ($type === 'textarea') {
        $html .= '<textarea id="f-' . $name . '" name="' . $name . '" class="' . $class . '" rows="'
               . $rows . '" placeholder="' . e($placeholder) . '">' . e($value) . '</textarea>';
    } else {
        $html .= '<input type="' . $type . '" id="f-' . $name . '" name="' . $name . '" class="' . $class
               . '" value="' . e($value) . '" placeholder="' . e($placeholder) . '"'
               . ($required ? ' required' : '') . '>';
    }
    if ($hint) {
        $html .= '<div class="form-text">' . e($hint) . '</div>';
    }
    if (has_error($name)) {
        $html .= '<div class="invalid-feedback">' . e(error_msg($name)) . '</div>';
    }
    $html .= '</div>';
    return $html;
}

/** Render select form */
function select_field($name, $label, $list, $options = array())
{
    $selected = isset($options['selected']) ? $options['selected'] : old($name);
    $required = !empty($options['required']);
    $placeholder = isset($options['placeholder']) ? $options['placeholder'] : '-- Pilih --';
    $class = 'form-select' . (has_error($name) ? ' is-invalid' : '');

    $html = '<div class="mb-3">';
    $html .= '<label for="f-' . $name . '" class="form-label">' . e($label)
           . ($required ? ' <span class="text-danger">*</span>' : '') . '</label>';
    $html .= '<select id="f-' . $name . '" name="' . $name . '" class="' . $class . '"'
           . ($required ? ' required' : '') . '>';
    $html .= '<option value="">' . e($placeholder) . '</option>';
    foreach ($list as $key => $optLabel) {
        $isSel = ((string) $key === (string) $selected) ? ' selected' : '';
        $html .= '<option value="' . e($key) . '"' . $isSel . '>' . e($optLabel) . '</option>';
    }
    $html .= '</select>';
    if (has_error($name)) {
        $html .= '<div class="invalid-feedback">' . e(error_msg($name)) . '</div>';
    }
    $html .= '</div>';
    return $html;
}

/** Tombol submit */
function submit_btn($label = 'Simpan', $class = 'btn btn-primary')
{
    return '<button type="submit" class="' . $class . '">' . e($label) . '</button>';
}
