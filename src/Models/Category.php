<?php

namespace SpseiMarketplace\Models;

class Category extends BaseModel
{
    public function get_all()
    {
        return $this->db->query("SELECT * 
                                FROM `categories`")->getResultArray();
    }
}