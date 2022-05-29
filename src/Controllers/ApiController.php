<?php

class ApiController extends BaseController
{
    private $api;

    public function __construct()
    {
        if(isset($_GET['key']) && !empty($_GET['key']) && Api::verify_key($_GET['key']))
        {
            $this->api = new Api();
        }
        else
        {
            die;
        }
    }

    public function offers()
    {
        $result = [];
        $offers = Database::query("SELECT cr.room_code AS 'room_code', o.*, a.auction_id AS 'a_auction_id', a.start_date AS 'a_start_date', a.end_date AS 'a_end_date', u.user_id AS 'u_user_id', u.first_name AS 'u_first_name', u.last_name AS 'u_last_name', u.class_id AS 'u_class_id', c.name AS 'c_name', b.book_id AS 'b_book_id', b.name AS 'b_name', b.author AS 'b_author' FROM `offers` `o` LEFT JOIN `books` `b` ON `o`.`book_id` = `b`.`book_id` LEFT JOIN `users` `u` ON `u`.`user_id` = `o`.`user_id` LEFT JOIN `classes` `c` ON `c`.`class_id` = `u`.`class_id`  LEFT JOIN `class_room` `cr` ON `cr`.`class_id` = `u`.`class_id` LEFT JOIN `auctions` `a` ON `a`.`offer_id` = `o`.`offer_id`")->getResultArray();
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
}