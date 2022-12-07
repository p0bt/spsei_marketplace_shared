<?php
namespace SpseiMarketplace\Models;

class Notebook extends BaseModel
{
    public function post($data)
    {
        $this->db->query("INSERT INTO `notebooks` 
                        (`name`, `grade`, `major_id`) 
                        VALUES (?, ?, ?)", 
                        [$data['name'], $data['grade'], $data['major_id']]);
        return $this->db->get_last_inserted_id();
    }
}