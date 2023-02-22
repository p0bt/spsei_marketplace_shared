<?php
namespace SpseiMarketplace\Models;

use SpseiMarketplace\Core\Database;

class Api extends BaseModel
{
    public function post($data)
    {
        return $this->db->query("INSERT INTO `api_keys` 
                                (`api_key`, `description`, `expiration_date`)
                                VALUES (?, ?, ?)", 
                                [$data['api_key'], $data['description'], $data['expiration_date']]);
    }

    public function delete_by_id($id)
    {
        return $this->db->query("DELETE FROM `api_keys` 
                                WHERE api_key_id = ?", 
                                [$id]);
    }

    public function get_all_keys()
    {
        return $this->db->query("SELECT * 
                                FROM `api_keys`")->getResultArray();
    }

    public function get_by_id($api_key_id)
    {
        return $this->db->query("SELECT * 
                                FROM `api_keys`
                                WHERE api_key_id = ?",
                                [$api_key_id])->getRowArray();
    }

    public static function verify_key($key)
    {
        $db = new Database();
        // Check if given API key exists in our DB, and also check expiration date (NULL = no expiration)
        $r = $db->query("SELECT api_key_id 
                            FROM `api_keys` 
                            WHERE api_key = ? AND (NOW() <= expiration_date OR expiration_date IS NULL)", 
                            [$key])->getRowArray();
        return (isset($r) && $r);
    }

    public function update($data, $api_key_id)
    {
        return $this->db->query("UPDATE `api_keys` 
                                SET api_key = ?,
                                    `description` = ?,
                                    expiration_date = ?
                                WHERE api_key_id = ?", 
                                [$data['api_key'], $data['description'], $data['expiration_date'], $api_key_id]);
    }
}