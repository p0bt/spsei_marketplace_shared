<?php
namespace SpseiMarketplace\Models;

use SpseiMarketplace\Core\Database;

abstract class BaseModel
{
    protected $db;

    public function __construct()
    {
        $this->db = new Database();
    }
}