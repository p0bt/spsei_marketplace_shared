<?php

namespace SpseiMarketplace\Models;

class Major extends BaseModel
{
    public function get_all()
    {
        return $this->db->query("SELECT * 
                                FROM `majors`")->getResultArray();
    }
}