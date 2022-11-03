<?php
namespace SpseiMarketplace\Controllers;

use SpseiMarketplace\Core\Validator;
use SpseiMarketplace\Core\Filter;
use SpseiMarketplace\Models\Offer;

class WishlistController extends BaseController
{
    private $validator;
    private $offers_model;

    public function __construct()
    {
        $this->validator = new Validator();
        $this->offers_model = new Offer();
        $this->create_basket();
    }

    public function wishlist()
    {
        $data['wishlist_items'] = [];
        $data['suggestions']["offers"] = [];
        $data['suggestions']["auctions"] = [];
        $data['auction_count'] = 0;

        if(isset($_SESSION['wishlist']))
        {
            foreach($_SESSION['wishlist'] as $key => $offer_id)
            {
                $offer = $this->offers_model->get_by_id($offer_id);
                // If offer is auction, increment auction count
                if(isset($offer["a_auction_id"]) && !empty($offer["a_auction_id"]))
                    $data['auction_count']++;

                // Try to find similar offers/auctions for suggestion
                $name = $offer['name'];
                if(isset($offer['b_name']) && !empty($offer['b_name']))
                {
                    $name = $offer['b_name'].' ('.$offer['b_author'].')';
                }

                // Split offer name to words and find similar offers
                $words_in_name = explode(" ", $name);

                foreach($words_in_name as $word)
                {
                    // Get suggested offer
                    $this->offers_model = new Offer();
                    $this->offers_model->set_filters($word, "pevna", (intval($offer["price"]) - SUGGESTIONS_PRICE_TOLERANCE)." ".(intval($offer["price"]) + SUGGESTIONS_PRICE_TOLERANCE));
                    $tmp_offers = $this->offers_model->get_all("date", "DESC");
    
                    foreach($tmp_offers as $of)
                    {
                        if(isset($of) && !empty($of))
                        {
                            if(!in_array($of["offer_id"], $data['suggestions']['offers']) && !$this->offers_model->is_mine($_SESSION['user_data']['user_id'], $of["offer_id"]))
                                $data['suggestions']['offers'][$of["offer_id"]] = $of;
                        }
                    }
    
                    // Get suggested auctions
                    $this->offers_model = new Offer();
                    $this->offers_model->set_filters($word, "aukce");
                    $tmp_auctions = $this->offers_model->get_all();
                    
                    foreach($tmp_auctions as $auctions)
                    {
                        if(isset($auctions) && !empty($auctions))
                        {
                            if(!in_array($auctions["offer_id"], $data['suggestions']['auctions']) && !$this->offers_model->is_mine($_SESSION['user_data']['user_id'], $auctions["offer_id"]))
                                $data['suggestions']['auctions'][$auctions["offer_id"]] = $auctions;
                        }
                    }
                }

                // If offer stored in session still exists -> else delete from session wishlist
                if($offer)
                {
                    $data['wishlist_items'][] = $offer;
                }
                else
                {
                    unset($_SESSION['wishlist'][$key]);
                }
            }
        }

        $this->render("views/templates/header.php");
        $this->render("views/account/wish_list.php", $data);
        $this->render("views/templates/footer.php");
    }

    public function add_or_delete()
    {
        if($_POST && Filter::is_ajax_request())
        {
            $this->validator->addMultipleRules([
                'offer_id' => 'is_not_unique[offers.offer_id]',
            ]);
            if($this->validator->run() && !$this->offers_model->is_mine($_SESSION['user_data']['user_id'], $_POST['offer_id']))
            {
                $offer_id = $_POST['offer_id'];

                if(in_array($offer_id, $_SESSION['wishlist']))
                    $this->delete($offer_id);
                else
                    $this->add($offer_id);
            }
        }
    }

    private function add($offer_id)
    {   
        if(!in_array($offer_id, $_SESSION['wishlist']))
        {
            $_SESSION['wishlist'][count($_SESSION['wishlist'])] = $offer_id;
            echo json_encode([
                "action" => "add",
                "type" => "success",
                "content" => "Přidáno do oblíbených",
            ]);
            return;
        }
    }

    private function delete($offer_id)
    {
        if(isset($_SESSION['wishlist']) && in_array($offer_id, $_SESSION['wishlist']))
        {
            unset($_SESSION['wishlist'][array_search($offer_id, $_SESSION['wishlist'])]);
            $_SESSION['wishlist'] = array_values($_SESSION['wishlist']);
            echo json_encode([
                "action" => "delete",
                "type" => "success",
                "content" => "Smazáno z oblíbených",
            ]);
        }
    }

    private function create_basket()
    {
        if(!isset($_SESSION['wishlist']))
            $_SESSION['wishlist'] = [];
    }
}