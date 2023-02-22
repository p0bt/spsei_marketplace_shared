<?php
namespace SpseiMarketplace\Models;

class Token extends BaseModel
{
    public function post($data)
    {
        return $this->db->query("INSERT INTO `tokens` 
                                (`token_id`, `user_id`, `expiration_date`)
                                VALUES (?, ?, ?)", 
                                [$data['token_id'], $data['user_id'], $data['expiration_date']]);
    }

    public function get_by_id($token_id)
    {
        $result = $this->db->query("SELECT * 
                                FROM `tokens` 
                                WHERE token_id = ?", 
                                [$token_id])->getRowArray();

        return ($result ? $result : false);
    }

    public function is_valid($token_id)
    {
        $result = $this->get_by_id($token_id);
        return ($result && (strtotime($result['expiration_date']) >= time()));
    }

    public function delete_by_id($token_id)
    {
        return $this->db->query("DELETE FROM `tokens` 
                                WHERE `token_id` = ?", 
                                [$token_id]);
    }

    public function delete_old()
    {
        return $this->db->query("DELETE FROM `tokens` 
                                WHERE `expiration_date` < CURRENT_TIMESTAMP()");
    }
}