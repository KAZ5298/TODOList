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
        if (!isset($item_name)) {
            return false;
        }
        return true;
    }

    public static function userNullCheck(int $user_id) {
        if (empty($user_id)) {
            return false;
        }
        return true;
    }
}
