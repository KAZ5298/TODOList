<?php

session_start();
session_regenerate_id(true);

abstract class Base 
{
    const DB_NAME = 'db_todolist';

    const DB_HOST = 'localhost';

    const DB_USER = 'root';

    const DB_PASSWORD = '';

    protected $dbh;

    public function __construct()
    {
        $dsn = 'mysql:dbname='.self::DB_NAME.';host='.self::DB_HOST.';charset=utf8';

        $this->dbh = new PDO($dsn, self::DB_USER, self::DB_PASSWORD);

        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}