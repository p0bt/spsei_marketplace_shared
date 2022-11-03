<?php
namespace SpseiMarketplace\Models;

use SpseiMarketplace\Core\Database;

class ClassRoom extends BaseModel
{
    public function replace($data)
    {
        Database::query("REPLACE INTO `class_room` 
                        (`class_id`, `room_code`) 
                        VALUES (?, ?)", 
                        [$data['class_id'], $data['room_code']]);
    }

    public function get_all()
    {
        return Database::query("SELECT `cr`.*, `c`.`name` 
                                FROM `class_room` AS `cr` 
                                LEFT JOIN `classes` `c` ON `cr`.`class_id` = `c`.`class_id`")->getResultArray();
    }

    public function delete_by_id($cr_id)
    {
        Database::query("DELETE FROM `class_room` 
                        WHERE `cr_id` = ?", 
                        [$cr_id]);
    }
}