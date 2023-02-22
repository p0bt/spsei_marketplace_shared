<?php
namespace SpseiMarketplace\Controllers;

use Exception;
use SpseiMarketplace\Core\Validator;
use SpseiMarketplace\Core\Database;
use SpseiMarketplace\Core\Filter;

class AjaxController extends BaseController
{
    private $db;
    
    private $validator;

    public function __construct()
    {
        if(!Filter::is_ajax_request()) 
            die;

        $this->db = new Database();
        $this->validator = new Validator();
    }

    public function process_list()
    {
        if(!Filter::is_admin()) die;
        echo json_encode($this->db->query("SHOW FULL PROCESSLIST")->getResultArray());
    }

    public function auction_current_price()
    {
        echo json_encode($this->db->query("SELECT `top_bid` FROM `auctions` WHERE `auction_id` = ?", [$_POST['auction_id']]));
    }

    public function start_socketio_server()
    {
        if(!Filter::is_admin()) die;

        try {
            $channel = pclose(popen("php /b ".SITE_PATH."/bin/ChannelServer.php", 'r'));
            $socketio = pclose(popen("php /b ".SITE_PATH."/bin/SocketIOServer.php", 'r'));
            //shell_exec("php ".SITE_PATH."/bin/ChannelServer.php");
            //hell_exec("php ".SITE_PATH."/bin/SocketIOServer.php");
        } catch(Exception $e) {
            echo json_encode($e);
            die;
        }

        echo json_encode([
            "success" => "true"
        ]);
    }

    public function socketio_shell_output()
    {
        if(!Filter::is_admin()) die;
        // Stolen from:
        // https://stackoverflow.com/questions/8370628/php-shell-exec-with-realtime-updating

        $response = "";
        /*
        if(($fp = popen("php ChannelServer.php SocketIOServer.php", "r"))) {
            while(!feof($fp)){
                $response .= fread($fp, 1024);
                // flush buffer
                flush();
            }
            fclose($fp);
        }
        */
        echo json_encode($response);
    }
}