<?php
namespace SpseiMarketplace\Models;

class BannedIp extends BaseModel
{    
    public function get_all()
    {
        return $this->db->query("SELECT * 
                                FROM `banned_ips`")->getResultArray();
    }

    public function get_by_id($bi_id)
    {
        return $this->db->query("SELECT * 
                                FROM `banned_ips`
                                WHERE bi_id = ?",
                                [$bi_id])->getRowArray();
    }

    public function delete_by_id($bi_id)
    {
        $this->db->query("DELETE FROM `banned_ips` 
                        WHERE `bi_id` = ?", 
                        [$bi_id]);
    }

    public function update($data, $bi_id)
    {
        return $this->db->query("UPDATE `banned_ips` 
                                SET ip_address = ?
                                WHERE bi_id = ?", 
                                [$data['ip_address'], $bi_id]);
    }
}