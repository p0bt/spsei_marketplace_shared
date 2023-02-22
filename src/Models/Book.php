<?php
namespace SpseiMarketplace\Models;

class Book extends BaseModel
{
    public function post($data)
    {
        $this->db->query("INSERT INTO `books` 
                        (`book_ISBN`, `name`, `author`, `category_id`, `grade`, `major_id`) 
                        VALUES (?, ?, ?, ?, ?, ?)", 
                        [$data['book_ISBN'], $data['name'], $data['author'], $data['category_id'], $data['grade'], $data['major_id']]);
    }

    public function get_all()
    {
        return $this->db->query("SELECT b.book_ISBN AS 'b_book_ISBN', b.name AS 'b_name', b.author AS 'b_author', c.name AS 'c_name', c.value AS 'c_value' 
                                FROM `books` `b`
                                INNER JOIN `categories` `c` ON `b`.`category_id` = `c`.`category_id`")->getResultArray();
    }

    public function get_by_id($book_id)
    {
        return $this->db->query("SELECT b.book_ISBN AS 'b_book_ISBN', b.name AS 'b_name', b.author AS 'b_author', c.name AS 'c_name', c.value AS 'c_value' 
                                FROM `books` `b`
                                INNER JOIN `categories` `c` ON `b`.`category_id` = `c`.`category_id`
                                WHERE b.book_ISBN = ?",
                                [$book_id])->getRowArray();
    }

    public function get_all_by_category_value($category_value)
    {
        return $this->db->query("SELECT b.book_ISBN AS 'b_book_ISBN', b.name AS 'b_name', b.author AS 'b_author', c.name AS 'c_name', c.value AS 'c_value'
                                FROM `books` `b`
                                INNER JOIN `categories` `c` ON `b`.`category_id` = `c`.`category_id`
                                WHERE `c`.`value` = ?", [$category_value])->getResultArray();
    }

    public function delete_by_id($book_ISBN)
    {
        $this->db->query("DELETE FROM `books` 
                        WHERE `book_ISBN` = ?", 
                        [$book_ISBN]);
    }

    public function update($data, $book_id)
    {
        return $this->db->query("UPDATE `books` 
                                SET book_ISBN = ?,
                                    `name` = ?,
                                    author = ?,
                                    category_id = ?,
                                    grade = ?, 
                                    major_id = ?
                                WHERE book_ISBN = ?", 
                                [$data['book_ISBN'], $data['name'], $data['author'], $data['category_id'], $data['grade'], $data['major_id'], $book_id]);
    }
}