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

    public static function itemNullCheck(string $item_name)
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

    public static function stringLengthCheck(string $item_name)
    {
        if (strlen($item_name) > 100) {
            return false;
        }
        return true;
    }
}