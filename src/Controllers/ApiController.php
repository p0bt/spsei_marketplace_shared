<?php
namespace SpseiMarketplace\Controllers;

use SpseiMarketplace\Core\Database;
use SpseiMarketplace\Models\Api;

class ApiController extends BaseController
{
    private $db;
    
    private $api;

    public function __construct()
    {
        if(isset($_GET['key']) && !empty($_GET['key']) && Api::verify_key($_GET['key']))
        {
            $this->db = new Database();
            $this->api = new Api();
        }
        else
        {
            echo 'Unauthorized';
            die;
        }
    }

    public function offers()
    {
        $result = [];
        $offers = $this->db->query("SELECT cr.room_code AS 'room_code', o.*, cat.value AS 'category', u.user_id AS 'u_user_id', u.first_name AS 'u_first_name', u.last_name AS 'u_last_name', u.class_id AS 'u_class_id', c.name AS 'c_name', b.book_ISBN AS 'b_book_ISBN', b.name AS 'b_name', b.author AS 'b_author' 
                                    FROM `offers` `o` 
                                    LEFT JOIN `books` `b` ON `o`.`book_ISBN` = `b`.`book_ISBN` 
                                    INNER JOIN `users` `u` ON `u`.`user_id` = `o`.`user_id` 
                                    LEFT JOIN `classes` `c` ON `c`.`class_id` = `u`.`class_id` 
                                    LEFT JOIN `class_room` `cr` ON `cr`.`class_id` = `u`.`class_id` 
                                    LEFT JOIN `auctions` `a` ON `a`.`offer_id` = `o`.`offer_id` 
                                    LEFT JOIN `categories` `cat` ON `b`.`category_id` = `cat`.`category_id` 
                                    WHERE a.auction_id IS NULL AND `c`.`name` = ?", [$_GET['trida']])->getResultArray();
        
        foreach($offers as $offer)
        {
            if(isset($result[$offer["user_id"]]))
            {
                array_push($result[$offer["user_id"]], $offer);
            }
            else
            {
                $result[$offer["user_id"]] = [
                    $offer
                ];
            }
        }

        echo json_encode($result);
    }

    public function classes()
    {
        $result = $this->db->query("SELECT c.name AS 'c_name', cr.room_code AS 'room_code'
                                    FROM `classes` `c` 
                                    INNER JOIN `class_room` `cr` ON `cr`.`class_id` = `c`.`class_id`")->getResultArray();

        echo json_encode($result);
    }
}