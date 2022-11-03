<?php
namespace SpseiMarketplace\Models;

use SpseiMarketplace\Core\Database;

class Api extends BaseModel
{
    public function post($data)
    {
        return Database::query("INSERT INTO `api_keys` 
                                (`api_key`, `description`, `expiration_date`)
                                VALUES (?, ?, ?)", 
                                [$data['api_key'], $data['description'], $data['expiration_date']]);
    }

    public function delete_by_id($id)
    {
        return Database::query("DELETE FROM `api_keys` 
                                WHERE api_key_id = ?", 
                                [$id]);
    }

    public function get_all_keys()
    {
        return Database::query("SELECT * 
                                FROM `api_keys`")->getResultArray();
    }

    public static function verify_key($key)
    {
        // Check if given API key exists in our DB, and also check expiration date (NULL = no expiration)
        $r = Database::query("SELECT api_key_id 
                            FROM `api_keys` 
                            WHERE api_key = ? AND (NOW() <= expiration_date OR expiration_date IS NULL)", 
                            [$key])->getRowArray();
        return (isset($r) && $r);
    }
}