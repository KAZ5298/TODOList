<?php

class Users extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllUsers()
    {
        $sql = 'SELECT * FROM users;';
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

    public function selectUser($user_id)
    {
        $sql = 'SELECT * FROM users WHERE id=:id;';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertUser($login_user, $pass, $family_name, $first_name, $is_admin, bool $delete_flg)
    {
        if (isset($delete_flg)) {
            $is_deleted = 1;
        } else {
            $is_deleted = 0;
        }
        $pass_hash = password_hash($pass, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users(';
        $sql .= 'user, pass, family_name, first_name, is_admin, is_deleted';
        $sql .= ') values (';
        $sql .= ':user, :pass, :family_name, :first_name, :is_admin, :is_deleted';
        $sql .= ')';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':user', $login_user, PDO::PARAM_STR);
        $stmt->bindValue(':pass', $pass_hash, PDO::PARAM_STR);
        $stmt->bindValue(':family_name', $family_name, PDO::PARAM_STR);
        $stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
        $stmt->bindValue(':is_admin', $is_admin, PDO::PARAM_INT);
        $stmt->bindValue(':is_deleted', $is_deleted, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function editUser(int $user_id, $login_user, $pass, $family_name, $first_name, $is_admin, $delete_flg)
    {
        if (isset($delete_flg)) {
            $is_deleted = 1;
        } else {
            $is_deleted = 0;
        }
        $pass_hash = password_hash($pass, PASSWORD_DEFAULT);

        $sql = 'UPDATE users SET ';
        $sql .= 'user=:user, ';
        $sql .= 'pass=:pass, ';
        $sql .= 'family_name=:family_name, ';
        $sql .= 'first_name=:first_name, ';
        $sql .= 'is_admin=:is_admin, ';
        $sql .= 'is_deleted=:is_deleted ';
        $sql .= 'WHERE id=:id';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':user', $login_user, PDO::PARAM_STR);
        $stmt->bindValue(':pass', $pass_hash, PDO::PARAM_STR);
        $stmt->bindValue(':family_name', $family_name, PDO::PARAM_STR);
        $stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
        $stmt->bindValue(':is_admin', $is_admin, PDO::PARAM_INT);
        $stmt->bindValue(':is_deleted', $is_deleted, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteUser(int $user_id)
    {
        $sql = 'UPDATE users SET ';
        $sql .= 'is_deleted=1 ';
        $sql .= 'WHERE id=:id';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    }

}