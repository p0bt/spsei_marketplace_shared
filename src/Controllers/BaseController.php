<?php
namespace SpseiMarketplace\Controllers;

use SpseiMarketplace\Models\Notification;

abstract class BaseController
{
    protected function render($path, $data = null)
    {
        $data['notifications'] = $this->get_notifications();

        extract($data);

        require_once($path);
    }

    private function get_notifications()
    {
        $notifications_model = new Notification();
        
        if(isset($_SESSION['user_data']['user_id']))
        {
            return $notifications_model->get_for_user($_SESSION['user_data']['user_id']);
        }

        return [];
    }
}