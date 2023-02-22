<?php
namespace SpseiMarketplace\Core;

/**
 * Simple validation class for validating inputs
 * @author Peter Butora
 */
class Validator
{
    private $input_rules;
    private $file_rules;

    private $input_error_messages;
    private $file_error_messages;

    private $rules = [];
    private $errors = [];

    public function __construct()
    {
        $this->input_rules = new InputRules();
        $this->input_error_messages = json_decode(file_get_contents(__DIR__."\InputErrorMessages.json"), true);

        $this->file_rules = new FileRules();
        $this->file_error_messages = json_decode(file_get_contents(__DIR__."\FileErrorMessages.json"), true);
    }

    /**
    * Add rule to validator
    * @param string $input_name Name of the input
    * @param string $rule Name of the rule
    * @return void
    */
    public function addRules($input_name, $rules)
    {
        $this->rules[] = [
            'input_name' => $input_name,
            'rules' => $rules,
        ];
    }

    /**
    * Add rules to validator
    * @param mixed[] $rules 2D Array of input_name and rule
    * @return void
    */
    public function addMultipleRules($data)
    {
        foreach($data as $item)
        {
            $this->rules[] = [
                'input_name' => array_search($item, $data),
                'rules' => $item,
            ];
        }
    }

    /**
     * Make each rule separated
     */
    private function parseRules()
    {
        $result = [];
        foreach($this->rules as $rule)
        {
            $rule_names = explode('|', $rule['rules']);
            foreach($rule_names as $rule_)
            {
                $result[] = [
                    'input_name' => $rule['input_name'],
                    'rule' => trim($rule_),
                ];
            }
        }
        return $result;
    }

    /**
    * Get rules
    * @return mixed
    */
    public function getRules()
    {
        return $this->rules;
    }

    /**
    * Run validation
    * @return bool (true on success / false on fail)
    */
    public function run()
    {
        $parsed_rules = $this->parseRules();
        $validation_successfull = true;

        if(isset($parsed_rules))
        {
            // Loop through all rules
            foreach($parsed_rules as $key => &$rule)
            {
                $input_name = $rule['input_name'];
                $input_value = null;
                if(isset($_POST[trim($rule['input_name'], '*')]))
                    $input_value = $_POST[trim($rule['input_name'], '*')];
                $rule_name = $rule['rule'];
                $rule_value = null;

                if(preg_match('/(.*?)\[(.*)\]/', $rule['rule'], $match))
                {
                    // Parse data from rule string
                    $rule_name = $match[1];
                    $rule_value = $match[2];
                }

                // If input can be empty, and it's empty
                if((empty($input_value) || !isset($input_value)) && $rule_name == "permit_empty")
                {
                    // Remove input from validation (both parsed rules and attribute rules)
                    $index = array_search($input_name, array_column($this->rules, "input_name"));
                    unset($this->rules[$index]);

                    $indexes = array_keys(array_column($parsed_rules, "input_name"), $input_name);

                    array_map(function($value) use(&$parsed_rules) {
                        unset($parsed_rules[$value]);
                    }, $indexes);

                    // Continue validating other inputs
                    continue;
                }
                else
                {
                    // If we have rule and it didn't pass -> validation isn't successfull
                    if(method_exists($this->input_rules, $rule_name))
                    {
                        $suc = $this->input_rules->$rule_name($input_value, $rule_value);
                    }
                    else if(method_exists($this->file_rules, $rule_name))
                    {
                        $suc = $this->file_rules->$rule_name($input_name, $rule_value);
                    } 
                    // If we don't have any method for this rule -> continue
                    else
                    {
                        continue;
                    }

                    if(!$suc)
                    {
                        $validation_successfull = false;
                        // Save that this input field has an error
                        $this->addError($input_name, $rule_name);
                    }
                }
            }
        }
        
        return $validation_successfull;
    }

    public function addError($input_name, $rule_name)
    {
        $this->errors[] = [
            "input_name" => $input_name,
            "error" => $rule_name,
            "error_message" => $this->getErrorMessage($rule_name),
        ];
    }

    private function getErrorMessage($rule_name)
    {
        if(isset($this->input_error_messages[$rule_name]))
            return $this->input_error_messages[$rule_name];
        else if(isset($this->file_error_messages[$rule_name]))
            return $this->file_error_messages[$rule_name];
    }

    /**
    * Get error for specific input
    * @return bool|string
    */
    public function getError($input_name)
    {
        if(isset($this->errors))
        {
            foreach($this->errors as $error)
            {
                if($error["input_name"] == $input_name)
                {
                    return $error["error_message"];
                }
            }
        }
        return false;
    }

    /**
    * Get all errors
    * @return array
    */
    public function getErrors()
    {
        return $this->errors;
    }
}