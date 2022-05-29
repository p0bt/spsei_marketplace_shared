<?php

class Notification extends BaseModel
{
    public function post($data)
    {
        Database::query("INSERT INTO `notifications` (`target`, `content`)
        VALUES (?, ?)", [$data['target'], $data['content']]);
    }

    public function get_for_user($user_id)
    {
        return Database::query("SELECT `n`.*, `u`.* FROM `notifications` `n`, `users` `u` WHERE (`n`.`target` = `u`.`user_id` OR `n`.`target` = '*') AND (`u`.`user_id` = ?) AND (`n`.`date` >= `u`.`register_date`) ORDER BY `n`.`date` DESC", [$user_id])->getResultArray();
    }
}