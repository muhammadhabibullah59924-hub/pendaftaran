<?php
/**
 * Controller pendaftaran untuk wali murid
 */
class PendaftaranController extends Controller
{
    /** Dashboard wali murid */
    public function dashboard()
    {
        $pend = new Pendaftaran();
        $berkas = new Berkas();
        $gelombang = new Gelombang();

        $dataPendaftaran = $pend->findByUser(Auth::id());
        $daftarBerkas = $dataPendaftaran ? $berkas->forPendaftaran($dataPendaftaran['id']) : array();
        $belum = $dataPendaftaran ? $berkas->belumDiunggah($dataPendaftaran['id']) : array();

        $data = array(
            'pendaftaran' => $dataPendaftaran,
            'berkas'      => $daftarBerkas,
            'belum'       => $belum,
            'jenis_berkas' => jenis_berkas(),
            'gelombang'   => $gelombang->aktif(),
            'riwayat'     => $dataPendaftaran ? (new Riwayat())->forPendaftaran($dataPendaftaran['id']) : array(),
        );
        $this->view('pendaftar/dashboard', $data, 'layouts/dashboard');
    }

    /** Formulir pendaftaran */
    public function form()
    {
        $pend = new Pendaftaran();
        $gelombang = new Gelombang();

        $existing = $pend->findByUser(Auth::id());
        $aktif = $gelombang->aktif();

        if (empty($aktif)) {
            Session::flash('error', 'Belum ada gelombang pendaftaran yang dibuka.');
            redirect('dashboard');
        }

        // Sudah dikirim? tidak boleh edit lagi
        if ($existing && $existing['status'] !== 'draft') {
            Session::flash('info', 'Pendaftaran Anda sudah dikirim. Tidak dapat diubah.');
            redirect('pendaftaran/status');
        }

        // Isi otomatis formulir dengan data yang sudah ada (jika ada)
        if ($existing && !Session::has('_old')) {
            Session::set('_old', $existing);
        }

        $data = array(
            'pendaftaran' => $existing,
            'gelombang'   => $aktif,
        );
        $this->view('pendaftar/form', $data, 'layouts/dashboard');
    }

    /** Simpan (buat / perbarui draft) */
    public function simpan()
    {
        $this->checkToken();

        $gelombang = new Gelombang();
        $aktif = $gelombang->aktif();
        $idsAktif = array();
        foreach ($aktif as $g) {
            $idsAktif[] = $g['id'];
        }

        $rules = array(
            'nama_lengkap'    => array('required', 'max:100'),
            'jenis_kelamin'   => array('required', 'in:L,P'),
            'tempat_lahir'    => array('required', 'max:100'),
            'tanggal_lahir'   => array('required', 'date'),
            'agama'           => array('required'),
            'anak_ke'         => array('required', 'numeric'),
            'jumlah_saudara'  => array('required', 'numeric'),
            'nama_ayah'       => array('required', 'max:100'),
            'pekerjaan_ayah'  => array('required'),
            'hp_ayah'         => array('required', 'max:20'),
            'nama_ibu'        => array('required', 'max:100'),
            'pekerjaan_ibu'   => array('required'),
            'hp_ibu'          => array('required', 'max:20'),
            'asal_sekolah'    => array('required', 'max:100'),
            'alamat'          => array('required'),
            'kelurahan'       => array('required'),
            'kecamatan'       => array('required'),
            'kota'            => array('required'),
            'provinsi'        => array('required'),
            'kode_pos'        => array('required', 'max:10'),
            'gelombang_id'    => array('required', 'numeric'),
        );
        $rules['gelombang_id'] = array('required', 'numeric', 'in:' . implode(',', $idsAktif));

        $labels = array(
            'nama_lengkap' => 'Nama lengkap anak',
            'jenis_kelamin' => 'Jenis kelamin',
            'tempat_lahir' => 'Tempat lahir',
            'tanggal_lahir' => 'Tanggal lahir',
            'agama' => 'Agama',
            'anak_ke' => 'Anak ke-',
            'jumlah_saudara' => 'Jumlah saudara',
            'nama_ayah' => 'Nama ayah',
            'pekerjaan_ayah' => 'Pekerjaan ayah',
            'hp_ayah' => 'No. HP ayah',
            'nama_ibu' => 'Nama ibu',
            'pekerjaan_ibu' => 'Pekerjaan ibu',
            'hp_ibu' => 'No. HP ibu',
            'asal_sekolah' => 'Asal TK/RA',
            'alamat' => 'Alamat',
            'kelurahan' => 'Kelurahan/Desa',
            'kecamatan' => 'Kecamatan',
            'kota' => 'Kota/Kabupaten',
            'provinsi' => 'Provinsi',
            'kode_pos' => 'Kode pos',
            'gelombang_id' => 'Gelombang',
        );

        $v = new Validator($_POST);
        $v->run($rules, $labels);
        if ($v->fails()) {
            Session::set('_errors', $v->errors());
            Session::set('_old', $_POST);
            redirect('pendaftaran');
        }

        $dataPend = $this->dataFromInput();
        $pend = new Pendaftaran();
        $existing = $pend->findByUser(Auth::id());

        if ($existing) {
            $pend->updateData($existing['id'], $dataPend);
            $pendId = $existing['id'];
            $noPendaftaran = $existing['no_pendaftaran'];
        } else {
            $dataPend['user_id'] = Auth::id();
            $dataPend['no_pendaftaran'] = $pend->generateNo();
            $dataPend['status'] = 'draft';
            $dataPend['catatan_admin'] = null;
            $dataPend['tanggal_daftar'] = null;
            $pendId = $pend->create($dataPend);
            $noPendaftaran = $dataPend['no_pendaftaran'];
        }

        Session::flash('success', 'Data pendaftaran tersimpan. Nomor pendaftaran: <b>' . e($noPendaftaran) . '</b>. Silakan unggah berkas.');
        redirect('pendaftaran/berkas');
    }

    /** Halaman unggah berkas */
    public function berkas()
    {
        $pend = new Pendaftaran();
        $existing = $pend->findByUser(Auth::id());

        if (!$existing) {
            Session::flash('error', 'Isi formulir pendaftaran terlebih dahulu.');
            redirect('pendaftaran');
        }
        if ($existing['status'] !== 'draft') {
            redirect('pendaftaran/status');
        }

        $berkas = new Berkas();
        $data = array(
            'pendaftaran' => $existing,
            'berkas'      => $berkas->forPendaftaran($existing['id']),
            'jenis_berkas' => jenis_berkas(),
            'belum'       => $berkas->belumDiunggah($existing['id']),
        );
        $this->view('pendaftar/berkas', $data, 'layouts/dashboard');
    }

    /** Proses unggah berkas */
    public function unggahBerkas()
    {
        $this->checkToken();

        $pend = new Pendaftaran();
        $existing = $pend->findByUser(Auth::id());
        if (!$existing || $existing['status'] !== 'draft') {
            Session::flash('error', 'Tidak dapat mengunggah berkas.');
            redirect('pendaftaran/berkas');
        }

        $jenis = input('jenis');
        $wajib = array_keys(jenis_berkas());
        if (!in_array($jenis, $wajib)) {
            Session::flash('error', 'Jenis berkas tidak valid.');
            redirect('pendaftaran/berkas');
        }

        $berkas = new Berkas();
        $upload = new Upload();

        try {
            $result = $upload->save($_FILES['berkas'], $existing['id']);

            // Ganti berkas lama jika sudah ada
            $lama = $berkas->findByPendaftaranDanJenis($existing['id'], $jenis);
            if ($lama) {
                $upload->delete($lama['path']);
                $berkas->updateStatus($lama['id'], 'menunggu', null);
                // perbarui path & nama
                $berkas->execute(
                    'UPDATE berkas SET nama_asli=?, path=?, uploaded_at=NOW() WHERE id=?',
                    array($result['nama_asli'], $result['path'], $lama['id'])
                );
            } else {
                $berkas->create(array(
                    'pendaftaran_id' => $existing['id'],
                    'jenis' => $jenis,
                    'nama_asli' => $result['nama_asli'],
                    'path' => $result['path'],
                    'status' => 'menunggu',
                ));
            }

            Session::flash('success', 'Berkas berhasil diunggah.');
        } catch (Exception $ex) {
            Session::flash('error', $ex->getMessage());
        }

        redirect('pendaftaran/berkas');
    }

    /** Hapus berkas */
    public function hapusBerkas($id)
    {
        $this->checkToken();
        $berkas = new Berkas();
        $b = $berkas->find($id);
        $pend = new Pendaftaran();
        $existing = $pend->findByUser(Auth::id());

        if (!$b || !$existing || $b['pendaftaran_id'] != $existing['id'] || $existing['status'] !== 'draft') {
            Session::flash('error', 'Berkas tidak dapat dihapus.');
            redirect('pendaftaran/berkas');
        }

        $berkas->delete($id);
        Session::flash('success', 'Berkas dihapus.');
        redirect('pendaftaran/berkas');
    }

    /** Kirim pendaftaran (draft -> dikirim) */
    public function kirim()
    {
        $this->checkToken();

        $pend = new Pendaftaran();
        $existing = $pend->findByUser(Auth::id());

        if (!$existing || $existing['status'] !== 'draft') {
            Session::flash('error', 'Pendaftaran tidak ditemukan.');
            redirect('pendaftaran');
        }

        $berkas = new Berkas();
        $belum = $berkas->belumDiunggah($existing['id']);
        if (!empty($belum)) {
            $nama = array();
            foreach ($belum as $j) {
                $nama[] = jenis_berkas()[$j];
            }
            Session::flash('error', 'Masih ada berkas wajib yang belum diunggah: ' . implode(', ', $nama) . '.');
            redirect('pendaftaran/berkas');
        }

        $lamaStatus = $existing['status'];
        $pend->setStatus($existing['id'], 'dikirim', null, date('Y-m-d H:i:s'));

        $riwayat = new Riwayat();
        $riwayat->create(array(
            'pendaftaran_id' => $existing['id'],
            'status_lama' => $lamaStatus,
            'status_baru' => 'dikirim',
            'oleh_user_id' => Auth::id(),
            'catatan' => 'Pendaftaran dikirim oleh wali murid.',
        ));

        Session::flash('success', 'Pendaftaran berhasil dikirim. Nomor: <b>' . e($existing['no_pendaftaran']) . '</b>. Tunggu verifikasi panitia.');
        redirect('pendaftaran/status');
    }

    /** Halaman status */
    public function status()
    {
        $pend = new Pendaftaran();
        $berkas = new Berkas();
        $existing = $pend->findByUser(Auth::id());

        if (!$existing) {
            Session::flash('info', 'Anda belum mendaftar.');
            redirect('pendaftaran');
        }

        $data = array(
            'pendaftaran' => $existing,
            'berkas'      => $berkas->forPendaftaran($existing['id']),
            'jenis_berkas' => jenis_berkas(),
            'riwayat'     => (new Riwayat())->forPendaftaran($existing['id']),
        );
        $this->view('pendaftar/status', $data, 'layouts/dashboard');
    }

    /** Cetak bukti pendaftaran */
    public function bukti()
    {
        $pend = new Pendaftaran();
        $berkas = new Berkas();
        $gel = new Gelombang();
        $existing = $pend->findByUser(Auth::id());

        if (!$existing) {
            Session::flash('error', 'Belum ada pendaftaran.');
            redirect('dashboard');
        }

        $data = array(
            'pendaftaran' => $existing,
            'berkas'      => $berkas->forPendaftaran($existing['id']),
            'jenis_berkas' => jenis_berkas(),
            'gelombang'   => $gel->find($existing['gelombang_id']),
        );
        $this->view('pendaftar/bukti', $data, 'layouts/print');
    }

    /** Cek status tanpa login (umum) */
    public function cekStatus()
    {
        $hasil = null;
        $nomor = '';
        if (method() === 'POST') {
            $this->checkToken();
            $nomor = input('no_pendaftaran');
            $pend = new Pendaftaran();
            $hasil = $pend->findByNo($nomor);
            if (!$hasil) {
                Session::flash('error', 'Nomor pendaftaran tidak ditemukan.');
            }
        }
        $data = array('hasil' => $hasil, 'nomor' => $nomor);
        $this->view('pendaftar/cek', $data, 'layouts/public');
    }

    /** Kumpulkan data formulir menjadi array */
    protected function dataFromInput()
    {
        return array(
            'gelombang_id'     => (int) input('gelombang_id'),
            'nama_lengkap'     => input('nama_lengkap'),
            'nama_panggilan'   => input('nama_panggilan') ?: null,
            'jenis_kelamin'    => input('jenis_kelamin'),
            'tempat_lahir'     => input('tempat_lahir'),
            'tanggal_lahir'    => input('tanggal_lahir'),
            'agama'            => input('agama'),
            'kewarganegaraan'  => input('kewarganegaraan') ?: 'Indonesia',
            'anak_ke'          => (int) input('anak_ke'),
            'jumlah_saudara'   => (int) input('jumlah_saudara'),
            'nisn'             => input('nisn') ?: null,
            'nama_ayah'        => input('nama_ayah'),
            'pendidikan_ayah'  => input('pendidikan_ayah') ?: null,
            'pekerjaan_ayah'   => input('pekerjaan_ayah'),
            'hp_ayah'          => input('hp_ayah'),
            'nama_ibu'         => input('nama_ibu'),
            'pendidikan_ibu'   => input('pendidikan_ibu') ?: null,
            'pekerjaan_ibu'    => input('pekerjaan_ibu'),
            'hp_ibu'           => input('hp_ibu'),
            'nama_wali'        => input('nama_wali') ?: null,
            'hubungan_wali'    => input('hubungan_wali') ?: null,
            'hp_wali'          => input('hp_wali') ?: null,
            'asal_sekolah'     => input('asal_sekolah'),
            'npsn_asal'        => input('npsn_asal') ?: null,
            'tahun_lulus'      => input('tahun_lulus') ?: null,
            'alamat'           => input('alamat'),
            'rt_rw'            => input('rt_rw') ?: null,
            'kelurahan'        => input('kelurahan'),
            'kecamatan'        => input('kecamatan'),
            'kota'             => input('kota'),
            'provinsi'         => input('provinsi'),
            'kode_pos'         => input('kode_pos'),
        );
    }
}
