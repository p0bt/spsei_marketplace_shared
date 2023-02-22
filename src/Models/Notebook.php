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

    public function get_all()
    {
        return $this->db->query("SELECT nb.*, m.name AS 'm_name', m.value AS 'm_value' 
                                FROM `notebooks` `nb`
                                INNER JOIN `majors` `m` ON `m`.`major_id` = `nb`.`major_id`")->getResultArray();
    }

    public function delete_by_id($notebook_id)
    {
        $this->db->query("DELETE FROM `notebooks` 
                        WHERE `notebook_id` = ?", 
                        [$notebook_id]);
    }

    public function get_by_id($notebook_id)
    {
        return $this->db->query("SELECT nb.*, m.name AS 'm_name', m.value AS 'm_value' 
                                FROM `notebooks` `nb`
                                INNER JOIN `majors` `m` ON `m`.`major_id` = `nb`.`major_id`
                                WHERE nb.notebook_id = ?",
                                [$notebook_id])->getRowArray();
    }

    public function update($data, $notebook_id)
    {
        return $this->db->query("UPDATE `notebooks` 
                                SET `name` = ?,
                                    grade = ?, 
                                    major_id = ?
                                WHERE notebook_id = ?", 
                                [$data['name'], $data['grade'], $data['major_id'], $notebook_id]);
    }
}