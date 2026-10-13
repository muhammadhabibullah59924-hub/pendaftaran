<?php
/**
 * Controller Kepala Sekolah (read-only)
 */
class PimpinanController extends Controller
{
    public function dashboard()
    {
        $pend = new Pendaftaran();
        $tahun = query('tahun') ?: date('Y');

        $data = array(
            'stats'     => $pend->stats(),
            'per_status' => array(
                'dikirim'       => $pend->countByStatus('dikirim'),
                'diverifikasi'  => $pend->countByStatus('diverifikasi'),
                'ditolak'       => $pend->countByStatus('ditolak'),
                'diterima'      => $pend->countByStatus('diterima'),
                'tidak_diterima' => $pend->countByStatus('tidak_diterima'),
            ),
            'per_gelombang' => $pend->perGelombang(),
            'per_bulan' => $pend->perBulan($tahun),
            'tahun'     => $tahun,
            'terbaru'   => array_values(array_filter(
                (new Pendaftaran())->terbaru(8),
                function ($p) { return $p['status'] !== 'draft'; }
            )),
            'pengumuman' => (new Pengumuman())->aktif(),
        );
        $this->view('pimpinan/dashboard', $data, 'layouts/dashboard');
    }
}
