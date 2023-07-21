<?php

class Safety
{
    public static function sanitize(array $post): array
    {
        foreach ($post as $key => $value) {
            $post[$key] = htmlspecialchars($value);
        }
        return $post;
    }

    public static function generateToken(string $tokenName = 'token'): string
    {
        $token = bin2hex(openssl_random_pseudo_bytes(32));
        $_SESSION[$tokenName] = $token;
        return $token;
    }

    public static function isValidToken(string $token, string $tokenName = 'token') : bool
    {
        if (!isset($_SESSION[$tokenName]) || $_SESSION[$tokenName] !== $token) {
            return false;
        }
        return true;
    }
}
