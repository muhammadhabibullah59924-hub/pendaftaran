<?php
/**
 * Model Users
 */
class User extends Model
{
    public function findByEmail($email)
    {
        return $this->fetchOne('SELECT * FROM users WHERE email = ? LIMIT 1', array($email));
    }

    public function find($id)
    {
        return $this->fetchOne('SELECT * FROM users WHERE id = ? LIMIT 1', array($id));
    }

    public function all()
    {
        return $this->fetchAll('SELECT * FROM users ORDER BY role, nama ASC');
    }

    /** Daftar user (dengan jumlah pendaftaran), bisa difilter */
    public function listUsers($filter = array())
    {
        $sql = 'SELECT u.*,
                       (SELECT COUNT(*) FROM pendaftaran p WHERE p.user_id = u.id) AS jumlah_pendaftaran
                FROM users u
                WHERE 1=1';
        $params = array();

        if (!empty($filter['status'])) {
            if ($filter['status'] === 'aktif') {
                $sql .= ' AND u.aktif = 1';
            } elseif ($filter['status'] === 'nonaktif') {
                $sql .= ' AND u.aktif = 0';
            } elseif ($filter['status'] === 'menunggu') {
                $sql .= ' AND u.aktif = 0 AND u.role = ?';
                $params[] = 'wali_murid';
            }
        }
        if (!empty($filter['role'])) {
            $sql .= ' AND u.role = ?';
            $params[] = $filter['role'];
        }
        if (!empty($filter['cari'])) {
            $sql .= ' AND (u.nama LIKE ? OR u.email LIKE ?)';
            $like = '%' . $filter['cari'] . '%';
            $params[] = $like;
            $params[] = $like;
        }

        // Akun menunggu persetujuan ditampilkan paling atas
        $sql .= ' ORDER BY u.aktif ASC, u.created_at DESC';
        return $this->fetchAll($sql, $params);
    }

    /** Jumlah akun yang menunggu persetujuan (belum aktif & bukan admin/kepsek) */
    public function countPending()
    {
        return $this->count(
            "SELECT COUNT(*) FROM users WHERE aktif = 0 AND role = 'wali_murid'"
        );
    }

    /** Ubah status aktif sebuah akun */
    public function setAktif($id, $aktif)
    {
        return $this->execute(
            'UPDATE users SET aktif = ?, updated_at = NOW() WHERE id = ?',
            array($aktif ? 1 : 0, $id)
        );
    }

    /** Jumlah pendaftaran milik sebuah akun */
    public function countPendaftaran($userId)
    {
        return $this->count(
            'SELECT COUNT(*) FROM pendaftaran WHERE user_id = ?',
            array($userId)
        );
    }

    public function create($data)
    {
        $this->execute(
            'INSERT INTO users (nama, email, password, role, aktif, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())',
            array($data['nama'], $data['email'], $data['password'], $data['role'], isset($data['aktif']) ? $data['aktif'] : 1)
        );
        return $this->lastId();
    }

    public function update($id, $data)
    {
        $fields = array();
        $params = array();
        foreach ($data as $k => $v) {
            $fields[] = $k . ' = ?';
            $params[] = $v;
        }
        $params[] = $id;
        return $this->execute('UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?', $params);
    }

    public function delete($id)
    {
        return $this->execute('DELETE FROM users WHERE id = ?', array($id));
    }
}
