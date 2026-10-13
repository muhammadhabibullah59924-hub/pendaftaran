<?php
/**
 * Front controller — titik masuk aplikasi PSB-MI Ihyaul Islam
 */

// Path absolut ke root proyek
define('BASE_PATH', __DIR__);

// URL dasar (misal /pendaftaran), dihitung otomatis
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', rtrim($scriptDir, '/'));

// Fungsi bantu
require BASE_PATH . '/app/Core/helpers.php';

// Zona waktu
date_default_timezone_set(config('timezone'));

// Autoloader kelas (tanpa namespace, kompatibel PHP 5.6)
spl_autoload_register(function ($class) {
    $paths = array('app/Core/', 'app/Controllers/', 'app/Models/');
    foreach ($paths as $p) {
        $file = BASE_PATH . '/' . $p . $class . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

// Mulai session
Session::start();

// Routing
$router = new Router();
require BASE_PATH . '/app/routes.php';
$router->dispatch();
