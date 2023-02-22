<?php
namespace SpseiMarketplace\Controllers;

use Emitter;
use SpseiMarketplace\Core\Filter;
use SpseiMarketplace\Core\Mail;
use SpseiMarketplace\Models\Offer;
use SpseiMarketplace\Models\User;
use SpseiMarketplace\Models\Notification;
use SpseiMarketplace\Models\Auction;
use SpseiMarketplace\Models\Chat;
use SpseiMarketplace\Models\Message;
use SpseiMarketplace\Models\Token;

class CronController
{
    private $offers_model;
    private $notifications_model;
    private $auctions_model;
    private $users_model;
    private $chat_model;
    private $message_model;
    private $emitter;
    private $token_model;

    public function __construct()
    {
        $this->offers_model = new Offer();
        $this->notifications_model = new Notification();
        $this->auctions_model = new Auction();
        $this->users_model = new User();
        $this->chat_model = new Chat();
        $this->message_model = new Message();   
        $this->emitter = new Emitter();
        $this->token_model = new Token();
    }

    public function delete_old_tokens()
    {
        $this->token_model->delete_old();
    }

    public function delete_old_offers()
    {
        $this->offers_model->delete_old();
    }

    public function close_old_auctions()
    {
        // Get old auctions
        $auctions = $this->auctions_model->get_old_auctions();
        var_dump($auctions);
        // Close old auctions (set closed = 1)
        $this->auctions_model->close_old_auctions();
        
        foreach($auctions as $auction)
        {
            $winner_user_data = $this->users_model->get_by_id($auction["user_id"]);
            $seller_user_data = $this->users_model->get_by_id($this->offers_model->get_by_id($auction["offer_id"])['user_id']);

            $msg_winner = "Vyhrál/a jste aukci č. ".$auction["auction_id"]." . Kontaktujte prodejce.";
            $msg_seller = "Vaše aukce č. ".$auction["auction_id"]." skončila. Výherce aukce: " . $winner_user_data['email'];
            
            // Create new chat if it doesn't exist with auciton winner and auction creator
            $chat_id = $this->chat_model->get_chat_id($winner_user_data["user_id"], $seller_user_data["user_id"]);
            if(!$chat_id)
            {
                $chat_id = uniqid("", true);

                // Associate new chat with me and target user :D
                $this->chat_model->post([
                    "chat_id" => $chat_id,
                    "user_id" => $winner_user_data["user_id"],
                ]);

                $this->chat_model->post([
                    "chat_id" => $chat_id,
                    "user_id" => $seller_user_data["user_id"],
                ]);
            }

            // Send a message about auction state
            $data = [
                "chat_id" => $chat_id,
                "sender" => 0, // SENDER WITH ID: 0 = SYSTEM
                "text" => "Aukce č. ".$auction["auction_id"]." skončila. Prodejce: ".$seller_user_data['email'].", výherce: ".$winner_user_data['email'].". Domluvte se prosím na předání a zaplacení předmětu.",
                "date_sent" => date("Y-m-d H:i:s", time()),
            ];

            $this->emitter->emit('message_sent', json_encode($data));

            $this->message_model->post($data);


            // Email for winner
            $mail = new Mail();
            $mail->setReceiver($winner_user_data['email']);
            $mail->setSubject("Aukce č.".$auction["auction_id"]." - " . SITE_TITLE);
            $mail->setMessage($msg_winner);
            //$mail->send();
            
            // Email for seller
            $mail = new Mail();
            $mail->setReceiver($seller_user_data['email']);
            $mail->setSubject("Aukce č.".$auction["auction_id"]." - " . SITE_TITLE);
            $mail->setMessage($msg_seller);
            //$mail->send();

            // Notification for winner
            $this->notifications_model->post([
                'target' => $winner_user_data["user_id"],
                'content' => $msg_winner,
            ]);

            // Notification for seller (auction owner)
            $this->notifications_model->post([
                'target' => $seller_user_data["user_id"],
                'content' => $msg_seller,
            ]);
        }
    }
}