<?php
namespace SpseiMarketplace\Models;

class Offer extends BaseModel
{
    private $filters = "";
    private $limit_a, $limit_b;
    private $allowed_filters = [
        'search',
        'price',
        'category',
        'price_type',
        'major',
        'grade',
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

        $this->filters = $this->db->join_sql($this->sql);
    }

    public function set_search($search)
    {
        $this->sql[] = "`b`.`book_ISBN` LIKE '%".$search."%' OR `b`.`name` LIKE '%".$search."%' OR `b`.`author` LIKE '%".$search."%' OR `nb`.`name` LIKE '%".$search."%' OR `description` LIKE '%".$search."%'";
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
                    $price_min = is_numeric($price[0]) ? $price[0] : 1;
                    $price_max = is_numeric($price[1]) ? $price[1] : MAX_OFFER_PRICE;
                    $this->sql[] = "`price` >= ".$price_min." AND `price` <= ".$price_max;
                }
                break;

            case "zdarma":
                $this->sql[] = "`price` = 0 AND `a`.`auction_id` IS NULL";
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
        $sql = "`cat`.`value` IN (".$categories.")";

        if(in_array("'sesity'", explode(",", $categories)))
            $sql .= " OR `cat`.`value` IS NULL";

        $this->sql[] = $sql;
    }

    public function set_major($major)
    {
        if($major != 3) // All majors
            $this->sql[] = "`b`.`major_id` = ".$major." OR `nb`.`major_id` = ".$major;
    }

    public function set_grade($grade)
    {
        if($grade != 0) // All grades
            $this->sql[] = "`b`.`grade` = ".$grade." OR `nb`.`grade` = ".$grade;
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

            // Set major
            if(isset($_GET['major']) && !empty($_GET['major']))
                $this->set_major($_GET['major']);

            // Set grade
            if(isset($_GET['grade']) && !empty($_GET['grade']))
                $this->set_grade($_GET['grade']);

            // Prepare sql
            $this->filters = $this->db->join_sql($this->sql);
        }
    }

    public function post($data)
    {
        $this->db->query("INSERT INTO `offers` 
                        (`user_id`, `notebook_id`, `book_ISBN`, `description`, `price`, `image_path`)
                        VALUES (?, ?, ?, ?, ?, ?)", 
                        [$data['user_id'], $data['notebook_id'], $data['book_ISBN'], $data['description'], $data['price'], $data['image_path']]);
        
        return $this->db->get_last_inserted_id();
    }

    public function delete_by_id($offer_id)
    {
        $this->db->query("DELETE FROM `offers` 
                        WHERE `offer_id` = ?", 
                        [$offer_id]);
    }

    public function delete($user_id, $offer_id)
    {
        $this->db->query("DELETE FROM `offers` 
                        WHERE `user_id` = ? AND `offer_id` = ?", 
                        [$user_id, $offer_id]);
    }

    public function is_mine($user_id, $offer_id)
    {
        $result = $this->db->query("SELECT offer_id 
                                FROM `offers` 
                                WHERE `user_id` = ? AND `offer_id` = ?", 
                                [$user_id, $offer_id])->getRowArray();
        return $result;
    }

    public function get_all($order_by = null, $order = null)
    {
        $sql = "SELECT o.*, nb.name AS 'name', nb.notebook_id AS 'nb_id', a.auction_id AS 'a_auction_id', a.start_date AS 'a_start_date', a.end_date AS 'a_end_date', b.book_ISBN AS 'b_book_ISBN', b.name AS 'b_name', b.author AS 'b_author', IFNULL(cat.name, 'Sešity') AS 'cat_name', IFNULL(cat.value, 'sesity') AS 'cat_value' 
                FROM `offers` `o` 
                LEFT JOIN `books` `b` ON `o`.`book_ISBN` = `b`.`book_ISBN`
                LEFT JOIN `notebooks` `nb` ON `nb`.`notebook_id` = `o`.`notebook_id` 
                LEFT JOIN `auctions` `a` ON `a`.`offer_id` = `o`.`offer_id` 
                LEFT JOIN `categories` `cat` ON `b`.`category_id` = `cat`.`category_id`".((isset($this->filters) && strlen($this->filters) > 0) ? " WHERE".$this->filters : "");
        
        if(isset($order_by) && isset($order))
        {

            if(str_contains($order_by, "."))
            {
                $arr = explode(".", $order_by);
                $sql .= " ORDER BY `".$arr[0]."`.`".$arr[1]."` ".$order;
            }
            else 
            {
                $sql .= " ORDER BY `".$order_by."` ".$order;
            }
        }
        $sql .= (isset($this->limit_a) && isset($this->limit_b)) ? (" LIMIT ".$this->limit_a.", ".$this->limit_b) : ("");
        return $this->db->query($sql)->getResultArray();
    }

    public function delete_old()
    {
        $old_timestamp = time() - (OFFER_EXPIRATION_DAYS * 86400);
        return $this->db->query("DELETE FROM `offers` 
                                WHERE UNIX_TIMESTAMP(`date`) <= ?",
                                [$old_timestamp]);
    }

    public function get_all_with_user_info()
    {
        $sql = "SELECT o.*, nb.name AS 'name', nb.notebook_id AS 'nb_id', a.auction_id AS 'a_auction_id', a.start_date AS 'a_start_date', a.end_date AS 'a_end_date', u.email AS 'email', u.first_name AS 'first_name', u.last_name AS 'last_name', b.book_ISBN AS 'b_book_ISBN', b.name AS 'b_name', b.author AS 'b_author', c.name AS 'c_name', IFNULL(cat.name, 'Sešity') AS 'cat_name', IFNULL(cat.value, 'sesity') AS 'cat_value'  
                FROM `offers` `o` 
                LEFT JOIN `books` `b` ON `o`.`book_ISBN` = `b`.`book_ISBN` 
                LEFT JOIN `notebooks` `nb` ON `nb`.`notebook_id` = `o`.`notebook_id` 
                INNER JOIN `users` `u` ON `u`.`user_id` = `o`.`user_id` 
                LEFT JOIN `classes` `c` ON `c`.`class_id` = `u`.`class_id` 
                LEFT JOIN `auctions` `a` ON `a`.`offer_id` = `o`.`offer_id` 
                LEFT JOIN `categories` `cat` ON `b`.`category_id` = `cat`.`category_id`";
        return $this->db->query($sql)->getResultArray();
    }

    public function get_by_id($offer_id)
    {
        return $this->db->query("SELECT o.*, nb.name AS 'name', nb.notebook_id AS 'nb_id', a.auction_id AS 'a_auction_id', a.start_date AS 'a_start_date', a.end_date AS 'a_end_date', u.*, b.book_ISBN AS 'b_book_ISBN', b.name AS 'b_name', b.author AS 'b_author', IFNULL(cat.name, 'Sešity') AS 'cat_name', IFNULL(cat.value, 'sesity') AS 'cat_value' 
                                FROM `offers` `o` 
                                LEFT JOIN `books` `b` ON `o`.`book_ISBN` = `b`.`book_ISBN` 
                                LEFT JOIN `notebooks` `nb` ON `nb`.`notebook_id` = `o`.`notebook_id` 
                                INNER JOIN `users` `u` ON `u`.`user_id` = `o`.`user_id` 
                                LEFT JOIN `auctions` `a` ON `a`.`offer_id` = `o`.`offer_id` 
                                LEFT JOIN `categories` `cat` ON `b`.`category_id` = `cat`.`category_id` 
                                WHERE `o`.`offer_id` = ?", 
                                [$offer_id])->getRowArray();
    }

    public function get_from_user($user_id, $order_by = null, $order = null)
    {
        $sql = "SELECT o.*, nb.name AS 'name', nb.notebook_id AS 'nb_id', a.auction_id AS 'a_auction_id', a.start_date AS 'a_start_date', a.end_date AS 'a_end_date', b.book_ISBN AS 'b_book_ISBN', b.name AS 'b_name', b.author AS 'b_author', IFNULL(cat.name, 'Sešity') AS 'cat_name', IFNULL(cat.value, 'sesity') AS 'cat_value' 
                FROM `offers` `o` 
                LEFT JOIN `books` `b` ON `o`.`book_ISBN` = `b`.`book_ISBN` 
                LEFT JOIN `notebooks` `nb` ON `nb`.`notebook_id` = `o`.`notebook_id` 
                LEFT JOIN `auctions` `a` ON `a`.`offer_id` = `o`.`offer_id` 
                LEFT JOIN `categories` `cat` ON `b`.`category_id` = `cat`.`category_id` 
                WHERE `o`.`user_id` = ?".((isset($this->filters) && strlen($this->filters) > 0) ? " AND".$this->filters : "");
                
        if(isset($order_by) && isset($order))
        {

            if(str_contains($order_by, "."))
            {
                $arr = explode(".", $order_by);
                $sql .= " ORDER BY `".$arr[0]."`.`".$arr[1]."` ".$order;
            }
            else 
            {
                $sql .= " ORDER BY `".$order_by."` ".$order;
            }
        }
        
        $sql .= (isset($this->limit_a) && isset($this->limit_b)) ? (" LIMIT ".$this->limit_a.", ".$this->limit_b) : ("");
        return $this->db->query($sql, [$user_id])->getResultArray();
    }

    public function get_count()
    {
        $sql = "SELECT o.offer_id 
                FROM `offers` `o` 
                LEFT JOIN `books` `b` ON `o`.`book_ISBN` = `b`.`book_ISBN`
                LEFT JOIN `notebooks` `nb` ON `nb`.`notebook_id` = `o`.`notebook_id` 
                LEFT JOIN `auctions` `a` ON `a`.`offer_id` = `o`.`offer_id` 
                LEFT JOIN `categories` `cat` ON `b`.`category_id` = `cat`.`category_id`".((isset($this->filters) && strlen($this->filters) > 0) ? " WHERE".$this->filters : "");
        return $this->db->query($sql)->countAll();
    }

    public function get_count_from_user($user_id)
    {
        $sql = "SELECT o.offer_id 
                FROM `offers` `o` 
                LEFT JOIN `books` `b` ON `o`.`book_ISBN` = `b`.`book_ISBN` 
                LEFT JOIN `notebooks` `nb` ON `nb`.`notebook_id` = `o`.`notebook_id` 
                LEFT JOIN `auctions` `a` ON `a`.`offer_id` = `o`.`offer_id` 
                LEFT JOIN `categories` `cat` ON `b`.`category_id` = `cat`.`category_id` 
                WHERE `o`.`user_id` = ?".((isset($this->filters) && strlen($this->filters) > 0) ? " AND".$this->filters : "");
        return $this->db->query($sql, [$user_id])->countAll();
    }


    public function get_most_expensive()
    {
        return $this->db->query("SELECT MAX(`price`) AS 'price' 
                                FROM `offers`")->getRowArray()['price'];
    }

    public function update($data, $offer_id)
    {
        return $this->db->query("UPDATE `offers` 
                                SET user_id = ?,
                                    notebook_id = ?,
                                    book_ISBN = ?,
                                    `description` = ?,
                                    price = ?,
                                    image_path = ?,
                                    `date` = ?
                                WHERE offer_id = ?", 
                                [$data['user_id'], $data['notebook_id'], $data['book_ISBN'], $data['description'], $data['price'], $data['image_path'], $data['date'], $offer_id]);
    }
}