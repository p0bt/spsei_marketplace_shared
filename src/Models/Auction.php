<?php
namespace SpseiMarketplace\Models;

use SpseiMarketplace\Core\Database;

class Auction extends BaseModel
{
    public function post($data)
    {
        Database::query("INSERT INTO `auctions` 
                        (`offer_id`, `start_date`, `end_date`)
                        VALUES (?, ?, ?)", 
                        [$data['offer_id'], $data['start_date'], $data['end_date']]);
    }

    public function get_all()
    {
        return Database::query("SELECT * 
                                FROM `auctions`")->getResultArray();
    }

    public function get_all_with_filters($start, $length, $search, $status)
    {
        $base_sql = "SELECT * 
                    FROM `auctions`";
        $sql_where = [];

        if(isset($search) && !empty($search))
        {
            $sql_where[] = "`offer_id` LIKE '%$search%' 
                    OR `top_bid` LIKE '%$search%' 
                    OR `start_date` LIKE '%$search%'  
                    OR `end_date` LIKE '%$search%'";
        }

        if(isset($status) && !empty($status))
        {
            switch($status)
            {
                case 'not_started':
                    $sql_where[] = "CURRENT_TIMESTAMP() < `start_date`";
                    break;

                case 'in_progress':
                    $sql_where[] = "CURRENT_TIMESTAMP() >= `start_date` AND CURRENT_TIMESTAMP() <= `end_date`";
                    break;

                case 'ended':
                    $sql_where[] = "CURRENT_TIMESTAMP() > `end_date`";
                    break;

                case 'all':
                default:
                    break;
            }
        }

        $final_sql = $base_sql.(!empty($sql_where) ? " WHERE ".Database::join_sql($sql_where) : "")." LIMIT ".$start.", ".$length."";
        return Database::query($final_sql)->getResultArray();
    }

    public function get_count()
    {
        return Database::query("SELECT auction_id 
                                FROM `auctions`")->countAll();
    }

    public function get_old_auctions()
    {
        return Database::query("SELECT * 
                                FROM `auctions` 
                                WHERE CURRENT_TIMESTAMP() > `end_date`")->getResultArray();
    }

    public function get_running_auctions()
    {
        return Database::query("SELECT * 
                                FROM `auctions` 
                                WHERE CURRENT_TIMESTAMP() >= `start_date` AND CURRENT_TIMESTAMP() <= `end_date`")->getResultArray();
    }

    public function get_current_state($auction_id)
    {
        return Database::query("SELECT `a`.`top_bid` AS 'top_bid', `u`.`user_id` AS 'user_id', `u`.`first_name` AS 'first_name', `u`.`last_name` AS 'last_name' 
                                FROM `auctions` `a` 
                                LEFT JOIN `users` `u` ON `u`.`user_id` = `a`.`user_id` 
                                WHERE `auction_id` = ?", 
                                [$auction_id])->getRowArray();
    }

    public function rise_price($auction_id, $new_price, $user_id)
    {
        Database::query("UPDATE `auctions` 
                        SET `top_bid` = ?, `user_id` = ? 
                        WHERE `auction_id` = ?", 
                        [$new_price, $user_id, $auction_id]);
    }

    public function is_mine($user_id, $auction_id)
    {
        $result = Database::query("SELECT auction_id 
                                    FROM `auctions` 
                                    WHERE `user_id` = ? AND `auction_id` = ?", 
                                    [$user_id, $auction_id])->getRowArray();
        return $result;
    }

    public function get_won_auctions_from_user($user_id)
    {
        return Database::query("SELECT * 
                                FROM `auctions` 
                                WHERE CURRENT_TIMESTAMP() > `end_date` AND user_id = ?",
                                [$user_id])->getResultArray();
    }
}