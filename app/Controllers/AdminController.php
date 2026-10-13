<?php
/**
 * Controller admin / panitia
 */
class AdminController extends Controller
{
    public function dashboard()
    {
        $pend = new Pendaftaran();
        $gel = new Gelombang();

        $data = array(
            'stats'     => $pend->stats(),
            'per_status' => array(
                'draft'          => $pend->countByStatus('draft'),
                'dikirim'       => $pend->countByStatus('dikirim'),
                'diverifikasi'  => $pend->countByStatus('diverifikasi'),
                'ditolak'       => $pend->countByStatus('ditolak'),
                'diterima'      => $pend->countByStatus('diterima'),
                'tidak_diterima' => $pend->countByStatus('tidak_diterima'),
            ),
            'terbaru'  => $pend->terbaru(5),
            'gelombang' => $gel->all(),
            'per_gelombang' => $pend->perGelombang(),
            'menunggu_akun' => (new User())->countPending(),
        );
        $this->view('admin/dashboard', $data, 'layouts/dashboard');
    }

    /** Daftar pendaftar */
    public function pendaftar()
    {
        $filter = array(
            'status'      => query('status'),
            'gelombang_id' => query('gelombang'),
            'cari'        => query('cari'),
            'hide_draft'  => query('hide_draft') === '1',
        );
        $pend = new Pendaftaran();
        $gel = new Gelombang();

        $data = array(
            'daftar'   => $pend->all($filter),
            'filter'   => $filter,
            'gelombang' => $gel->all(),
        );
        $this->view('admin/pendaftar', $data, 'layouts/dashboard');
    }

    /** Detail pendaftar */
    public function detail($id)
    {
        $pend = new Pendaftaran();
        $berkas = new Berkas();
        $gel = new Gelombang();
        $riwayat = new Riwayat();

        $p = $pend->find($id);
        if (!$p) {
            Session::flash('error', 'Data tidak ditemukan.');
            redirect('admin/pendaftar');
        }

        $data = array(
            'pendaftaran' => $p,
            'berkas'      => $berkas->forPendaftaran($id),
            'jenis_berkas' => jenis_berkas(),
            'gelombang'   => $gel->find($p['gelombang_id']),
            'riwayat'     => $riwayat->forPendaftaran($id),
        );
        $this->view('admin/detail', $data, 'layouts/dashboard');
    }

    /** Verifikasi berkas / pendaftaran */
    public function verifikasi($id)
    {
        $this->checkToken();
        $pend = new Pendaftaran();
        $p = $pend->find($id);
        if (!$p) {
            Session::flash('error', 'Data tidak ditemukan.');
            redirect('admin/pendaftar');
        }

        $aksi = input('aksi');
        $catatan = input('catatan') ?: null;
        $statusLama = $p['status'];

        if ($aksi === 'verifikasi') {
            $statusBaru = 'diverifikasi';
        } elseif ($aksi === 'tolak') {
            $statusBaru = 'ditolak';
        } else {
            Session::flash('error', 'Aksi tidak valid.');
            redirect('admin/pendaftar/' . $id);
        }

        $pend->setStatus($id, $statusBaru, $catatan);

        $riwayat = new Riwayat();
        $riwayat->create(array(
            'pendaftaran_id' => $id,
            'status_lama' => $statusLama,
            'status_baru' => $statusBaru,
            'oleh_user_id' => Auth::id(),
            'catatan' => $catatan,
        ));

        Session::flash('success', 'Status diperbarui menjadi ' . status_label($statusBaru) . '.');
        redirect('admin/pendaftar/' . $id);
    }

    /** Verifikasi per-berkas */
    public function verifikasiBerkas($id)
    {
        $this->checkToken();
        $berkas = new Berkas();
        $b = $berkas->find($id);
        if (!$b) {
            Session::flash('error', 'Berkas tidak ditemukan.');
            redirect('admin/pendaftar');
        }

        $status = input('status');
        if (!in_array($status, array('valid', 'tidak_valid', 'menunggu'))) {
            Session::flash('error', 'Status tidak valid.');
            redirect('admin/pendaftar/' . $b['pendaftaran_id']);
        }

        $berkas->updateStatus($id, $status, input('catatan') ?: null);
        Session::flash('success', 'Status berkas diperbarui.');
        redirect('admin/pendaftar/' . $b['pendaftaran_id']);
    }

    /** Unduh berkas */
    public function unduhBerkas($id)
    {
        $berkas = new Berkas();
        $b = $berkas->find($id);
        if (!$b) {
            http_response_code(404);
            echo 'Berkas tidak ditemukan.';
            return;
        }
        $upload = new Upload();
        $upload->download($b['path'], $b['nama_asli']);
    }

    // ============ GELOMBANG ============
    public function gelombang()
    {
        $gel = new Gelombang();
        $data = array('gelombang' => $gel->all());
        $this->view('admin/gelombang', $data, 'layouts/dashboard');
    }

    public function formGelombang()
    {
        $data = array('gelombang' => null);
        $this->view('admin/form_gelombang', $data, 'layouts/dashboard');
    }

    public function simpanGelombang()
    {
        $this->checkToken();
        $v = new Validator($_POST);
        $v->run(
            array(
                'nama'           => array('required', 'max:100'),
                'tahun_ajaran'   => array('required', 'max:20'),
                'jenis'          => array('required'),
                'tanggal_mulai'  => array('required', 'date'),
                'tanggal_selesai' => array('required', 'date'),
                'kuota'          => array('required', 'numeric'),
                'status'         => array('required', 'in:buka,tutup'),
            ),
            array(
                'nama' => 'Nama gelombang',
                'tahun_ajaran' => 'Tahun ajaran',
                'jenis' => 'Jenis/jalur',
                'tanggal_mulai' => 'Tanggal mulai',
                'tanggal_selesai' => 'Tanggal selesai',
                'kuota' => 'Kuota',
                'status' => 'Status',
            )
        );
        if ($v->fails()) {
            Session::set('_errors', $v->errors());
            Session::set('_old', $_POST);
            redirect('admin/gelombang/tambah');
        }

        $gel = new Gelombang();
        $gel->create(array(
            'nama' => input('nama'),
            'tahun_ajaran' => input('tahun_ajaran'),
            'jenis' => input('jenis'),
            'tanggal_mulai' => input('tanggal_mulai'),
            'tanggal_selesai' => input('tanggal_selesai'),
            'kuota' => (int) input('kuota'),
            'status' => input('status'),
            'deskripsi' => input('deskripsi') ?: null,
        ));

        Session::flash('success', 'Gelombang ditambahkan.');
        redirect('admin/gelombang');
    }

    public function editGelombang($id)
    {
        $gel = new Gelombang();
        $g = $gel->find($id);
        if (!$g) {
            Session::flash('error', 'Data tidak ditemukan.');
            redirect('admin/gelombang');
        }
        if (!Session::has('_old')) {
            Session::set('_old', $g);
        }
        $data = array('gelombang' => $g);
        $this->view('admin/form_gelombang', $data, 'layouts/dashboard');
    }

    public function updateGelombang($id)
    {
        $this->checkToken();
        $gel = new Gelombang();
        $g = $gel->find($id);
        if (!$g) {
            Session::flash('error', 'Data tidak ditemukan.');
            redirect('admin/gelombang');
        }

        $v = new Validator($_POST);
        $v->run(
            array(
                'nama'           => array('required', 'max:100'),
                'tahun_ajaran'   => array('required', 'max:20'),
                'jenis'          => array('required'),
                'tanggal_mulai'  => array('required', 'date'),
                'tanggal_selesai' => array('required', 'date'),
                'kuota'          => array('required', 'numeric'),
                'status'         => array('required', 'in:buka,tutup'),
            ),
            array(
                'nama' => 'Nama gelombang',
                'tahun_ajaran' => 'Tahun ajaran',
                'jenis' => 'Jenis/jalur',
                'tanggal_mulai' => 'Tanggal mulai',
                'tanggal_selesai' => 'Tanggal selesai',
                'kuota' => 'Kuota',
                'status' => 'Status',
            )
        );
        if ($v->fails()) {
            Session::set('_errors', $v->errors());
            Session::set('_old', $_POST);
            redirect('admin/gelombang/' . $id . '/edit');
        }

        $gel->update($id, array(
            'nama' => input('nama'),
            'tahun_ajaran' => input('tahun_ajaran'),
            'jenis' => input('jenis'),
            'tanggal_mulai' => input('tanggal_mulai'),
            'tanggal_selesai' => input('tanggal_selesai'),
            'kuota' => (int) input('kuota'),
            'status' => input('status'),
            'deskripsi' => input('deskripsi') ?: null,
        ));

        Session::flash('success', 'Gelombang diperbarui.');
        redirect('admin/gelombang');
    }

    public function hapusGelombang($id)
    {
        $this->checkToken();
        $gel = new Gelombang();
        $g = $gel->find($id);
        if (!$g) {
            Session::flash('error', 'Data tidak ditemukan.');
            redirect('admin/gelombang');
        }
        // Cek apakah ada pendaftar terkait
        if ($gel->countPendaftar($id) > 0) {
            Session::flash('error', 'Gelombang tidak dapat dihapus karena sudah ada pendaftar.');
            redirect('admin/gelombang');
        }
        $gel->delete($id);
        Session::flash('success', 'Gelombang dihapus.');
        redirect('admin/gelombang');
    }

    // ============ SELEKSI ============
    public function seleksi()
    {
        $pend = new Pendaftaran();
        $data = array(
            'daftar' => $pend->fetchAll(
                'SELECT p.*, u.nama AS nama_user, u.email AS email_user
                 FROM pendaftaran p LEFT JOIN users u ON u.id = p.user_id
                 WHERE p.status IN (?, ?) ORDER BY p.created_at ASC',
                array('diverifikasi', 'ditolak')
            ),
        );
        $this->view('admin/seleksi', $data, 'layouts/dashboard');
    }

    public function simpanSeleksi($id)
    {
        $this->checkToken();
        $pend = new Pendaftaran();
        $p = $pend->find($id);
        if (!$p) {
            Session::flash('error', 'Data tidak ditemukan.');
            redirect('admin/seleksi');
        }

        $hasil = input('hasil');
        if (!in_array($hasil, array('diterima', 'tidak_diterima'))) {
            Session::flash('error', 'Hasil tidak valid.');
            redirect('admin/seleksi');
        }

        $statusLama = $p['status'];
        $catatan = input('catatan') ?: null;
        $pend->setStatus($id, $hasil, $catatan);

        $riwayat = new Riwayat();
        $riwayat->create(array(
            'pendaftaran_id' => $id,
            'status_lama' => $statusLama,
            'status_baru' => $hasil,
            'oleh_user_id' => Auth::id(),
            'catatan' => $catatan,
        ));

        Session::flash('success', 'Hasil seleksi disimpan: ' . status_label($hasil) . '.');
        redirect('admin/seleksi');
    }

    // ============ PENGUMUMAN ============
    public function pengumuman()
    {
        $peng = new Pengumuman();
        $data = array(
            'pengumuman' => $peng->all(),
            'gelombang'  => (new Gelombang())->all(),
        );
        $this->view('admin/pengumuman', $data, 'layouts/dashboard');
    }

    public function formPengumuman()
    {
        $data = array(
            'pengumuman' => null,
            'gelombang'  => (new Gelombang())->all(),
        );
        $this->view('admin/form_pengumuman', $data, 'layouts/dashboard');
    }

    public function simpanPengumuman()
    {
        $this->checkToken();
        $v = new Validator($_POST);
        $v->run(
            array(
                'judul'   => array('required', 'max:150'),
                'isi'     => array('required'),
                'tanggal' => array('required', 'date'),
                'status'  => array('required', 'in:aktif,nonaktif'),
            ),
            array('judul' => 'Judul', 'isi' => 'Isi', 'tanggal' => 'Tanggal', 'status' => 'Status')
        );
        if ($v->fails()) {
            Session::set('_errors', $v->errors());
            Session::set('_old', $_POST);
            redirect('admin/pengumuman/tambah');
        }

        $peng = new Pengumuman();
        $peng->create(array(
            'gelombang_id' => input('gelombang_id') ?: null,
            'judul' => input('judul'),
            'isi' => input('isi'),
            'tanggal' => input('tanggal'),
            'status' => input('status'),
        ));

        Session::flash('success', 'Pengumuman ditambahkan.');
        redirect('admin/pengumuman');
    }

    public function editPengumuman($id)
    {
        $peng = new Pengumuman();
        $p = $peng->find($id);
        if (!$p) {
            Session::flash('error', 'Data tidak ditemukan.');
            redirect('admin/pengumuman');
        }
        if (!Session::has('_old')) {
            Session::set('_old', $p);
        }
        $data = array(
            'pengumuman' => $p,
            'gelombang'  => (new Gelombang())->all(),
        );
        $this->view('admin/form_pengumuman', $data, 'layouts/dashboard');
    }

    public function updatePengumuman($id)
    {
        $this->checkToken();
        $peng = new Pengumuman();
        $p = $peng->find($id);
        if (!$p) {
            Session::flash('error', 'Data tidak ditemukan.');
            redirect('admin/pengumuman');
        }

        $v = new Validator($_POST);
        $v->run(
            array(
                'judul'   => array('required', 'max:150'),
                'isi'     => array('required'),
                'tanggal' => array('required', 'date'),
                'status'  => array('required', 'in:aktif,nonaktif'),
            ),
            array('judul' => 'Judul', 'isi' => 'Isi', 'tanggal' => 'Tanggal', 'status' => 'Status')
        );
        if ($v->fails()) {
            Session::set('_errors', $v->errors());
            Session::set('_old', $_POST);
            redirect('admin/pengumuman/' . $id . '/edit');
        }

        $peng->update($id, array(
            'gelombang_id' => input('gelombang_id') ?: null,
            'judul' => input('judul'),
            'isi' => input('isi'),
            'tanggal' => input('tanggal'),
            'status' => input('status'),
        ));

        Session::flash('success', 'Pengumuman diperbarui.');
        redirect('admin/pengumuman');
    }

    public function hapusPengumuman($id)
    {
        $this->checkToken();
        $peng = new Pengumuman();
        $p = $peng->find($id);
        if (!$p) {
            Session::flash('error', 'Data tidak ditemukan.');
            redirect('admin/pengumuman');
        }
        $peng->delete($id);
        Session::flash('success', 'Pengumuman dihapus.');
        redirect('admin/pengumuman');
    }

    // ============ LAPORAN ============
    public function laporan()
    {
        $pend = new Pendaftaran();
        $tahun = query('tahun') ?: date('Y');
        $data = array(
            'stats'     => $pend->stats(),
            'per_status' => array(
                'draft'          => $pend->countByStatus('draft'),
                'dikirim'       => $pend->countByStatus('dikirim'),
                'diverifikasi'  => $pend->countByStatus('diverifikasi'),
                'ditolak'       => $pend->countByStatus('ditolak'),
                'diterima'      => $pend->countByStatus('diterima'),
                'tidak_diterima' => $pend->countByStatus('tidak_diterima'),
            ),
            'per_gelombang' => $pend->perGelombang(),
            'per_bulan' => $pend->perBulan($tahun),
            'tahun'     => $tahun,
        );
        $this->view('admin/laporan', $data, 'layouts/dashboard');
    }

    /** Ekspor CSV (mengikuti filter yang aktif di halaman Data Pendaftar) */
    public function exportCsv()
    {
        $pend = new Pendaftaran();
        $rows = $pend->all(array(
            'status'       => query('status'),
            'gelombang_id' => query('gelombang'),
            'cari'         => query('cari'),
            'hide_draft'   => query('hide_draft') === '1',
        ));

        $header = array(
            'No', 'No. Pendaftaran', 'Nama Siswa', 'Jenis Kelamin',
            'Tempat Lahir', 'Tanggal Lahir', 'Agama', 'Anak Ke',
            'Nama Ayah', 'Pekerjaan Ayah', 'HP Ayah',
            'Nama Ibu', 'Pekerjaan Ibu', 'HP Ibu',
            'Asal Sekolah', 'Alamat', 'Kelurahan', 'Kecamatan',
            'Kota', 'Provinsi', 'Status', 'Tanggal Daftar',
        );

        $out = "\xEF\xBB\xBF"; // BOM agar Excel terbaca UTF-8
        $out .= implode(';', $header) . "\n";

        $no = 1;
        foreach ($rows as $r) {
            $line = array(
                $no++,
                $r['no_pendaftaran'],
                $r['nama_lengkap'],
                $r['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan',
                $r['tempat_lahir'],
                $r['tanggal_lahir'],
                $r['agama'],
                $r['anak_ke'],
                $r['nama_ayah'],
                $r['pekerjaan_ayah'],
                $r['hp_ayah'],
                $r['nama_ibu'],
                $r['pekerjaan_ibu'],
                $r['hp_ibu'],
                $r['asal_sekolah'],
                str_replace(array("\r", "\n"), ' ', $r['alamat']),
                $r['kelurahan'],
                $r['kecamatan'],
                $r['kota'],
                $r['provinsi'],
                status_label($r['status']),
                $r['tanggal_daftar'],
            );
            $out .= implode(';', array_map(array($this, 'csvCell'), $line)) . "\n";
        }

        $filename = 'laporan-psb-' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo $out;
        exit;
    }

    // ============ KELOLA PENGGUNA ============
    /** Daftar akun + persetujuan akun wali murid */
    public function pengguna()
    {
        $user = new User();
        $filter = array(
            'cari'   => query('cari'),
            'status' => query('status'),
            'role'   => query('role'),
        );

        $roleLabel = array(
            'wali_murid' => 'Wali Murid',
            'admin'      => 'Admin / Panitia',
            'kepsek'     => 'Kepala Sekolah',
        );

        $data = array(
            'daftar'    => $user->listUsers($filter),
            'filter'    => $filter,
            'menunggu'  => $user->countPending(),
            'roleLabel' => $roleLabel,
        );
        $this->view('admin/pengguna', $data, 'layouts/dashboard');
    }

    /** Setujui (aktifkan) akun */
    public function aktifkanAkun($id)
    {
        $this->checkToken();
        $user = new User();
        $u = $user->find($id);

        if (!$u) {
            Session::flash('error', 'Akun tidak ditemukan.');
            redirect('admin/pengguna');
        }
        if ($u['aktif']) {
            Session::flash('info', 'Akun tersebut sudah aktif.');
            redirect('admin/pengguna');
        }

        $user->setAktif($id, 1);
        Session::flash('success', 'Akun <b>' . e($u['nama']) . '</b> (' . e($u['email']) . ') disetujui dan sudah bisa login.');
        redirect('admin/pengguna');
    }

    /** Nonaktifkan akun */
    public function nonaktifkanAkun($id)
    {
        $this->checkToken();
        $user = new User();
        $u = $user->find($id);

        if (!$u) {
            Session::flash('error', 'Akun tidak ditemukan.');
            redirect('admin/pengguna');
        }
        if ((int) $u['id'] === (int) Auth::id()) {
            Session::flash('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
            redirect('admin/pengguna');
        }
        if (!$u['aktif']) {
            Session::flash('info', 'Akun tersebut sudah nonaktif.');
            redirect('admin/pengguna');
        }

        $user->setAktif($id, 0);
        Session::flash('success', 'Akun <b>' . e($u['nama']) . '</b> dinonaktifkan dan tidak bisa login.');
        redirect('admin/pengguna');
    }

    /** Hapus akun */
    public function hapusAkun($id)
    {
        $this->checkToken();
        $user = new User();
        $u = $user->find($id);

        if (!$u) {
            Session::flash('error', 'Akun tidak ditemukan.');
            redirect('admin/pengguna');
        }
        if ((int) $u['id'] === (int) Auth::id()) {
            Session::flash('error', 'Anda tidak dapat menghapus akun sendiri.');
            redirect('admin/pengguna');
        }
        if ($u['role'] === 'admin' || $u['role'] === 'kepsek') {
            Session::flash('error', 'Akun panitia/kepala sekolah tidak dapat dihapus dari menu ini.');
            redirect('admin/pengguna');
        }

        $jml = $user->countPendaftaran($id);
        if ($jml > 0) {
            Session::flash('error', 'Akun ini memiliki <b>' . $jml . ' data pendaftaran</b>. '
                . 'Hapus atau nonaktifkan saja agar riwayat pendaftaran tetap tersimpan.');
            redirect('admin/pengguna');
        }

        $user->delete($id);
        Session::flash('success', 'Akun <b>' . e($u['nama']) . '</b> (' . e($u['email']) . ') dihapus.');
        redirect('admin/pengguna');
    }

    protected function csvCell($value)
    {
        $value = (string) $value;
        if (preg_match('/[;"\n\r]/', $value)) {
            return '"' . str_replace('"', '""', $value) . '"';
        }
        return $value;
    }
}
