<?php
namespace SpseiMarketplace\Controllers;

use SpseiMarketplace\Core\Validator;
use SpseiMarketplace\Models\Chat;
use SpseiMarketplace\Models\Message;
use Emitter;

class ChatController extends BaseController
{
    private $validator;
    private $message_model;
    private $chat_model;
    private $emitter;

    public function __construct()
    {
        $this->validator = new Validator();
        $this->message_model = new Message();
        $this->chat_model = new Chat();
        $this->emitter = new Emitter();
    }

    public function create_new_chat()
    {
        if ($_POST)
        {
            $this->validator->addMultipleRules([
                'user_id' => 'required|is_not_unique[users.user_id]',
            ]);
            if ($this->validator->run())
            {
                $chat_id = $this->get_or_create_chat($_SESSION['user_data']['user_id'], $_POST['user_id']);
                
                echo json_encode("/zpravy?chat=".$chat_id);
            }
        }
    }

    private function get_or_create_chat($user_id_1, $user_id_2)
    {
        // If chat doesn't exist yet
        $chat_id = $this->chat_model->get_chat_id($user_id_1, $user_id_2);
        
        if(!$chat_id)
        {
            $chat_id = uniqid("", true);

            // Associate new chat with me and target user :D
            $this->chat_model->post([
                "chat_id" => $chat_id,
                "user_id" => $user_id_1,
            ]);

            $this->chat_model->post([
                "chat_id" => $chat_id,
                "user_id" => $user_id_2,
            ]);
        }

        return $chat_id;
    }

    public function index()
    {
        $data['chats'] = $this->chat_model->get_all_chats_with_info_from_user($_SESSION['user_data']['user_id']);

        if(isset($_GET['chat']) && !empty($_GET['chat']) && $this->chat_model->is_chat_mine($_GET['chat'], $_SESSION['user_data']['user_id']))
        {
            $data['messages'] = $this->message_model->get_from_chat($_GET['chat']);
        }

        $this->render("views/templates/header.php");
        $this->render("views/chat/index.php", $data);
        $this->render("views/templates/footer.php");
    }

    private function send_msg($chat_id, $sender, $text, $date_sent)
    {
        if($this->chat_model->is_chat_mine($chat_id, $sender))
        {
            $data = [
                "chat_id" => $chat_id,
                "sender" => $sender,
                "text" => $text,
                "date_sent" => $date_sent,
            ];

            $this->emitter->emit('message_sent', json_encode($data));

            $this->message_model->post($data);
        }
    }

    public function send_message()
    {
        if ($_POST)
        {
            $this->validator->addMultipleRules([
                'chat_id' => 'required|is_not_unique[chats.chat_id]',
                'message' => 'required|min_length[1]|max_length[65535]',
            ]);
            if ($this->validator->run())
            {
                $chat_id = $_POST['chat_id'];
                $this->send_msg($chat_id, $_SESSION['user_data']['user_id'], $_POST['message'], date("Y-m-d H:i:s", time()));
            }
        }
    }

    public function send_message_contact_form()
    {
        if ($_POST)
        {
            $this->validator->addMultipleRules([
                'user_id' => 'required|is_not_unique[users.user_id]',
                'message' => 'required|min_length[1]|max_length[65535]',
            ]);
            if ($this->validator->run())
            {
                $chat_id = $this->get_or_create_chat($_SESSION['user_data']['user_id'], $_POST['user_id']);
                $this->send_msg($chat_id, $_SESSION['user_data']['user_id'], $_POST['message'], date("Y-m-d H:i:s", time()));
                echo json_encode(["success" => "Zpráva byla odeslána prodejci"]);
                die;
            }
        }

        echo json_encode(["error" => "Někde nastala chyba, zkuste prosím akci opakovat"]);
        die;
    }
}