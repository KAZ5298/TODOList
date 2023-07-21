<?php

class Users extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllUsers()
    {
        $sql = 'SELECT * FROM users WHERE is_deleted=0;';
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function loginUser($user)
    {
        $sql = 'SELECT * FROM users WHERE user=:user AND is_deleted=0;';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':user', $user, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
