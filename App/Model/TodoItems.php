<?php

class TodoItems extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllItems()
    {
        $sql = 'SELECT ';
        $sql .= 't.id AS todo_id, t.user_id, t.item_name, t.registration_date, t.expire_date, t.finished_date, t.is_deleted, ';
        $sql .= 'u.user, u.family_name, u.first_name ';
        $sql .= 'FROM todo_items AS t JOIN users AS u ON t.user_id = u.id ';
        $sql .= 'WHERE t.is_deleted != 1 ';
        $sql .= 'ORDER BY expire_date ASC;';
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectByItemId(int $item_id)
    {
        $sql = 'SELECT ';
        $sql .= 't.id AS todo_id, t.user_id, t.item_name, t.registration_date, t.expire_date, t.finished_date, t.is_deleted, ';
        $sql .= 'u.user, u.family_name, u.first_name ';
        $sql .= 'FROM todo_items AS t JOIN users AS u ON t.user_id = u.id ';
        $sql .= 'WHERE t.id=:id;';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':id', $item_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchItems(string $search_name)
    {
        $sql = 'SELECT ';
        $sql .= 't.id AS todo_id, t.user_id, t.item_name, t.registration_date, t.expire_date, t.finished_date, t.is_deleted, ';
        $sql .= 'u.user, u.family_name, u.first_name ';
        $sql .= 'FROM todo_items AS t JOIN users AS u ON t.user_id = u.id ';
        $sql .= 'WHERE t.is_deleted != 1 ';
        $sql .= 'AND (t.item_name LIKE :item_name ';
        $sql .= 'OR u.family_name LIKE :family_name ';
        $sql .= 'OR u.first_name LIKE :first_name) ';
        $sql .= 'ORDER BY expire_date ASC;';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':item_name', '%' . $search_name . '%', PDO::PARAM_STR);
        $stmt->bindValue(':family_name', '%' . $search_name . '%', PDO::PARAM_STR);
        $stmt->bindValue(':first_name', '%' . $search_name . '%', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteTodoItem(int $item_id)
    {
        $sql = 'UPDATE todo_items SET is_deleted=1 WHERE id=:id';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':id', $item_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function todoItemIsComplete(string $finished_date, int $item_id)
    {
        $sql = 'UPDATE todo_items SET finished_date = :finished_date WHERE id = :id;';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':finished_date', $finished_date, PDO::PARAM_STR);
        $stmt->bindValue(':id', $item_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function editTodoItem(int $item_id, int $user_id, string $item_name, string $expire_date, string $finished_date)
    {
        $sql = 'UPDATE todo_items SET ';
        $sql .= 'user_id=:user_id, ';
        $sql .= 'item_name=:item_name, ';
        $sql .= 'expire_date=:expire_date, ';
        $sql .= 'finished_date=:finished_date ';
        $sql .= 'WHERE id=:id';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':id', $item_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':item_name', $item_name, PDO::PARAM_STR);
        $stmt->bindValue(':expire_date', $expire_date, PDO::PARAM_STR);
        if ($finished_date != "") {
            $stmt->bindValue(':finished_date', $finished_date, PDO::PARAM_STR);
        } else {
            $stmt->bindValue(':finished_date', null, PDO::PARAM_STR);
        }
        $stmt->execute();
    }

    public function insertTodoItem(int $user_id, string $item_name, string $registration_date, string $expire_date, string $finished_date)
    {
        $sql = 'INSERT INTO todo_items(';
        $sql .= 'user_id, item_name, registration_date, expire_date, finished_date';
        $sql .= ') values (';
        $sql .= ':user_id, :item_name, :registration_date, :expire_date, :finished_date';
        $sql .= ')';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':item_name', $item_name, PDO::PARAM_STR);
        $stmt->bindValue(':registration_date', $registration_date, PDO::PARAM_STR);
        $stmt->bindValue(':expire_date', $expire_date, PDO::PARAM_STR);
        $stmt->bindValue(':finished_date', $finished_date, PDO::PARAM_STR);
        if ($finished_date != "") {
            $stmt->bindValue(':finished_date', $finished_date, PDO::PARAM_STR);
        } else {
            $stmt->bindValue(':finished_date', null, PDO::PARAM_STR);
        }
        $stmt->execute();
    }
}
