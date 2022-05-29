<?php

class HelperFunctions 
{
    /**
     * Set input value from post data
     * @param string $input_name name of the input
     * @return string input value
     */
    public static function setInputValue($input_name)
    {
        if(isset($_POST[$input_name]))
            return $_POST[$input_name];
        elseif(isset($_GET[$input_name]))
            return $_GET[$input_name];
        return '';
    }

    /**
     * Set checkbox to checked
     * @param string $input_name name of the checkbox
     * @return string 'checked' or empty '' string
     */
    public static function setCheckbox($input_name, $input_value)
    {
        if(isset($_POST[$input_name]))
        {
            foreach($_POST[$input_name] as $inp)
            {
                if(strcmp($inp, $input_value) == 0)
                return 'checked';
            }
        }
        if(isset($_GET[$input_name]))
        {
            foreach($_GET[$input_name] as $inp)
            {
                if(strcmp($inp, $input_value) == 0)
                return 'checked';
            }
        }
        return '';
    }

    /**
     * Set radio to checked
     * @param string $input_name name of the radio
     * @return string 'checked' or empty '' string
     */
    public static function setRadio($input_name, $input_value)
    {
        if(isset($_POST[$input_name]))
        {
            if($_POST[$input_name] == $input_value)
                return 'checked';
        }
        if(isset($_GET[$input_name]))
        {
            if($_GET[$input_name] == $input_value)
                return 'checked';
        }
        return '';
    }

    /**
     * Set alert
     * @param string $type type of alert (danger, success...)
     * @param string $message
     * @return void
     */
    public static function setAlert($type, $message)
    {
        $_SESSION['alert'][$type] = $message;
    }

    /**
     * Get alert
     * NOTE: alert is destroyed after obtaining the message
     * @param string $type type of alert (danger, success...)
     * @return string alert message
     */
    public static function getAlert($type)
    {
        if(isset($_SESSION['alert'][$type]))
        {
            $tmp = $_SESSION['alert'][$type];
            unset($_SESSION['alert'][$type]);
            return $tmp;
        }
        return false;
    }

    /**
     * Escape string from special characters (prevent XSS)
     * @param string $string
     * @return string escaped string
     */
    public static function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Escape array from special characters (prevent XSS)
     * @param array $array
     * @return string escaped array
     */
    /*
    public static function escapeArray($array)
    {
        $result = array_map(function($value) {
            if(is_array($value))
            {
                foreach($value as $val)
                {
                    $val = HelperFunctions::escape($val);
                }
            }
            else
            {
                return HelperFunctions::escape($value);
            }
        }, $array);
        return $result;
    }*/

    public static function getClientIp() 
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ip = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = 'UNKNOWN';
        return $ip;
    } 
}