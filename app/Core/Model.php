<?php
/**
 * Model dasar.
 */
class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /** Ambil semua baris */
    public function fetchAll($sql, $params = array())
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Ambil satu baris */
    public function fetchOne($sql, $params = array())
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /** Jalankan query (insert/update/delete) */
    public function execute($sql, $params = array())
    {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /** ID insert terakhir */
    public function lastId()
    {
        return $this->db->lastInsertId();
    }

    /** Hitung jumlah baris */
    public function count($sql, $params = array())
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
}
