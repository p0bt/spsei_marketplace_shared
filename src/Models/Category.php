<?php

namespace SpseiMarketplace\Models;

use SpseiMarketplace\Core\Database;

class Category extends BaseModel
{
    public function get_all()
    {
        return Database::query("SELECT * 
                                FROM `categories`")->getResultArray();
    }
}