<?php

class Common
{
    public function getDate()
    {
        $dateTime = new DateTime();
        $dateTime->setTimezone(new DateTimeZone('Asia/Tokyo'));
        $dt = $dateTime->format('Y-m-d');
        return $dt;
    }
}
