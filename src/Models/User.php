<?php
namespace SpseiMarketplace\Models;

use SpseiMarketplace\Core\HelperFunctions;

class User extends BaseModel
{

    public function get_all()
    {
        return $this->db->query("SELECT u.*, c.name AS 'c_name', bi.bi_id AS 'bi_id' 
                                FROM `users` `u` 
                                LEFT JOIN `classes` `c` ON `c`.`class_id` = `u`.`class_id` 
                                LEFT JOIN `banned_ips` `bi` ON `bi`.`ip_address` = `u`.`ip_address`")->getResultArray();
    }

    public function get_count()
    {
        return $this->db->query("SELECT COUNT(user_id) AS 'count' 
                                FROM `users`")->getRowArray()['count'];
    }

    public function get_by_email($email)
    {
        return $this->db->query("SELECT * 
                                FROM `users` 
                                WHERE `email` = ?", 
                                [$email])->getRowArray();
    }

    public function get_by_id($user_id)
    {
        return $this->db->query("SELECT * 
                                FROM `users` 
                                WHERE `user_id` = ?", 
                                [$user_id])->getRowArray();
    }

    public function post($data)
    {
        return $this->db->query("INSERT INTO `users` 
                                (`email`, `password`, `ip_address`) 
                                VALUES (?, ?, ?)", 
                                [$data['email'], $data['password'], HelperFunctions::getClientIp()]);
    }

    public function ban_ip($ip_address)
    {
        $this->db->query("INSERT INTO `banned_ips` 
                        (`ip_address`) 
                        VALUES (?)", 
                        [$ip_address]);
    }

    public function unban_ip($ip_address)
    {
        $this->db->query("DELETE FROM `banned_ips` 
                        WHERE `ip_address` = ?", 
                        [$ip_address]);
    }

    public function update_password($password, $user_id)
    {
        return $this->db->query("UPDATE `users` 
                                SET `password` = ?
                                WHERE user_id = ?", 
                                [$password, $user_id]);
    }

    public function update($data, $user_id)
    {
        return $this->db->query("UPDATE `users` 
                                SET first_name = ?,
                                    last_name = ?,
                                    class_id = ?,
                                    email = ?,
                                    ip_address = ?, 
                                    `admin` = ?,
                                    last_update = ?,
                                    register_date = ?
                                WHERE user_id = ?", 
                                [$data['first_name'], $data['last_name'], $data['class_id'], $data['email'], $data['ip_address'], $data['admin'], $data['last_update'], $data['register_date'], $user_id]);
    }
}