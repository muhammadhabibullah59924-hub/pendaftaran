<?php
/**
 * Koneksi database (PDO) - singleton.
 */
class Database
{
    private static $instance = null;

    public static function connect()
    {
        if (self::$instance === null) {
            $cfg = require BASE_PATH . '/config/database.php';
            $dsn = 'mysql:host=' . $cfg['host'] . ';port=' . $cfg['port']
                 . ';dbname=' . $cfg['database'] . ';charset=utf8mb4';
            try {
                self::$instance = new PDO($dsn, $cfg['username'], $cfg['password'], array(
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ));
            } catch (PDOException $ex) {
                http_response_code(500);
                echo '<div style="font-family:sans-serif;max-width:640px;margin:60px auto;padding:24px;border:1px solid #e5c07b;background:#fff8e1;border-radius:8px">';
                echo '<h2>Koneksi Database Gagal</h2>';
                echo '<p>' . htmlspecialchars($ex->getMessage()) . '</p>';
                echo '<p>Pastikan <b>MySQL sudah di-Start</b> di XAMPP Control Panel dan database sudah dibuat dengan menjalankan <code>database/install.php</code>.</p>';
                echo '</div>';
                exit;
            }
        }
        return self::$instance;
    }
}
