<?php

class AdminController extends BaseController
{
    private $validator;
    private $users_model;
    private $offers_model;
    private $classes_model;
    private $books_model;
    private $class_room_model;
    private $banned_ip_model;
    private $notifications_model;
    private $auctions_model;

    public function __construct()
    {
        $this->validator = new Validator();
        $this->users_model = new User();
        $this->offers_model = new Offer();
        $this->classes_model = new SchoolClass();
        $this->books_model = new Book();
        $this->class_room_model = new ClassRoom();
        $this->banned_ip_model = new BannedIp();
        $this->notifications_model = new Notification();
        $this->auctions_model = new Auction();
        $this->api_model = new Api();
    }

    public function dashboard()
    {
        // Data for cards ands charts
        $data['cards']['offer_count'] = Database::query("SELECT COUNT(offer_id) AS 'count' FROM `offers`")->getRowArray()['count'];
        $data['cards']['auction_count'] = $this->auctions_model->get_count();
        $data['cards']['user_count'] = Database::query("SELECT COUNT(user_id) AS 'count' FROM `users`")->getRowArray()['count'];
        $data['cards']['banned_ip_count'] = Database::query("SELECT COUNT(bi_id) AS 'count' FROM `banned_ips`")->getRowArray()['count'];
        $data['cards']['class_room_percentage'] = ceil((Database::query("SELECT COUNT(cr_id) AS 'count' FROM `class_room`")->getRowArray()['count'] / Database::query("SELECT COUNT(class_id) AS 'count' FROM `classes`")->getRowArray()['count']) * 100);
        
        $data['charts']['offers_by_date'] = Database::query("SELECT COUNT(offer_id) AS 'count', DATE(`date`) AS 'date' FROM `offers` GROUP BY DATE(`date`)")->getResultArray();
        $data['charts']['offer_book_count'] = Database::query("SELECT COUNT(offer_id) AS 'count' FROM `offers` WHERE `book_id` IS NOT NULL")->getRowArray()['count'];
        $data['charts']['offer_other_count'] =  $data['cards']['offer_count'] -  $data['charts']['offer_book_count'];

        $this->offers_model->set_limit(0, 5);
        $data['widgets']['last_offers'] = $this->offers_model->get_all("date", "DESC");
        $data['widgets']['users'] = $this->users_model->get_all();
        
        if($_POST)
        {
            $this->validator->addMultipleRules([
                'target' => 'required',
                'content' => 'required|min_length[3]|max_length[255]',
            ]);
            if ($this->validator->run())
            {
                $post_data = [
                    "target" => $_POST["target"],
                    "content" => $_POST["content"],
                ];

                $this->notifications_model->post($post_data);
                HelperFunctions::setAlert("success", "Oznámení bylo odesláno");
            }
        }

        $this->render("views/templates/admin/header.php");
        $this->render("views/admin/dashboard.php", $data);
        $this->render("views/templates/admin/footer.php");
    }
    
    public function user_maintenance()
    {
        if(isset($_GET['ban']) && $_GET['ban'] != "null")
        {
            $this->users_model->ban_ip($_GET['ban']);
            unset($_GET['ban']);
        }
        else if(isset($_GET['unban']) && $_GET['unban'] != "null")
        {
            $this->users_model->unban_ip($_GET['unban']);
            unset($_GET['unban']);
        }

        $data['users'] = $this->users_model->get_all();
        
        $this->render("views/templates/admin/header.php");
        $this->render("views/admin/user_maintenance.php", $data);
        $this->render("views/templates/admin/footer.php");
    }

    public function offer_maintenance()
    {
        if(isset($_GET['delete']))
        {
            $this->offers_model->delete_by_id($_GET['delete']);
        }

        $data['offers'] = $this->offers_model->get_all_with_user_info();

        $this->render("views/templates/admin/header.php");
        $this->render("views/admin/offer_maintenance.php", $data);
        $this->render("views/templates/admin/footer.php");
    }

    public function auction_maintenance()
    {
        $data['auctions'] = $this->auctions_model->get_all();

        $this->render("views/templates/admin/header.php");
        $this->render("views/admin/auction_maintenance.php", $data);
        $this->render("views/templates/admin/footer.php");
    }

    public function get_auctions()
    {
        echo json_encode($this->auctions_model->get_all_with_filters($_POST['start'], $_POST['length'], $_POST['search']['value'], $_POST['auction_status']));
    }

    public function class_maintenance()
    {
        if($_POST)
        {
            $this->validator->addMultipleRules([
                'name' => 'required|max_length[3]',
            ]);
            if ($this->validator->run())
            {
                $post_data = [
                    'name' => $_POST['name'],
                ];
                $this->classes_model->post($post_data);
            }
        }
        if(isset($_GET['delete']))
        {
            $this->classes_model->delete_by_id($_GET['delete']);
        }

        $data['classes'] = $this->classes_model->get_all();

        $this->render("views/templates/admin/header.php");
        $this->render("views/admin/class_maintenance.php", $data);
        $this->render("views/templates/admin/footer.php");
    }

    public function book_maintenance()
    {
        if($_POST)
        {
            $this->validator->addMultipleRules([
                'name' => 'required|max_length[50]',
                'author' => 'required|max_length[50]',
            ]);
            if ($this->validator->run())
            {
                $post_data = [
                    'name' => $_POST['name'],
                    'author' => $_POST['author'],
                ];
                $this->books_model->post($post_data);
            }
        }
        if(isset($_GET['delete']))
        {
            $this->books_model->delete_by_id($_GET['delete']);
        }

        $data['books'] = $this->books_model->get_all();

        $this->render("views/templates/admin/header.php");
        $this->render("views/admin/book_maintenance.php", $data);
        $this->render("views/templates/admin/footer.php");
    }

    public function cr_maintenance()
    {
        if($_POST)
        {
            $this->validator->addMultipleRules([
                'class_id' => 'required|in_not_unique[classes.class_id]',
                'room_code' => 'min_length[4]|max_length[4]',
            ]);
            if ($this->validator->run())
            {
                $post_data = [
                    'class_id' => $_POST['class_id'],
                    'room_code' => $_POST['room_code'],
                ];
                $this->class_room_model->replace($post_data);
            }
        }
        if(isset($_GET['delete']))
        {
            $this->class_room_model->delete_by_id($_GET['delete']);
        }
        $data['crs'] = $this->class_room_model->get_all();
        $data['classes'] = $this->classes_model->get_all();

        $this->render("views/templates/admin/header.php");
        $this->render("views/admin/cr_maintenance.php", $data);
        $this->render("views/templates/admin/footer.php");
    }

    public function banned_ip_maintenance()
    {
        if(isset($_GET['unban']) && $_GET['unban'] != "null")
        {
            $this->banned_ip_model->delete_by_id($_GET['unban']);
        }

        $data['banned_ips'] = $this->banned_ip_model->get_all();

        $this->render("views/templates/admin/header.php");
        $this->render("views/admin/banned_ip_maintenance.php", $data);
        $this->render("views/templates/admin/footer.php");
    }

    public function api_key_maintenance()
    {
        if($_POST)
        {
            $without_expiration = (isset($_POST["without_expiration"]) && $_POST["without_expiration"] == "on");

            $this->validator->addMultipleRules([
                'api_key' => 'required|max_length[255]|is_unique[api_keys.api_key]',
                'description' => 'required|max_length[65535]',
            ]);
            // Validate date only if "without expiration date" checkbox isn't checked
            if(!$without_expiration)
                $this->validator->addRules('expiration_date', 'required|is_valid_datetime');

            if ($this->validator->run())
            {
                $post_data = [
                    'api_key' => $_POST['api_key'],
                    'description' => $_POST['description'],
                    'expiration_date' => ((isset($_POST['expiration_date']) && !empty($_POST['expiration_date'])) ? date("Y-m-d H:i:s", strtotime($_POST['expiration_date'])) : NULL),
                ];
                $this->api_model->post($post_data);
            }
        }
        if(isset($_GET['delete']))
        {
            $this->api_model->delete_by_id($_GET['delete']);
        }

        $data['api_keys'] = $this->api_model->get_all_keys();

        $this->render("views/templates/admin/header.php");
        $this->render("views/admin/api_key_maintenance.php", $data);
        $this->render("views/templates/admin/footer.php");
    }
}