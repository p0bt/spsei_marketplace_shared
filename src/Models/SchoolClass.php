<?php
namespace SpseiMarketplace\Models;

class SchoolClass extends BaseModel
{
    public function post($data)
    {
        $this->db->query("INSERT INTO `classes` 
                        (`name`) 
                        VALUES (?)", 
                        [$data['name']]);
    }

    public function get_all()
    {
        return $this->db->query("SELECT * 
                                FROM `classes`")->getResultArray();
    }

    public function get_by_id($class_id)
    {
        return $this->db->query("SELECT * 
                                FROM `classes`
                                WHERE class_id = ?",
                                [$class_id])->getRowArray();
    }

    public function delete_by_id($class_id)
    {
        $this->db->query("DELETE FROM `classes` 
                        WHERE `class_id` = ?", 
                        [$class_id]);
    }

    public function update($data, $class_id)
    {
        return $this->db->query("UPDATE `classes` 
                                SET `name` = ?
                                WHERE class_id = ?", 
                                [$data['name'], $class_id]);
    }
}