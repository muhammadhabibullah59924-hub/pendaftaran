<?php
/**
 * Validator input sederhana.
 * Aturan ditulis sebagai array: field => array('required', 'max:100', ...)
 */
class Validator
{
    protected $data;
    protected $errors = array();
    protected $labels = array();
    protected $rules = array();

    public function __construct($data)
    {
        $this->data = is_array($data) ? $data : array();
    }

    /**
     * Menjalankan validasi.
     * $rules  = array('field' => array('rule', 'rule:param'))
     * $labels = array('field' => 'Nama Lengkap')
     */
    public function run($rules, $labels = array())
    {
        $this->rules = $rules;
        $this->labels = $labels;

        foreach ($rules as $field => $rulesForField) {
            foreach ($rulesForField as $rule) {
                $this->apply($field, $rule);
            }
        }
    }

    public function fails()
    {
        return count($this->errors) > 0;
    }

    public function errors()
    {
        return $this->errors;
    }

    protected function apply($field, $rule)
    {
        $parts = explode(':', $rule, 2);
        $name = $parts[0];
        $param = isset($parts[1]) ? $parts[1] : null;

        $value = isset($this->data[$field]) ? $this->data[$field] : null;
        if (is_string($value)) {
            $value = trim($value);
        }

        switch ($name) {
            case 'required':
                if ($this->isEmpty($value)) {
                    $this->add($field, $this->label($field) . ' wajib diisi.');
                }
                break;

            case 'min':
                if (!$this->isEmpty($value) && strlen((string) $value) < (int) $param) {
                    $this->add($field, $this->label($field) . ' minimal ' . $param . ' karakter.');
                }
                break;

            case 'max':
                if (!$this->isEmpty($value) && strlen((string) $value) > (int) $param) {
                    $this->add($field, $this->label($field) . ' maksimal ' . $param . ' karakter.');
                }
                break;

            case 'email':
                if (!$this->isEmpty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->add($field, $this->label($field) . ' tidak valid.');
                }
                break;

            case 'numeric':
                if (!$this->isEmpty($value) && !is_numeric($value)) {
                    $this->add($field, $this->label($field) . ' harus berupa angka.');
                }
                break;

            case 'alpha':
                if (!$this->isEmpty($value) && !preg_match('#^[a-zA-Z ]+$#', $value)) {
                    $this->add($field, $this->label($field) . ' hanya boleh huruf.');
                }
                break;

            case 'alnum':
                if (!$this->isEmpty($value) && !preg_match('#^[a-zA-Z0-9 ]+$#', $value)) {
                    $this->add($field, $this->label($field) . ' hanya boleh huruf dan angka.');
                }
                break;

            case 'date':
                if (!$this->isEmpty($value) && strtotime($value) === false) {
                    $this->add($field, $this->label($field) . ' bukan tanggal yang valid.');
                }
                break;

            case 'in':
                if (!$this->isEmpty($value)) {
                    $list = explode(',', $param);
                    if (!in_array($value, $list)) {
                        $this->add($field, $this->label($field) . ' tidak valid.');
                    }
                }
                break;

            case 'same':
                if ($value !== (isset($this->data[$param]) ? $this->data[$param] : null)) {
                    $this->add($field, $this->label($field) . ' tidak sama dengan ' . $this->label($param) . '.');
                }
                break;

            case 'unique':
                if (!$this->isEmpty($value)) {
                    $table = $param;
                    $column = $field;
                    if (strpos($param, ',') !== false) {
                        list($table, $column) = explode(',', $param, 2);
                    }
                    if ($this->exists($table, $column, $value)) {
                        $this->add($field, $this->label($field) . ' sudah digunakan.');
                    }
                }
                break;

            case 'regex':
                if (!$this->isEmpty($value) && !preg_match($param, $value)) {
                    $this->add($field, $this->label($field) . ' format tidak valid.');
                }
                break;
        }
    }

    protected function add($field, $message)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }

    protected function label($field)
    {
        if (isset($this->labels[$field])) {
            return $this->labels[$field];
        }
        return ucwords(str_replace(array('_', '-'), ' ', $field));
    }

    protected function isEmpty($value)
    {
        return $value === null || $value === '';
    }

    protected function exists($table, $column, $value)
    {
        $db = Database::connect();
        $stmt = $db->prepare('SELECT COUNT(*) FROM `' . $table . '` WHERE `' . $column . '` = ? LIMIT 1');
        $stmt->execute(array($value));
        return (int) $stmt->fetchColumn() > 0;
    }
}
