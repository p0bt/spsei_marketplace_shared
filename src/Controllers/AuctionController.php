<?php
namespace SpseiMarketplace\Controllers;

use SpseiMarketplace\Core\Validator;
use SpseiMarketplace\Core\Filter;
use SpseiMarketplace\Models\Auction;
use Emitter;

class AuctionController extends BaseController
{
    private $auction_model;
    private $validator;
    private $emitter;

    public function __construct()
    {
        $this->auction_model = new Auction();
        $this->validator = new Validator();
        $this->emitter = new Emitter();
    }

    public function current_state()
    {
        $this->validator->addMultipleRules([
            'auction_id' => 'is_not_unique[auctions.auction_id]',
        ]);
        if($this->validator->run())
        {
            echo json_encode($this->auction_model->get_current_state($_POST['auction_id']));
        }
    }

    public function rise_price()
    {
        if($_POST && Filter::is_ajax_request())
        {
            $this->validator->addMultipleRules([
                'auction_id' => 'is_not_unique[auctions.auction_id]',
                'new_price' => 'required|is_number|greather_than[0]|less_than[10001]',
            ]);
            if($this->validator->run() && $this->can_user_bid($_POST['auction_id']) && !$this->auction_model->is_mine($_POST['auction_id'], $_SESSION['user_data']['user_id']))
            {
                if($_POST['new_price'] > $this->auction_model->get_current_state($_POST['auction_id'])['top_bid'])
                {
                    $this->auction_model->rise_price($_POST['auction_id'], $_POST['new_price'], $_SESSION['user_data']['user_id']);
                    $_SESSION['auction']['last_bid_time'] = time();

                    $data = [
                        "event" => "auction_price_rised",
                        "auction_id" => $_POST['auction_id'],
                        "new_price" => $_POST['new_price'],
                        "user_id" => $_SESSION['user_data']['user_id'],
                    ];

                    $this->emitter->emit('auction_change', json_encode($data));
                }
            }
        }
    }

    public function can_user_bid_ajax()
    {
        echo json_encode($this->can_user_bid($_POST['auction_id']));
    }

    private function can_user_bid($auction_id)
    {
        $auction_data = $this->auction_model->get_by_id($auction_id);
        
        return ((strtotime($auction_data['start_date']) <= time() && strtotime($auction_data['end_date']) >= time()) && (!isset($_SESSION['auction']['last_bid_time']) || (time() - $_SESSION['auction']['last_bid_time'] >= AUCTION_BID_DELAY)));
    }
}