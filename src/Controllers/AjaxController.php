<?php

class AjaxController extends BaseController
{
    public function __construct()
    {
        if(!Filter::is_ajax_request()) 
            die;

        $this->validator = new Validator();
    }

    public function process_list()
    {
        if(!Filter::is_admin()) die;
        echo json_encode(Database::query("SHOW FULL PROCESSLIST")->getResultArray());
    }

    public function auction_current_price()
    {
        echo json_encode(Database::query("SELECT `top_bid` FROM `auctions` WHERE `auction_id` = ?", [$_POST['auction_id']]));
    }

    public function send_email()
    {
        if($_POST)
        {
            $this->validator->addMultipleRules([
                'email' => 'required|max_length[100]',
                'text' => 'required|max_length[255]',
            ]);
            if($this->validator->run())
            {
                $from = $_POST['email'];
                $to = $_POST['receiver'];
                $message = $_POST['text'];

                $subject = "Nová zpráva - " . SITE_TITLE;
                $headers = 'From: ' . $from . "\r\n" .
                'Reply-To: ' . $from . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

                if(mail($to, $subject, $message, $headers)) 
                    echo json_encode(["success" => "Zpráva byla odeslána prodejci"]);
                else
                    echo json_encode(["error" => "Někde nastala chyba, zkuste prosím akci opakovat"]);
            }
        }
    }
}