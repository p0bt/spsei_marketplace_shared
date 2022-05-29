<?php

class Offer extends BaseModel
{
    private $filters = "";
    private $limit_a, $limit_b;
    private $allowed_filters = [
        'search',
        'price',
        'category',
        'price_type',
    ];
    private $sql = [];

    private function url_has_filter()
    {
        if(isset($_GET))
        {
            foreach($_GET as $par)
            {
                if(in_array(array_search($par, $_GET), $this->allowed_filters)) 
                    return true;
            }
        }
        return false;
    }

    public function set_limit($a, $b)
    {
        $this->limit_a = $a;
        $this->limit_b = $b;
    }

    public function get_filters()
    {
        return $this->filters;
    }

    public function set_filters($search = null, $price_type = "vse", $price = null, $categories = [])
    {
        if(isset($search))
            $this->set_search($search);

        if(isset($price_type))
            $this->set_price($price_type, $price);
        
        if(isset($categories) && !empty($categories))
            $this->set_categories($categories);

        $this->filters = Database::join_sql($this->sql);
    }

    public function set_search($search)
    {
        $this->sql[] = "`b`.`name` LIKE '%".$search."%' OR `b`.`author` LIKE '%".$search."%' OR `o`.`name` LIKE '%".$search."%' OR `description` LIKE '%".$search."%'";
    }

    public function set_price($price_type, $price)
    {
        switch($price_type) 
        {
            case "aukce":
                $this->sql[] = "`a`.`auction_id` IS NOT NULL";
                break;
            
            case "pevna":
                if(isset($price)) 
                {
                    $price = explode(" ", $price);
                    $price_min = is_numeric($price[0]) ? $price[0] : 0;
                    $price_max = is_numeric($price[1]) ? $price[1] : 0;
                    $this->sql[] = "`price` >= ".$price_min." AND `price` <= ".$price_max;
                }
                break;

            case "vse":
            default:
                break;
        }
    }

    public function set_categories($categories)
    {
        $categories = implode(",", (array_map(function($value) {
            return "'$value'";
        }, $categories)));
        $this->sql[] = "`category` IN (".$categories.")";
    }

    public function read_filters()
    {
        // Filter out empty fields 
        $_GET = array_filter($_GET);
        // Product filtering
        if($this->url_has_filter())
        {
            // Set search
            if(isset($_GET['search']) && !empty($_GET['search']))
            {
                $this->set_search($_GET['search']);
            }

            // Set price
            $price_type = (isset($_GET['price_type']) && !empty($_GET['price_type'])) ? $_GET['price_type'] : "vse";
            $price = (isset($_GET['price']) && !empty($_GET['price'])) ? $_GET['price'] : null;
            $this->set_price($price_type, $price);

            // Set categories
            if(isset($_GET['category']) && !empty($_GET['category']))
                $this->set_categories($_GET['category']);

            // Prepare sql
            $this->filters = Database::join_sql($this->sql);
        }
    }

    public function post($data)
    {
        Database::query("INSERT INTO `offers` (`user_id`, `name`, `description`, `book_id`, `category`, `price`, `image_path`)
        VALUES (?, ?, ?, ?, ?, ?, ?)", [$data['user_id'], $data['name'], $data['description'], $data['book_id'], $data['category'], $data['price'], $data['image_path']]);
    }

    public function delete_by_id($offer_id)
    {
        Database::query("DELETE FROM `offers` WHERE `offer_id` = ?", [$offer_id]);
    }

    public function delete($user_id, $offer_id)
    {
        Database::query("DELETE FROM `offers` WHERE `user_id` = ? AND `offer_id` = ?", [$user_id, $offer_id]);
    }

    public function is_mine($user_id, $offer_id)
    {
        $result = Database::query("SELECT offer_id FROM `offers` WHERE `user_id` = ? AND `offer_id` = ?", [$user_id, $offer_id])->getRowArray();
        return $result;
    }

    public function get_all($order_by = null, $order = null)
    {
        $sql = "SELECT o.*, a.auction_id AS 'a_auction_id', a.start_date AS 'a_start_date', a.end_date AS 'a_end_date', b.book_id AS 'b_book_id', b.name AS 'b_name', b.author AS 'b_author' FROM `offers` `o` LEFT JOIN `books` `b` ON `o`.`book_id` = `b`.`book_id` LEFT JOIN `auctions` `a` ON `a`.`offer_id` = `o`.`offer_id`".((isset($this->filters) && strlen($this->filters) > 0) ? " WHERE".$this->filters : "");
        if(isset($order_by) && isset($order))
            $sql .= " ORDER BY `".$order_by."` ".$order;
        $sql .= (isset($this->limit_a) && isset($this->limit_b)) ? (" LIMIT ".$this->limit_a.", ".$this->limit_b) : ("");
        return Database::query($sql)->getResultArray();
    }

    public function delete_old()
    {
        $old_timestamp = time() - (OFFER_EXPIRATION_DAYS * 86400);
        return Database::query("DELETE FROM `offers` WHERE UNIX_TIMESTAMP(`date`) <= ?", [$old_timestamp]);
    }

    public function get_all_with_user_info()
    {
        $sql = "SELECT o.*, a.auction_id AS 'a_auction_id', a.start_date AS 'a_start_date', a.end_date AS 'a_end_date', u.email AS 'email', u.first_name AS 'first_name', u.last_name AS 'last_name', b.book_id AS 'b_book_id', b.name AS 'b_name', b.author AS 'b_author', c.name AS 'c_name' FROM `offers` `o` LEFT JOIN `books` `b` ON `o`.`book_id` = `b`.`book_id` LEFT JOIN `users` `u` ON `u`.`user_id` = `o`.`user_id` LEFT JOIN `classes` `c` ON `c`.`class_id` = `u`.`class_id` LEFT JOIN `auctions` `a` ON `a`.`offer_id` = `o`.`offer_id`";
        return Database::query($sql)->getResultArray();
    }

    public function get_by_id($offer_id)
    {
        return Database::query("SELECT o.*, a.auction_id AS 'a_auction_id', a.start_date AS 'a_start_date', a.end_date AS 'a_end_date', u.*, b.book_id AS 'b_book_id', b.name AS 'b_name', b.author AS 'b_author' FROM `offers` `o` LEFT JOIN `books` `b` ON `o`.`book_id` = `b`.`book_id` LEFT JOIN `users` `u` ON `u`.`user_id` = `o`.`user_id` LEFT JOIN `auctions` `a` ON `a`.`offer_id` = `o`.`offer_id` WHERE `o`.`offer_id` = ?", [$offer_id])->getRowArray();
    }

    public function get_from_user($user_id)
    {
        $sql = "SELECT o.*, a.auction_id AS 'a_auction_id', a.start_date AS 'a_start_date', a.end_date AS 'a_end_date', b.book_id AS 'b_book_id', b.name AS 'b_name', b.author AS 'b_author' FROM `offers` `o` LEFT JOIN `books` `b` ON `o`.`book_id` = `b`.`book_id` LEFT JOIN `auctions` `a` ON `a`.`offer_id` = `o`.`offer_id` WHERE `o`.`user_id` = ?".((isset($this->filters) && strlen($this->filters) > 0) ? " AND".$this->filters : "");
        $sql .= (isset($this->limit_a) && isset($this->limit_b)) ? (" LIMIT ".$this->limit_a.", ".$this->limit_b) : ("");
        return Database::query($sql, [$user_id])->getResultArray();
    }

    public function get_count()
    {
        $sql = "SELECT o.offer_id FROM `offers` `o` LEFT JOIN `books` `b` ON `o`.`book_id` = `b`.`book_id` LEFT JOIN `auctions` `a` ON `a`.`offer_id` = `o`.`offer_id`".((isset($this->filters) && strlen($this->filters) > 0) ? " WHERE".$this->filters : "");
        return Database::query($sql)->countAll();
    }

    public function get_count_from_user($user_id)
    {
        $sql = "SELECT o.offer_id FROM `offers` `o` LEFT JOIN `books` `b` ON `o`.`book_id` = `b`.`book_id` LEFT JOIN `auctions` `a` ON `a`.`offer_id` = `o`.`offer_id` WHERE `o`.`user_id` = ?".((isset($this->filters) && strlen($this->filters) > 0) ? " AND".$this->filters : "");
        return Database::query($sql, [$user_id])->countAll();
    }


    public function get_most_expensive()
    {
        return Database::query("SELECT MAX(`price`) AS 'price' FROM `offers`")->getRowArray()['price'];
    }
}