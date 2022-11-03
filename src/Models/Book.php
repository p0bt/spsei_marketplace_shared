<?php
namespace SpseiMarketplace\Models;

use SpseiMarketplace\Core\Database;

class Book extends BaseModel
{
    public function post($data)
    {
        Database::query("INSERT INTO `books` 
                        (`book_ISBN`, `name`, `author`, `category_id`) 
                        VALUES (?, ?, ?, ?)", 
                        [$data['book_ISBN'], $data['name'], $data['author'], $data['category_id']]);
    }

    public function get_all()
    {
        return Database::query("SELECT b.book_ISBN AS 'b_book_ISBN', b.name AS 'b_name', b.author AS 'b_author', c.name AS 'c_name', c.value AS 'c_value' 
                                FROM `books` `b`
                                INNER JOIN `categories` `c` ON `b`.`category_id` = `c`.`category_id`")->getResultArray();
    }

    public function get_all_by_category_value($category_value)
    {
        return Database::query("SELECT b.book_ISBN AS 'b_book_ISBN', b.name AS 'b_name', b.author AS 'b_author', c.name AS 'c_name', c.value AS 'c_value'
                                FROM `books` `b`
                                INNER JOIN `categories` `c` ON `b`.`category_id` = `c`.`category_id`
                                WHERE `c`.`value` = ?", [$category_value])->getResultArray();
    }

    public function delete_by_id($book_ISBN)
    {
        Database::query("DELETE FROM `books` 
                        WHERE `book_ISBN` = ?", 
                        [$book_ISBN]);
    }
}