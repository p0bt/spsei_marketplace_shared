<?php

class FileRules 
{
    public function max_size($input_name, $rule_value)
    {
        if(str_contains($input_name, '*'))
        {
            $input_name = trim($input_name, '*');
            foreach($_FILES[$input_name]['size'] as $size)
            {
                if($size / 1000 > $rule_value)
                    return false;
            }
            return true;
        }
        return (($_FILES[$input_name]['size'] / 1000) <= $rule_value);
    }

    public function is_image($input_name, $rule_value)
    {
        // Allowed file types
        $image_types = [
            "image/jpeg",
            "image/png",
        ];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if(str_contains($input_name, '*'))
        {
            $input_name = trim($input_name, '*');
            foreach($_FILES[$input_name]['tmp_name'] as $tmp_name)
            {
                $file_type = finfo_file($finfo, $tmp_name);
                if(!in_array($file_type, $image_types))
                    return false;
            }
            return true;
        }
        else
        {
            $file_type = finfo_file($finfo, $_FILES[$input_name]['tmp_name']);
            return (in_array($file_type, $image_types));
        }
    }

    public function min_count($input_name, $rule_value)
    {
        if(str_contains($input_name, '*'))
        {
            $input_name = trim($input_name, '*');
            return count($_FILES[$input_name]["name"]) >= $rule_value;
        }
        return count($_FILES[$input_name]["name"]) >= $rule_value;
    }

    public function max_count($input_name, $rule_value)
    {
        if(str_contains($input_name, '*'))
        {
            $input_name = trim($input_name, '*');
            return count($_FILES[$input_name]["name"]) <= $rule_value;
        }
        return count($_FILES[$input_name]["name"]) <= $rule_value;
    }
}