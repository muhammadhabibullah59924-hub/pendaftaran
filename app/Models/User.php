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
