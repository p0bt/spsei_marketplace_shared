<?php

class Book extends BaseModel
{
    public function post($data)
    {
        Database::query("INSERT INTO `books` (`name`, `author`) VALUES (?, ?)", [$data['name'], $data['author']]);
    }

    public function get_all()
    {
        return Database::query("SELECT * FROM `books`")->getResultArray();
    }

    public function delete_by_id($book_id)
    {
        Database::query("DELETE FROM `books` WHERE `book_id` = ?", [$book_id]);
    }
}