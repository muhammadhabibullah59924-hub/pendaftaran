<?php
/**
 * Model Pengumuman
 */
class Pengumuman extends Model
{
    public function all()
    {
        return $this->fetchAll(
            'SELECT * FROM pengumuman ORDER BY tanggal DESC, id DESC'
        );
    }

    public function aktif()
    {
        return $this->fetchAll(
            "SELECT * FROM pengumuman WHERE status = 'aktif' ORDER BY tanggal DESC, id DESC"
        );
    }

    public function find($id)
    {
        return $this->fetchOne('SELECT * FROM pengumuman WHERE id = ? LIMIT 1', array($id));
    }

    public function create($data)
    {
        $this->execute(
            'INSERT INTO pengumuman (gelombang_id, judul, isi, tanggal, status, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())',
            array(
                $data['gelombang_id'], $data['judul'], $data['isi'],
                $data['tanggal'], $data['status'],
            )
        );
        return $this->lastId();
    }

    public function update($id, $data)
    {
        return $this->execute(
            'UPDATE pengumuman SET gelombang_id=?, judul=?, isi=?, tanggal=?, status=? WHERE id=?',
            array($data['gelombang_id'], $data['judul'], $data['isi'], $data['tanggal'], $data['status'], $id)
        );
    }

    public function delete($id)
    {
        return $this->execute('DELETE FROM pengumuman WHERE id = ?', array($id));
    }
}
