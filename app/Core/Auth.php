<?php
/**
 * Autentikasi & otorisasi sederhana.
 */
class Auth
{
    /** Coba login dengan email + password */
    public static function attempt($email, $password)
    {
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            return false;
        }
        if (!$user['aktif']) {
            return false;
        }
        if (!password_verify($password, $user['password'])) {
            return false;
        }

        self::login($user);
        return true;
    }

    /** Simpan user ke sesi */
    public static function login($user)
    {
        Session::regenerate();
        Session::set('user', array(
            'id'    => $user['id'],
            'nama'  => $user['nama'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ));
    }

    /** Data user yang login */
    public static function user()
    {
        return Session::get('user');
    }

    public static function id()
    {
        $u = self::user();
        return $u ? $u['id'] : null;
    }

    public static function check()
    {
        return self::user() !== null;
    }

    public static function guest()
    {
        return !self::check();
    }

    public static function role()
    {
        $u = self::user();
        return $u ? $u['role'] : null;
    }

    public static function isWali()
    {
        return self::role() === 'wali_murid';
    }

    public static function isAdmin()
    {
        return self::role() === 'admin';
    }

    public static function isKepsek()
    {
        return self::role() === 'kepsek';
    }

    public static function hasRole($roles)
    {
        if (!self::check()) {
            return false;
        }
        if (is_array($roles)) {
            return in_array(self::role(), $roles);
        }
        return self::role() === $roles;
    }

    public static function logout()
    {
        Session::remove('user');
        Session::regenerate();
    }
}
