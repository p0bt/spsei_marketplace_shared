<?php

class Filter
{
    public static function is_admin()
    {
        return (Filter::is_logged_in() && $_SESSION['user_data']['admin']);
    }

    public static function is_logged_in()
    {
        return isset($_SESSION['user_data']);
    }

    public static function is_ajax_request()
    {
        return(isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
        && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest"
        && strpos($_SERVER['HTTP_REFERER'], getenv('HTTP_HOST')));
    }

    public static function is_cron()
    {
        return (php_sapi_name() == 'cli' && !isset($_SERVER['TERM']));
    }

    public static function is_banned()
    {
        $result = Database::query("SELECT bi_id FROM `banned_ips` WHERE `ip_address` = ?", [HelperFunctions::getClientIp()])->getRowArray();
        return is_array($result);
    }
}