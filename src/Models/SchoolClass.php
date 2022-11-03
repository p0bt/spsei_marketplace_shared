<?php
namespace SpseiMarketplace\Models;

use SpseiMarketplace\Core\Database;

class SchoolClass extends BaseModel
{
    public function post($data)
    {
        Database::query("INSERT INTO `classes` 
                        (`name`) 
                        VALUES (?)", 
                        [$data['name']]);
    }

    public function get_all()
    {
        return Database::query("SELECT * 
                                FROM `classes`")->getResultArray();
    }

    public function delete_by_id($class_id)
    {
        Database::query("DELETE FROM `classes` 
                        WHERE `class_id` = ?", 
                        [$class_id]);
    }
}