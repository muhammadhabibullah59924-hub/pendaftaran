<?php
/**
 * Pengelola unggah berkas.
 */
class Upload
{
    protected $allowed = array('jpg', 'jpeg', 'png', 'pdf');
    protected $maxSize = 2097152; // 2 MB

    /**
     * Simpan berkas.
     * Mengembalikan array('path'=>..., 'nama_asli'=>...) atau melempar Exception.
     */
    public function save($file, $folder)
    {
        if (!isset($file) || !is_array($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            throw new Exception('Berkas belum dipilih.');
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Terjadi kesalahan saat mengunggah berkas (kode ' . $file['error'] . ').');
        }
        if ($file['size'] > $this->maxSize) {
            throw new Exception('Ukuran berkas maksimal 2 MB.');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowed)) {
            throw new Exception('Tipe berkas tidak diizinkan. Gunakan JPG, PNG, atau PDF.');
        }

        // Pastikan ukuran & tipe benar-benar sesuai (bukan cuma ekstensi)
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            $validMimes = array(
                'image/jpeg', 'image/png', 'application/pdf',
                'application/octet-stream',
            );
            if (!in_array($mime, $validMimes)) {
                throw new Exception('Tipe berkas tidak diizinkan (format terdeteksi tidak valid).');
            }
        }

        $base = BASE_PATH . '/storage/uploads/' . $folder;
        if (!is_dir($base)) {
            if (!@mkdir($base, 0777, true)) {
                throw new Exception('Gagal membuat folder penyimpanan.');
            }
        }

        $nama = date('YmdHis') . '-' . bin2hex(openssl_random_pseudo_bytes(8)) . '.' . $ext;
        $tujuan = $base . '/' . $nama;

        if (!@move_uploaded_file($file['tmp_name'], $tujuan)) {
            throw new Exception('Gagal menyimpan berkas.');
        }

        return array(
            'path'      => 'storage/uploads/' . $folder . '/' . $nama,
            'nama_asli' => $file['name'],
            'ukuran'    => $file['size'],
            'tipe'      => $ext,
        );
    }

    /** Hapus berkas fisik */
    public function delete($path)
    {
        if (!$path) {
            return;
        }
        $file = BASE_PATH . '/' . $path;
        if (is_file($file)) {
            @unlink($file);
        }
    }

    /** Unduh berkas lewat PHP (melindungi path asli) */
    public function download($path, $namaAsli = null)
    {
        $file = BASE_PATH . '/' . $path;
        if (!is_file($file)) {
            http_response_code(404);
            echo 'Berkas tidak ditemukan.';
            exit;
        }
        $nama = $namaAsli ? $namaAsli : basename($path);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $nama . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}
