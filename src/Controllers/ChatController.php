<?php
namespace SpseiMarketplace\Controllers;

use SpseiMarketplace\Core\Validator;
use SpseiMarketplace\Models\Chat;
use SpseiMarketplace\Models\Message;
use Emitter;

class ChatController extends BaseController
{
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
                if(!($chat_id = $this->chat_model->get_chat_id($_SESSION['user_data']['user_id'], $_POST['user_id'])))
                {
                    $chat_id = uniqid("", true);

                    // Associate new chat with me and target user :D
                    $this->chat_model->post([
                        "chat_id" => $chat_id,
                        "user_id" => $_SESSION['user_data']['user_id'],
                    ]);

                    $this->chat_model->post([
                        "chat_id" => $chat_id,
                        "user_id" => $_POST['user_id'],
                    ]);
                }
                
                echo json_encode("/zpravy?chat=".$chat_id);
            }
        }
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

                if($this->chat_model->is_chat_mine($chat_id, $_SESSION['user_data']['user_id']))
                {
                    $data = [
                        "chat_id" => $chat_id,
                        "sender" => $_SESSION['user_data']['user_id'],
                        "text" => $_POST['message'],
                        "date_sent" => date("Y-m-d H:i:s", time()),
                    ];

                    $this->emitter->emit('message_sent', json_encode($data));

                    $this->message_model->post($data);
                }
            }
        }
    }
}