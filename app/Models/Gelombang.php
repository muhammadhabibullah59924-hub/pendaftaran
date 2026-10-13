<?php
/**
 * Model Gelombang (periode / jalur pendaftaran)
 */
class Gelombang extends Model
{
    public function all()
    {
        return $this->fetchAll('SELECT * FROM gelombang ORDER BY tanggal_mulai DESC');
    }

    public function find($id)
    {
        return $this->fetchOne('SELECT * FROM gelombang WHERE id = ? LIMIT 1', array($id));
    }

    /** Gelombang yang sedang aktif (status buka & dalam rentang tanggal) */
    public function aktif()
    {
        $today = date('Y-m-d');
        return $this->fetchAll(
            "SELECT * FROM gelombang
             WHERE status = 'buka' AND tanggal_mulai <= ? AND tanggal_selesai >= ?
             ORDER BY tanggal_selesai ASC",
            array($today, $today)
        );
    }

    public function create($data)
    {
        $this->execute(
            'INSERT INTO gelombang (nama, tahun_ajaran, jenis, tanggal_mulai, tanggal_selesai, kuota, status, deskripsi, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())',
            array(
                $data['nama'], $data['tahun_ajaran'], $data['jenis'],
                $data['tanggal_mulai'], $data['tanggal_selesai'],
                $data['kuota'], $data['status'], $data['deskripsi'],
            )
        );
        return $this->lastId();
    }

    public function update($id, $data)
    {
        return $this->execute(
            'UPDATE gelombang SET nama=?, tahun_ajaran=?, jenis=?, tanggal_mulai=?, tanggal_selesai=?, kuota=?, status=?, deskripsi=? WHERE id=?',
            array(
                $data['nama'], $data['tahun_ajaran'], $data['jenis'],
                $data['tanggal_mulai'], $data['tanggal_selesai'],
                $data['kuota'], $data['status'], $data['deskripsi'], $id,
            )
        );
    }

    public function delete($id)
    {
        return $this->execute('DELETE FROM gelombang WHERE id = ?', array($id));
    }

    /** Jumlah pendaftar pada gelombang tertentu */
    public function countPendaftar($id)
    {
        return $this->count('SELECT COUNT(*) FROM pendaftaran WHERE gelombang_id = ? AND status != ?', array($id, 'draft'));
    }

    /** Sisa kuota */
    public function sisaKuota($gelombang)
    {
        if (!$gelombang) {
            return 0;
        }
        $masuk = $this->countPendaftar($gelombang['id']);
        $sisa = (int) $gelombang['kuota'] - $masuk;
        return $sisa < 0 ? 0 : $sisa;
    }
}
