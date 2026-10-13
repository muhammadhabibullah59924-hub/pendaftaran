<?php
/**
 * Model Pendaftaran
 */
class Pendaftaran extends Model
{
    /** Nomor pendaftaran otomatis & unik */
    public function generateNo()
    {
        $year = date('Y');
        $n = $this->count('SELECT COUNT(*) FROM pendaftaran WHERE YEAR(created_at) = ?', array($year));
        $seq = $n + 1;
        do {
            $no = 'PSB-' . $year . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
            $seq++;
        } while ($this->findByNo($no));
        return $no;
    }

    public function create($data)
    {
        $this->execute(
            'INSERT INTO pendaftaran (
                user_id, gelombang_id, no_pendaftaran,
                nama_lengkap, nama_panggilan, jenis_kelamin, tempat_lahir, tanggal_lahir,
                agama, kewarganegaraan, anak_ke, jumlah_saudara, nisn,
                nama_ayah, pendidikan_ayah, pekerjaan_ayah, hp_ayah,
                nama_ibu, pendidikan_ibu, pekerjaan_ibu, hp_ibu,
                nama_wali, hubungan_wali, hp_wali,
                asal_sekolah, npsn_asal, tahun_lulus,
                alamat, rt_rw, kelurahan, kecamatan, kota, provinsi, kode_pos,
                status, catatan_admin, tanggal_daftar, created_at
            ) VALUES (
                ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?,
                ?, ?, ?, ?,
                ?, ?, ?,
                ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, NOW()
            )',
            array(
                $data['user_id'], $data['gelombang_id'], $data['no_pendaftaran'],
                $data['nama_lengkap'], $data['nama_panggilan'], $data['jenis_kelamin'],
                $data['tempat_lahir'], $data['tanggal_lahir'],
                $data['agama'], $data['kewarganegaraan'], $data['anak_ke'],
                $data['jumlah_saudara'], $data['nisn'],
                $data['nama_ayah'], $data['pendidikan_ayah'], $data['pekerjaan_ayah'], $data['hp_ayah'],
                $data['nama_ibu'], $data['pendidikan_ibu'], $data['pekerjaan_ibu'], $data['hp_ibu'],
                $data['nama_wali'], $data['hubungan_wali'], $data['hp_wali'],
                $data['asal_sekolah'], $data['npsn_asal'], $data['tahun_lulus'],
                $data['alamat'], $data['rt_rw'], $data['kelurahan'], $data['kecamatan'],
                $data['kota'], $data['provinsi'], $data['kode_pos'],
                $data['status'], $data['catatan_admin'], $data['tanggal_daftar'],
            )
        );
        return $this->lastId();
    }

    public function updateData($id, $data)
    {
        $this->execute(
            'UPDATE pendaftaran SET
                gelombang_id=?,
                nama_lengkap=?, nama_panggilan=?, jenis_kelamin=?, tempat_lahir=?, tanggal_lahir=?,
                agama=?, kewarganegaraan=?, anak_ke=?, jumlah_saudara=?, nisn=?,
                nama_ayah=?, pendidikan_ayah=?, pekerjaan_ayah=?, hp_ayah=?,
                nama_ibu=?, pendidikan_ibu=?, pekerjaan_ibu=?, hp_ibu=?,
                nama_wali=?, hubungan_wali=?, hp_wali=?,
                asal_sekolah=?, npsn_asal=?, tahun_lulus=?,
                alamat=?, rt_rw=?, kelurahan=?, kecamatan=?, kota=?, provinsi=?, kode_pos=?,
                updated_at=NOW()
             WHERE id=?',
            array(
                $data['gelombang_id'],
                $data['nama_lengkap'], $data['nama_panggilan'], $data['jenis_kelamin'],
                $data['tempat_lahir'], $data['tanggal_lahir'],
                $data['agama'], $data['kewarganegaraan'], $data['anak_ke'],
                $data['jumlah_saudara'], $data['nisn'],
                $data['nama_ayah'], $data['pendidikan_ayah'], $data['pekerjaan_ayah'], $data['hp_ayah'],
                $data['nama_ibu'], $data['pendidikan_ibu'], $data['pekerjaan_ibu'], $data['hp_ibu'],
                $data['nama_wali'], $data['hubungan_wali'], $data['hp_wali'],
                $data['asal_sekolah'], $data['npsn_asal'], $data['tahun_lulus'],
                $data['alamat'], $data['rt_rw'], $data['kelurahan'], $data['kecamatan'],
                $data['kota'], $data['provinsi'], $data['kode_pos'],
                $id,
            )
        );
    }

    public function find($id)
    {
        return $this->fetchOne('SELECT * FROM pendaftaran WHERE id = ? LIMIT 1', array($id));
    }

    public function findByNo($no)
    {
        return $this->fetchOne('SELECT * FROM pendaftaran WHERE no_pendaftaran = ? LIMIT 1', array($no));
    }

    /** Pendaftaran milik user tertentu (termasuk draft) */
    public function findByUser($userId)
    {
        return $this->fetchOne(
            'SELECT * FROM pendaftaran WHERE user_id = ? ORDER BY id DESC LIMIT 1',
            array($userId)
        );
    }

    /** Daftar pendaftaran (dengan nama user), bisa difilter */
    public function all($filter = array())
    {
        $sql = 'SELECT p.*, u.nama AS nama_user, u.email AS email_user
                FROM pendaftaran p
                LEFT JOIN users u ON u.id = p.user_id
                WHERE 1=1';
        $params = array();

        // Sembunyikan draft hanya jika diminta eksplisit
        if (!empty($filter['hide_draft'])) {
            $sql .= ' AND p.status != ?';
            $params[] = 'draft';
        }
        if (!empty($filter['status'])) {
            $sql .= ' AND p.status = ?';
            $params[] = $filter['status'];
        }
        if (!empty($filter['gelombang_id'])) {
            $sql .= ' AND p.gelombang_id = ?';
            $params[] = $filter['gelombang_id'];
        }
        if (!empty($filter['cari'])) {
            $sql .= ' AND (p.nama_lengkap LIKE ? OR p.no_pendaftaran LIKE ?)';
            $like = '%' . $filter['cari'] . '%';
            $params[] = $like;
            $params[] = $like;
        }

        $sql .= ' ORDER BY p.created_at DESC';
        return $this->fetchAll($sql, $params);
    }

    /** Pendaftaran milik user, tampil di dashboard wali murid */
    public function riwayatUser($userId)
    {
        return $this->fetchAll(
            'SELECT * FROM pendaftaran WHERE user_id = ? ORDER BY id DESC',
            array($userId)
        );
    }

    /** Pendaftar terbaru (semua status, termasuk draft) */
    public function terbaru($limit = 8)
    {
        return $this->fetchAll(
            'SELECT p.*, u.nama AS nama_user FROM pendaftaran p
             LEFT JOIN users u ON u.id = p.user_id
             ORDER BY p.created_at DESC LIMIT ?',
            array($limit)
        );
    }

    /** Ubah status + catatan */
    public function setStatus($id, $status, $catatan = null, $tanggalDaftar = null)
    {
        $data = array('status' => $status, 'updated_at' => 'NOW()');
        // Bangun query manual agar NOW() bisa dipakai
        $sets = 'status = ?, catatan_admin = ?, updated_at = NOW()';
        $params = array($status, $catatan);
        if ($tanggalDaftar !== null) {
            $sets .= ', tanggal_daftar = ?';
            $params[] = $tanggalDaftar;
        }
        $params[] = $id;
        return $this->execute('UPDATE pendaftaran SET ' . $sets . ' WHERE id = ?', $params);
    }

    /** Statistik ringkas */
    public function stats()
    {
        $total = $this->count('SELECT COUNT(*) FROM pendaftaran WHERE status != ?', array('draft'));
        $laki = $this->count("SELECT COUNT(*) FROM pendaftaran WHERE jenis_kelamin = 'L' AND status != ?", array('draft'));
        $perempuan = $this->count("SELECT COUNT(*) FROM pendaftaran WHERE jenis_kelamin = 'P' AND status != ?", array('draft'));
        $diterima = $this->countByStatus('diterima');
        $draft = $this->countByStatus('draft');
        return array(
            'total' => $total,
            'laki' => $laki,
            'perempuan' => $perempuan,
            'diterima' => $diterima,
            'draft' => $draft,
        );
    }

    public function countByStatus($status)
    {
        return $this->count('SELECT COUNT(*) FROM pendaftaran WHERE status = ?', array($status));
    }

    /** Rekap per gelombang */
    public function perGelombang()
    {
        return $this->fetchAll(
            'SELECT g.nama, g.tahun_ajaran,
                    COUNT(p.id) AS jumlah,
                    SUM(CASE WHEN p.status = ? THEN 1 ELSE 0 END) AS diterima
             FROM gelombang g
             LEFT JOIN pendaftaran p ON p.gelombang_id = g.id AND p.status != ?
             GROUP BY g.id
             ORDER BY g.tanggal_mulai DESC',
            array('diterima', 'draft')
        );
    }

    /** Rekap per bulan pendaftaran */
    public function perBulan($tahun)
    {
        return $this->fetchAll(
            "SELECT MONTH(tanggal_daftar) AS bulan, COUNT(*) AS jumlah
             FROM pendaftaran
             WHERE tanggal_daftar IS NOT NULL AND YEAR(tanggal_daftar) = ? AND status != ?
             GROUP BY MONTH(tanggal_daftar)
             ORDER BY bulan ASC",
            array($tahun, 'draft')
        );
    }
}
