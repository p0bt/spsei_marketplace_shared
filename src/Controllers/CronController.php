<?php
namespace SpseiMarketplace\Controllers;

use SpseiMarketplace\Core\Filter;
use SpseiMarketplace\Core\Mail;
use SpseiMarketplace\Models\Offer;
use SpseiMarketplace\Models\User;
use SpseiMarketplace\Models\Notification;
use SpseiMarketplace\Models\Auction;

class CronController
{
    private $offers_model;
    private $notifications_model;
    private $auctions_model;
    private $users_model;

    public function __construct()
    {
        if(!Filter::is_cron()) 
            die;
        
        $this->offers_model = new Offer();
        $this->notifications_model = new Notification();
        $this->auctions_model = new Auction();
        $this->users_model = new User();
    }

    public function delete_old_offers()
    {
        $this->offers_model->delete_old();
    }

    public function close_old_auctions()
    {
        $auctions = $this->auctions_model->get_old_auctions();

        foreach($auctions as $auction)
        {
            $winner_user_data = $this->users_model->get_by_id($auction["user_id"]);
            $seller_user_data = $this->users_model->get_by_id($this->offers_model->get_by_id($auction["offer_id"])['user_id']);

            $msg_winner = "Vyhrál/a jste aukci č. ".$auction["auction_id"]." . Kontaktujte prodejce.";
            $msg_seller = "Vaše aukce č. ".$auction["auction_id"]." skončila. Výherce aukce: " . $winner_user_data['email'];

            // Email for winner
            $mail = new Mail();
            $mail->setReceiver($winner_user_data['email']);
            $mail->setSubject("Aukce č.".$auction["auction_id"]." - " . SITE_TITLE);
            $mail->setMessage($msg_winner);
            $mail->send();

            // Notification for winner
            $this->notifications_model->post([
                'target' => $auction["user_id"],
                'content' => $msg_winner,
            ]);

            // Email for winner
            $mail = new Mail();
            $mail->setReceiver($seller_user_data['email']);
            $mail->setSubject("Aukce č.".$auction["auction_id"]." - " . SITE_TITLE);
            $mail->setMessage($msg_seller);
            $mail->send();

            // Notification for seller (auction owner)
            $this->notifications_model->post([
                'target' => $auction["user_id"],
                'content' => $msg_seller,
            ]);
        }
    }
}