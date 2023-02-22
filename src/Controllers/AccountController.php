<?php
namespace SpseiMarketplace\Controllers;

use SpseiMarketplace\Core\Validator;
use SpseiMarketplace\Core\Database;
use SpseiMarketplace\Core\HelperFunctions;
use SpseiMarketplace\Core\Pagination;
use SpseiMarketplace\Models\Offer;
use SpseiMarketplace\Models\Auction;
use SpseiMarketplace\Models\Category;
use SpseiMarketplace\Models\User;
use SpseiMarketplace\Models\SchoolClass;

class AccountController extends BaseController
{
    private $db;

    private $validator;
    private $offers_model;
    private $auctions_model;
    private $users_model;
    private $classes_model;
    private $categories_model;

    private $account_data;

    public function __construct()
    {
        $this->db = new Database();

        $this->validator = new Validator();
        $this->offers_model = new Offer();
        $this->auctions_model = new Auction();
        $this->users_model = new User();
        $this->classes_model = new SchoolClass();
        $this->categories_model = new Category();

        // Get account info
        $this->account_data['account'] = $this->users_model->get_by_id($_SESSION['user_data']['user_id']);

        // Bid count
        $bid_count = $this->db->query("SELECT COUNT(auction_id) AS 'count' FROM auctions WHERE end_date >= NOW() AND user_id = ?", [$_SESSION['user_data']['user_id']])->countAll();

        // Get offers for my account
        $this->account_data['offers'] = $this->offers_model->get_from_user($_SESSION['user_data']['user_id'], "date", "DESC");
        
        // Get overview info
        // https://www.php.net/manual/en/function.array-filter.php
        $this->account_data['overview'] = [
            "offer_count" => $this->offers_model->get_count_from_user($_SESSION['user_data']['user_id']),
            "auction_count" => count(array_filter($this->account_data['offers'], function($value) {
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
    
                        $this->account_data['wishlist'][] = [
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
        $this->account_data['classes'] = $this->classes_model->get_all();
        $this->account_data['categories'] = $this->categories_model->get_all();
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
                    $this->db->query("UPDATE `users` SET `first_name` = ?, `last_name` = ?, `class_id` = ?, `last_update` = ? WHERE `user_id` = ?", [$_POST['first_name'], $_POST['last_name'], $_POST['class'], date('Y-m-d H:i:s', time()), $account['user_id']]);
                    $account = $this->db->query("SELECT * FROM `users` WHERE `user_id` = ?", [$_SESSION['user_data']['user_id']])->getRowArray();
                    HelperFunctions::setAlert("success-profile", "Váš profil byl aktualizován");
                }
            }
            else
            {
                HelperFunctions::setAlert("error-profile", "Profil můžete aktualizovat maximálně 1x denně");
            }
            
            $account = $this->users_model->get_by_id($_SESSION['user_data']['user_id']);
        }
        if(isset($_GET['delete']))
        {
            if($this->offers_model->is_mine($_SESSION['user_data']['user_id'], $_GET['delete']))
            {
                $this->offers_model->delete($_SESSION['user_data']['user_id'], $_GET['delete']);
                HelperFunctions::setAlert("success-offer", "Nabídka byla odstraněna");
            }
        }

        $this->render("views/templates/header.php");
        $this->render("views/account/my_account.php", $this->account_data);
        $this->render("views/templates/footer.php");
    }

    public function tab_my_offers()
    {
        // Read filters
        $this->offers_model->read_filters();
        $tmp_offer_count = $this->offers_model->get_count_from_user($_SESSION['user_data']['user_id']);

        // My offers pagination (5 offers per page)
        $this->account_data['my_offfers_pagination'] = new Pagination($tmp_offer_count, "muj-ucet", "po");
        $this->account_data['my_offfers_pagination']->set_items_per_page(5);
        $this->offers_model->set_limit($this->account_data['my_offfers_pagination']->get_limit_a(), $this->account_data['my_offfers_pagination']->get_limit_b());

        // Get offers for my account
        $this->account_data['offers'] = $this->offers_model->get_from_user($_SESSION['user_data']['user_id'], "date", "DESC");

        $this->render("views/account/my_offers.php", $this->account_data);
    }

    public function tab_my_won_auctions()
    {
        // Get won auctions for my account
        $won_auctions = $this->auctions_model->get_won_auctions_from_user($_SESSION['user_data']['user_id']);
        $this->account_data['won_auctions'] = [];
        foreach($won_auctions as $won_auction)
        {
            // Lazy for SQL join :D (Sorry for performance impact)
            $this->account_data['won_auctions'][] = array_merge($won_auction, $this->offers_model->get_by_id($won_auction['offer_id']));
        }

        // Won auctions pagination (5 offers per page)
        $this->account_data['my_won_auctions_pagination'] = new Pagination(count($this->account_data['won_auctions']), "muj-ucet", "pa");
        $this->account_data['my_won_auctions_pagination']->set_items_per_page(5);
        $this->offers_model->set_limit($this->account_data['my_won_auctions_pagination']->get_limit_a(), $this->account_data['my_won_auctions_pagination']->get_limit_b());

        $this->render("views/account/my_won_auctions.php", $this->account_data);
    }

    public function change_password()
    {
        if ($_POST)
        {
            $this->validator->addMultipleRules([
                'password' => 'required|min_length[8]|max_length[255]',
                'new_password' => 'required|min_length[8]|max_length[255]',
                'new_cpassword' => 'required|min_length[8]|max_length[255]|matches[new_password]',
            ]);
            // Validate all of the password inputs
            // And check that user typed his old password correctly
            if ($this->validator->run() && password_verify($_POST['password'], $_SESSION['user_data']['password']))
            {
                $password = password_hash($_POST['new_password'], AuthController::hashing_algorithm);
                // Update user's password with a new one
                $this->users_model->update_password($password, $_SESSION['user_data']['user_id']);

                HelperFunctions::setAlert("success-password", "Heslo bylo změněno");
            }
            else
            {
                HelperFunctions::setAlert("error-password", "Zkontrolujte chyby a zkuste to znovu");
            }
        }
        
        header("Location: /muj-ucet");
        die;
    }
}