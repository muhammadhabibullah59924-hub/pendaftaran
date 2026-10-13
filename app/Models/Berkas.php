<?php
/**
 * Model Berkas
 */
class Berkas extends Model
{
    public function forPendaftaran($pendaftaranId)
    {
        return $this->fetchAll(
            'SELECT * FROM berkas WHERE pendaftaran_id = ? ORDER BY jenis ASC',
            array($pendaftaranId)
        );
    }

    public function find($id)
    {
        return $this->fetchOne('SELECT * FROM berkas WHERE id = ? LIMIT 1', array($id));
    }

    public function findByPendaftaranDanJenis($pendaftaranId, $jenis)
    {
        return $this->fetchOne(
            'SELECT * FROM berkas WHERE pendaftaran_id = ? AND jenis = ? LIMIT 1',
            array($pendaftaranId, $jenis)
        );
    }

    public function create($data)
    {
        $this->execute(
            'INSERT INTO berkas (pendaftaran_id, jenis, nama_asli, path, status, catatan, uploaded_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW())',
            array(
                $data['pendaftaran_id'], $data['jenis'], $data['nama_asli'],
                $data['path'], isset($data['status']) ? $data['status'] : 'menunggu',
                isset($data['catatan']) ? $data['catatan'] : null,
            )
        );
        return $this->lastId();
    }

    public function updateStatus($id, $status, $catatan = null)
    {
        return $this->execute(
            'UPDATE berkas SET status = ?, catatan = ? WHERE id = ?',
            array($status, $catatan, $id)
        );
    }

    public function delete($id)
    {
        $b = $this->find($id);
        if ($b) {
            $up = new Upload();
            $up->delete($b['path']);
        }
        return $this->execute('DELETE FROM berkas WHERE id = ?', array($id));
    }

    /** Jenis berkas yang belum diunggah */
    public function belumDiunggah($pendaftaranId)
    {
        $wajib = array_keys(jenis_berkas());
        $sudah = array();
        foreach ($this->forPendaftaran($pendaftaranId) as $b) {
            $sudah[] = $b['jenis'];
        }
        return array_diff($wajib, $sudah);
    }
}
