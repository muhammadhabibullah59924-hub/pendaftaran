<?php
/**
 * Pembungkus session.
 */
class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /** Set atau ambil pesan flash (sekali pakai) */
    public static function flash($key, $value = null)
    {
        if ($value === null) {
            $val = isset($_SESSION['_flash'][$key]) ? $_SESSION['_flash'][$key] : null;
            unset($_SESSION['_flash'][$key]);
            return $val;
        }
        $_SESSION['_flash'][$key] = $value;
        return null;
    }

    public static function regenerate()
    {
        session_regenerate_id(true);
    }

    public static function destroy()
    {
        session_unset();
        session_destroy();
    }
}
