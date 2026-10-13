<?php
/**
 * Model Riwayat Status (audit trail)
 */
class Riwayat extends Model
{
    public function create($data)
    {
        $this->execute(
            'INSERT INTO riwayat_status (pendaftaran_id, status_lama, status_baru, oleh_user_id, catatan, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())',
            array(
                $data['pendaftaran_id'], $data['status_lama'], $data['status_baru'],
                isset($data['oleh_user_id']) ? $data['oleh_user_id'] : null,
                isset($data['catatan']) ? $data['catatan'] : null,
            )
        );
        return $this->lastId();
    }

    public function forPendaftaran($pendaftaranId)
    {
        return $this->fetchAll(
            'SELECT r.*, u.nama AS nama_petugas
             FROM riwayat_status r
             LEFT JOIN users u ON u.id = r.oleh_user_id
             WHERE r.pendaftaran_id = ?
             ORDER BY r.created_at DESC',
            array($pendaftaranId)
        );
    }
}
