<?php

class Validation
{
    public static function lengthCheck(string $item_name)
    {
        if (strlen($item_name) > 100) {
            return false;
        }
        return true;
    }

    public static function itemNullCheck($item_name)
    {
        if (empty($item_name)) {
            return false;
        }
        return true;
    }

    public static function userNullCheck($user_id)
    {
        if ($user_id <= 0) {
            return false;
        }

        if (!is_numeric($user_id)) {
            return false;
        }

        return true;

    }

    public static function strLenChk(string $item_name)
    {
        if (strlen($item_name) > 100) {
            return false;
        }
        return true;
    }

    public static function userLenChk($item_len)
    {
        if (strlen($item_len) > 50) {
            return false;
        }
        return true;
    }

    public static function passChk($password)
    {
        if (!preg_match('/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,50}+\z/', $password)) {
            return false;
        }
        return true;
    }
}