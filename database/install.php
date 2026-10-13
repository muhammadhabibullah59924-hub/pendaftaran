<?php
/**
 * Installer Database — PSB-MI Ihyaul Islam
 * Buka halaman ini lewat browser: http://localhost/pendaftaran/database/install.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$root = dirname(__DIR__);
$dbCfg = require $root . '/config/database.php';

$message = '';
$error = '';
$warn = '';

/** Cek tabel */
function tabelAda($pdo, $tabel)
{
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE '" . $tabel . "'");
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        return false;
    }
}

/** Jumlah baris tabel (0 jika tabel tidak ada) */
function jumlahBaris($pdo, $tabel)
{
    if (!tabelAda($pdo, $tabel)) {
        return 0;
    }
    $row = $pdo->query("SELECT COUNT(*) FROM `" . $tabel . "`")->fetch();
    return (int) $row[0];
}

/** Jalankan isi file SQL statement per statement */
function jalankanSqlFile($pdo, $file)
{
    $sql = file_get_contents($file);
    if ($sql === false) {
        throw new Exception('Gagal membaca file: ' . basename($file));
    }
    $sql = preg_replace('/--.*$/m', '', $sql);
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
    $statements = preg_split('/;(\s*)\n/', $sql);
    $count = 0;
    foreach ($statements as $stmt) {
        $stmt = trim($stmt);
        if ($stmt === '') {
            continue;
        }
        $pdo->exec($stmt);
        $count++;
    }
    return $count;
}

/** Buat folder penyimpanan + blokir akses langsung */
function buatFolderUploads($root)
{
    $dir = $root . '/storage/uploads';
    if (!is_dir($dir)) {
        if (!@mkdir($dir, 0777, true)) {
            throw new Exception('Gagal membuat folder storage/uploads');
        }
    }
    $ht = $dir . '/.htaccess';
    if (!file_exists($ht)) {
        @file_put_contents($ht, "Require all denied\n");
    }
}

/** Koneksi tanpa memilih database */
function koneksi($cfg)
{
    $dsn = 'mysql:host=' . $cfg['host'] . ';port=' . $cfg['port'] . ';charset=utf8mb4';
    return new PDO($dsn, $cfg['username'], $cfg['password'], array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ));
}

/** Buat & pilih database */
function siapkanDatabase($pdo, $cfg)
{
    $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . $cfg['database'] . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci');
    $pdo->exec('USE `' . $cfg['database'] . '`');
}

$tableNames = array('berkas', 'riwayat_status', 'pengumuman', 'pendaftaran', 'gelombang', 'users');

// ---- Proses ----
if (isset($_POST['reset'])) {
    // Hapus tabel lama lalu pasang ulang bersih
    try {
        $pdo = koneksi($dbCfg);
        siapkanDatabase($pdo, $dbCfg);
        $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
        foreach ($tableNames as $t) {
            $pdo->exec('DROP TABLE IF EXISTS `' . $t . '`');
        }
        $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
        $n1 = jalankanSqlFile($pdo, __DIR__ . '/schema.sql');
        $n2 = jalankanSqlFile($pdo, __DIR__ . '/seed.sql');
        buatFolderUploads($root);
        $message = 'Database direset dan dipasang ulang (fresh). ' . $n1 . ' tabel, ' . $n2 . ' data awal.';
    } catch (Exception $e) {
        $error = $e->getMessage();
    } catch (PDOException $e) {
        $error = 'Koneksi MySQL gagal: ' . $e->getMessage() . '<br>Pastikan MySQL di XAMPP sudah <b>Start</b>.';
    }

} elseif (isset($_POST['install'])) {
    try {
        $pdo = koneksi($dbCfg);
        siapkanDatabase($pdo, $dbCfg);

        // Skema selalu aman dijalankan ulang (CREATE TABLE IF NOT EXISTS)
        $n1 = jalankanSqlFile($pdo, __DIR__ . '/schema.sql');

        // Seed hanya jika belum ada data (agar tidak duplikat)
        if (jumlahBaris($pdo, 'users') === 0) {
            $n2 = jalankanSqlFile($pdo, __DIR__ . '/seed.sql');
            $warn = $n2 . ' data awal dimasukkan.';
        } else {
            $warn = 'Data sudah ada &amp; <b>tidak diubah</b> (seed dilewati).';
        }

        buatFolderUploads($root);

        $message = 'Database <b>' . htmlspecialchars($dbCfg['database']) . '</b> siap. '
                 . $n1 . ' tabel dipasang. ' . $warn . '<br>'
                 . '<b>Akun login:</b><br>'
                 . '&bull; Admin &rarr; <code>admin@mihiyaulislam.sch.id</code> / <code>admin123</code><br>'
                 . '&bull; Kepala Sekolah &rarr; <code>kepsek@mihiyaulislam.sch.id</code> / <code>kepsek123</code><br>'
                 . '&bull; Contoh Wali Murid &rarr; <code>wali.contoh@email.com</code> / <code>wali123</code>';
    } catch (Exception $e) {
        $error = $e->getMessage();
    } catch (PDOException $e) {
        $error = 'Koneksi MySQL gagal: ' . $e->getMessage() . '<br>Pastikan MySQL di XAMPP sudah <b>Start</b>.';
    }
}

// ---- Status sekarang ----
$status = '';
$sudah = false;
try {
    $pdo = koneksi($dbCfg);
    siapkanDatabase($pdo, $dbCfg);
    if (tabelAda($pdo, 'users')) {
        $jml = jumlahBaris($pdo, 'users');
        $sudah = ($jml > 0);
        $status = $sudah
            ? '<span class="text-success">Terpasang — ' . jumlahBaris($pdo, 'users') . ' user, '
              . jumlahBaris($pdo, 'pendaftaran') . ' pendaftaran.</span>'
            : '<span class="text-warning">Tabel sudah ada tetapi masih kosong (belum di-seed).</span>';
    } else {
        $status = '<span class="text-muted">Belum terpasang.</span>';
    }
} catch (PDOException $e) {
    $status = '<span class="text-danger">MySQL tidak terhubung.</span>';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Installer Database — PSB-MI Ihyaul Islam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container" style="max-width:640px">
    <div class="text-center my-5">
        <h2>Installer Database</h2>
        <p class="text-muted">Sistem Informasi PSB-MI Ihyaul Islam</p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <p class="mb-1 small">Database tujuan: <code><?php echo htmlspecialchars($dbCfg['database']); ?></code></p>
            <p class="small text-muted mb-3">
                Status: <?php echo $status; ?><br>
                Pastikan <b>MySQL XAMPP sudah Start</b>.
            </p>

            <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="post" class="text-center">
                <button type="submit" name="install" value="1" class="btn btn-primary btn-lg px-5 mb-2">
                    Install / Perbarui Database
                </button>
                <?php if ($sudah): ?>
                <br>
                <button type="submit" name="reset" value="1" class="btn btn-outline-danger btn-sm mt-3"
                        onclick="return confirm('SEMUA DATA AKAN DIHAPUS dan diganti dengan data contoh. Lanjutkan?')">
                    Install Ulang &amp; Reset Data
                </button>
                <?php endif; ?>
            </form>

            <hr>
            <p class="text-center mb-0 small">
                <a href="../">Buka Halaman Utama &rarr;</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
