<?php

class AccountController extends BaseController
{
    private $validator;
    private $offers_model;
    private $users_model;
    private $classes_model;

    public function __construct()
    {
        $this->validator = new Validator();
        $this->offers_model = new Offer();
        $this->users_model = new User();
        $this->classes_model = new SchoolClass();
    }

    public function my_account()
    {
        $account = $this->users_model->get_by_id($_SESSION['user_data']['user_id']);

        if($_POST)
        {
            if(date("Y-m-d", strtotime($account['last_update'])) < date("Y-m-d", time()))
            {
                $this->validator->addMultipleRules([
                    'first_name' => 'permit_empty|min_length[2]|max_length[50]',
                    'last_name' => 'permit_empty|min_length[2]|max_length[50]',
                    'class' => 'permit_empty|is_not_unique[classes.class_id]',
                ]);
                if ($this->validator->run())
                {
                    Database::query("UPDATE `users` SET `first_name` = ?, `last_name` = ?, `class_id` = ?, `last_update` = ? WHERE `user_id` = ?", [$_POST['first_name'], $_POST['last_name'], $_POST['class'], date('Y-m-d H:i:s', time()), $account['user_id']]);
                    $account = Database::query("SELECT * FROM `users` WHERE `user_id` = ?", [$_SESSION['user_data']['user_id']])->getRowArray();
                    HelperFunctions::setAlert("success-profile", "Váš profil byl aktualizován");
                }
            }
            else
            {
                HelperFunctions::setAlert("error-profile", "Profil můžete aktualizovat maximálně 1x denně");
            }
        }
        if(isset($_GET['delete']))
        {
            if($this->offers_model->is_mine($_SESSION['user_data']['user_id'], $_GET['delete']))
            {
                $this->offers_model->delete($_SESSION['user_data']['user_id'], $_GET['delete']);
                HelperFunctions::setAlert("success-offer", "Nabídka byla odstraněna");
            }
        }

        // Read filters
        $this->offers_model->read_filters();
        $tmp_offer_count = $this->offers_model->get_count_from_user($_SESSION['user_data']['user_id']);

        $bid_count = Database::query("SELECT COUNT(auction_id) AS 'count' FROM auctions WHERE end_date >= NOW() AND user_id = ?", [$_SESSION['user_data']['user_id']])->countAll();

        // Get account info
        $data['account'] = $this->users_model->get_by_id($_SESSION['user_data']['user_id']);

        // Pagination (5 offers per page)
        $data['pagination'] = new Pagination($tmp_offer_count, "muj-ucet", "p");
        $data['pagination']->set_items_per_page(5);
        $this->offers_model->set_limit($data['pagination']->get_limit_a(), $data['pagination']->get_limit_b());

        // Get offers for my account
        $data['offers'] = $this->offers_model->get_from_user($_SESSION['user_data']['user_id']);

        // Get overview info
        $data['overview'] = [
            "offer_count" => $tmp_offer_count,
            "auction_count" => count(array_filter($data['offers'], function($value) {
                                        return (isset($value['a_auction_id']) && (strlen($value['a_auction_id']) > 0)) ;
                                    })),
            "bid_count" => $bid_count,
            "fav_count" => isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0,
        ];
        
        // Get last 5 offers from wishlist
        if(isset($_SESSION['wishlist']))
        {
            for($i = 0; $i < 5; $i ++)
            {
                if(isset($_SESSION['wishlist'][$i]))
                {
                    $offer = $this->offers_model->get_by_id($_SESSION['wishlist'][$i]);
                    if($offer)
                    {
                        $name = $offer['name'];
                    
                        if(strlen($offer['b_name']) > 0)
                        {
                            $name = $offer['b_name'].' ('.$offer['b_author'].')';
                        }
                            
                        $thumbnail = '/assets/images/no_image.png';
    
                        if(is_dir(SITE_PATH.'/uploads/'.$offer['image_path']))
                        {
                            $images = array_values(array_diff(scandir(SITE_PATH.'/uploads/'.$offer['image_path']), ['.', '..']));
                            $thumbnail = '/uploads/'.$offer['image_path'].'/'.$images[0];
                        }
    
                        $data['wishlist'][] = [
                            "id" => $offer['offer_id'],
                            "name" => $name,
                            "thumbnail" => $thumbnail,
                        ];
                    }
                    else
                    {
                        unset($_SESSION['wishlist'][$i]);
                    }
                }
            }
        }
        // Get classes
        $data['classes'] = $this->classes_model->get_all();

        $this->render("views/templates/header.php");
        $this->render("views/account/my_account.php", $data);
        $this->render("views/templates/footer.php");
    }
}