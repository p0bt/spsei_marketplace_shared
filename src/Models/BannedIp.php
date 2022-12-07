<?php
namespace SpseiMarketplace\Models;

class BannedIp extends BaseModel
{    
    public function get_all()
    {
        return $this->db->query("SELECT * 
                                FROM `banned_ips`")->getResultArray();
    }

    public function delete_by_id($banned_ip_id)
    {
        $this->db->query("DELETE FROM `banned_ips` 
                        WHERE `bi_id` = ?", 
                        [$banned_ip_id]);
    }
}