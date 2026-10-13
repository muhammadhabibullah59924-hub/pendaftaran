<?php
/**
 * Perlindungan CSRF (Cross-Site Request Forgery).
 */
class Csrf
{
    /** Ambil / buat token CSRF */
    public static function token()
    {
        if (!Session::has('_csrf')) {
            if (function_exists('random_bytes')) {
                $token = bin2hex(random_bytes(32));
            } elseif (function_exists('openssl_random_pseudo_bytes')) {
                $token = bin2hex(openssl_random_pseudo_bytes(32));
            } else {
                $token = md5(uniqid(mt_rand(), true));
            }
            Session::set('_csrf', $token);
        }
        return Session::get('_csrf');
    }

    /** Render hidden input token */
    public static function field()
    {
        return '<input type="hidden" name="_token" value="' . self::token() . '">';
    }

    /** Periksa token yang dikirim */
    public static function check($token)
    {
        $stored = Session::get('_csrf');
        if (!$stored || !$token) {
            return false;
        }
        if (function_exists('hash_equals')) {
            return hash_equals($stored, $token);
        }
        return $stored === $token;
    }
}
