<?php
namespace SpseiMarketplace\Core;

use DateTime, Exception;

class InputRules 
{
    public function required($input_value, $rule_value)
    {
        return $input_value == "0" || (isset($input_value) && strlen($input_value) > 0 && !empty($input_value));
    }

    public function min_length($input_value, $rule_value)
    {
        return (strlen($input_value) >= $rule_value);
    }

    public function max_length($input_value, $rule_value)
    {
        return (strlen($input_value) <= $rule_value);
    }

    public function in_list($input_value, $rule_value)
    {
        $rule_value = str_replace(' ', '', $rule_value);
        $list_arr = explode(',', $rule_value);
        return in_array($input_value, $list_arr);
    }

    public function matches($input_value, $rule_value)
    {
        return ($input_value == $_POST[$rule_value]);
    }

    public function is_unique($input_value, $rule_value)
    {
        $arr = explode('.', $rule_value);
        $table = $arr[0];
        $column = $arr[1];

        $result = Database::query("SELECT * FROM `".$table."` WHERE `".$column."` = ?", [$input_value])->countAll();
        if($result)
            return (($result > 0) ? false : true);
        return true;
    }

    public function is_not_unique($input_value, $rule_value)
    {
        return !($this->is_unique($input_value, $rule_value));
    }

    public function is_valid_email($input_value, $rule_value)
    {   
        return filter_var($input_value, FILTER_VALIDATE_EMAIL);
    }

    public function is_number($input_value, $rule_value)
    {   
        return is_numeric($input_value);
    }

    public function greather_than($input_value, $rule_value)
    {
        return ($input_value > $rule_value);
    }

    public function less_than($input_value, $rule_value)
    {
        return ($input_value < $rule_value);
    }

    public function is_valid_date($input_value, $rule_value)
    {
        if (strtotime($input_value) === false) { 
            return false;
        }

        $date_parts = explode('-', $input_value); 
        return checkdate($date_parts[2], $date_parts[1], $date_parts[0]);
    }

    public function is_valid_datetime($input_value, $rule_value)
    {
        try
        {
            new DateTime($input_value);
            return true;
        }
        catch (Exception $e)
        {
            return false;
        }
        /* NOT WORKING
        $allowed_formats = ['Y-m-d','Y-m-d H:i:s','Y-m-d H:i:s.u', 'Y-m-deH:i'];
        
        foreach($allowed_formats as $format) 
        {
            $d = DateTime::createFromFormat($format, $input_value);
            if ($d && $d->format($format) == $input_value) return true;
        }

        return false; */
    }

    public function datetime_greather_than($input_value, $rule_value)
    {
        $input_value = strtotime($input_value);

        if($rule_value == "now")
        {
            return $input_value > time();
        }
        elseif(str_contains($rule_value, ","))
        {
            $arr = explode(',', $rule_value);
            $post_key = $arr[0];
            $offset = (int)$arr[1];

            return $input_value > (strtotime($_POST[$post_key]) + $offset);
        }
        elseif($this->is_number($rule_value, null))
        {
            $rule_value = (int)$rule_value;
            return $input_value > $rule_value;
        }
        elseif(is_string($rule_value))
        {
            return $input_value > strtotime($_POST[$rule_value]);
        }
    }

    public function datetime_less_than($input_value, $rule_value)
    {
        $input_value = strtotime($input_value);

        if($rule_value == "now")
        {
            return $input_value < time();
        }
        elseif(str_contains($rule_value, ","))
        {
            $arr = explode(',', $rule_value);
            $post_key = $arr[0];
            $offset = (int)$arr[1];

            return $input_value < (strtotime($_POST[$post_key]) + $offset);
        }
        elseif($this->is_number($rule_value, null))
        {
            $rule_value = (int)$rule_value;
            return $input_value < $rule_value;
        }
        elseif(is_string($rule_value))
        {
            return $input_value < strtotime($_POST[$rule_value]);
        }
    }
}