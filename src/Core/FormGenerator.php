<?php

namespace SpseiMarketplace\Core;

class FormGenerator
{
    private $url;

    private $method;

    private $tables;

    private $extra_atrributes;

    private $title = "";

    private $fields = [];
    
    // VALIDATION RULES
    private $validation_rules = [];

    // HTML Result
    private $html_result = "";

    // DB
    private $db;

    public function __construct($url, $method, $tables, $title = null, $fields = [], $extra_atrributes = null)
    {
        $this->url = $url;
        $this->method = $method;
        $this->tables = $tables;
        $this->extra_atrributes = $extra_atrributes;
        $this->fields = $fields;
        $this->title = $title;

        $this->db = new Database();
    }

    private function generate_label($field_name)
    {   
        $field_name = str_replace("_", " ", $field_name);

        $parts = explode(" ", $field_name);

        $parts[0] = ucfirst($parts[0]);

        return join(" ", $parts);
    }

    public function prefill_form_by_id($record_id)
    {
        $tables = $this->tables;
        $left_joins = [];
        $select = $tables[0].".*";
        if(count($tables) > 1)
        {
            $select = join(".*, ", $tables).".*";

            $tables_without_first = $tables;
            array_shift($tables_without_first);

            foreach($tables_without_first as $table)
            {
                $ts = $this->db->query("DESCRIBE " . $table)->getResultArray();
                $primary_key = array_filter($ts, function($value) {
                    return $value['Key'] == "PRI";
                })[0]['Field'];

                $left_joins[] = "LEFT JOIN ".$table." USING(".$primary_key.")";
            }
        }

        $table_structure_0 = $this->db->query("DESCRIBE " . $tables[0])->getResultArray();
        $primary_keys = array_filter($table_structure_0, function($value) {
            return $value['Key'] == "PRI";
        });

        $sql = "SELECT ".$select." 
                FROM ".$tables[0].
                " ".join(" ", $left_joins).
                " WHERE ".$tables[0].".".$primary_keys[0]['Field']." = '".$record_id."';";

        $db_record = $this->db->query($sql)->getRowArray();

        foreach($this->fields as $field)
        {
            $input_name = $field["attributes"]["name"];

            if(in_array($input_name, array_keys($db_record)))
            {
                $this->fields[$input_name]["attributes"]["value"] = $db_record[$input_name];
                if($field["tag"] == "select")
                {
                    // Get select values only
                    // https://www.php.net/manual/en/function.array-column.php

                    $select_values = array_column($this->fields[$input_name]["select_options"], "value");
                    // Find option index from select, with same value as stored in db
                    // If db value not NULL
                    if(!empty($db_record[$input_name]))
                    {
                        $index = array_search($db_record[$input_name], $select_values);
                        $this->fields[$input_name]["select_options"][$index]['selected'] = true;
                    }
                }
                else if($field["attributes"]["type"] == "checkbox")
                {
                    // Set checkbox to checked if value stored in db equals to 1
                    if($db_record[$input_name] == 1)
                        $this->fields[$input_name]["attributes"]["checked"] = "checked";
                }
            }
        }
    }

    public function set_fields_from_tables($target_fields = [])
    {
        $tables = $this->tables;
        foreach($tables as $table)
        {
            // Get table structure
            $table_structure_rows = $this->db->query("DESCRIBE " . $table)->getResultArray();
            // Get table constraints
            $constraints = $this->db->query('SELECT COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_COLUMN_NAME, REFERENCED_TABLE_NAME 
                                            FROM information_schema.KEY_COLUMN_USAGE 
                                            WHERE TABLE_NAME = "'.$table.'"')->getResultArray();
            
            // Keep just references on other tables (exclude primary keys etc.)
            // https://www.php.net/manual/en/function.array-values.php
            $foreign_keys = array_values(array_filter($constraints, function($value) {
                return !empty($value['REFERENCED_COLUMN_NAME']);
            }));

            foreach($table_structure_rows as $table_structure)
            {
                // Skip primary key(s) or fields we don't want in our form
                if(($table_structure['Key'] == "PRI" && !in_array($table_structure['Field'], $target_fields[$table])) ||
                !in_array($table_structure['Field'], $target_fields[$table])) 
                    continue;

                $tag = "input";
                $type = "text";
                $maxlength = null;
                $class = "form-control";
                // Required (if attribute can be NULL)
                $required = ($table_structure['Null'] == "YES") ? false : true;

                // Findout tag and type (in case of input)
                if(in_array($table_structure['Field'], array_column($foreign_keys, "COLUMN_NAME")))
                {
                    $tag = "select";
                    $type = "";
                    
                    // Get referenced table for actual field
                    $target_table = array_values(array_filter($foreign_keys, function($value) use($table_structure) {
                        return $value['COLUMN_NAME'] == $table_structure['Field'];
                    }))[0]['REFERENCED_TABLE_NAME'];

                    $select_metadata = [
                        "referenced_table" => $target_table,
                        "column_name" => $table_structure['Field'],
                    ];

                    $target_table_records = $this->db->query('SELECT * FROM '.$target_table)->getResultArray();
                    
                    $select_options = [];
                    
                    foreach($target_table_records as $row)
                    {
                        $row = array_values($row);
                        // 'Select value' is always primary key -> so first attribute in table
                        $value = $row[0];
                        // Remove first index from array (we've already stored it's value in the line above)
                        array_shift($row);
                        // Filter out empty columns
                        $row = array_values(array_filter($row, function($value) {
                            return !empty($value) && strlen($value) > 0;
                        }));
                        $select_options[] = [
                            "value" => $value,
                            "name" => $row[0]." (ID: ".$value.")",
                            "selected" => false
                        ];
                    }
                }
                elseif(str_contains($table_structure['Type'], "text"))
                {
                    $tag = "textarea";
                }
                // SPECIAL CASE: BOOLEAN
                elseif($table_structure['Type'] == "tinyint(1)")
                {
                    $class = "form-check-input";
                    $type = "checkbox";
                    $required = false; // It has to be unrequired, because it'd expect checkbox to be always checked
                }
                elseif(str_contains($table_structure['Type'], "int"))
                {
                    $type = "number";
                }
                elseif($table_structure['Type'] == "date")
                {
                    $type = "date";
                }
                elseif($table_structure['Type'] == "datetime")
                {
                    $type = "datetime-local";
                }

                // Max length
                if(str_contains($table_structure['Type'], "("))
                {
                    $maxlength = preg_replace('/[^0-9]/', '', $table_structure['Type']);
                }

                // Generate label/placeholder
                $label = $this->generate_label($table_structure['Field']);

                $field_arr = [
                    "tag" => $tag,
                    "label" => $label,
                    "attributes" => [
                        "rows" => 5,
                        "name" => $table_structure['Field'],
                        "class" => $class,
                        "id" => $table_structure['Field'],
                        "type" => $type,
                        "maxlength" => !empty($maxlength) ? $maxlength : "",
                        "value" => "",
                        "placeholder" => $label,
                    ],
                ];

                if($required)
                {
                    $field_arr['attributes']['required'] = "required";
                }

                if($tag == "select")
                {
                    $field_arr = array_merge($field_arr , [
                        "select_options" => isset($select_options) ? $select_options : [],
                        "select_metadata" => isset($select_metadata) ? $select_metadata : [],
                    ]);
                }
                $this->fields[$table_structure['Field']] = $field_arr;
            }
        }
    }

    private function generate_form()
    {
        // FORM OPENING
        $form_open_tag = "<form action='".$this->url."' method='".$this->method."'";
        $append = []; 

        if(isset($this->extra_atrributes))
        {  
            foreach($this->extra_atrributes as $attr)
            {
                $append[] .= array_search($attr, $this->extra_atrributes) . "='" . $attr . "'";
            }
        }

        $form_open_tag .= " ".join(" ", $append);
        $form_open_tag .= ">";

        // FORM BODY
        $form_body = "";
        if(!empty($this->title))
            $form_body = "<h2>".$this->title."</h2>";

        if(!empty($this->fields))
        {
            foreach($this->fields as $field)
            {
                $form_body .= $this->generate_field($field);
            }
        }

        // FORM SUBMIT
        $form_submit = "<button type='submit' class='btn btn-primary text-uppercase'>ODESLAT</button>";

        // FORM CLOSING
        $form_closing_tag = "</form>";

        $this->html_result = $form_open_tag . $form_body . $form_submit. $form_closing_tag;
    }

    public function generate_field($field)
    {
        $tag = $field['tag'];
        $label = $field['label'];
        $attributes = $field['attributes'];

        $allowed_attributes = ["name", "class", "id", "required"];

        $tag_wrapper_opening = "<div class='mb-3'>";
        $tag_wrapper_closing = "</div>";

        $label_text = $label;
        $label = "<label class='form-label' for='".$attributes['id']."'>".$label_text."</label>";

        switch($tag) 
        {
            case "input":
                $allowed_attributes = array_merge($allowed_attributes, ["type", "placeholder", "value", "minlength", "maxlength"]);
                
                $tag_opening = "<input";
                $tag_closing = ">";

                if($attributes['type'] == "checkbox") 
                {
                    $allowed_attributes = array_merge($allowed_attributes, ["checked"]);
                    $label = "<label class='form-check-label' for='".$attributes['id']."'>".$label_text."</label>";
                    $tag_opening = "<div class='form-check'>".$tag_opening;
                    $tag_closing .= $label."</div>";
                    $label = "";
                }
                break;

            case "textarea":
                $allowed_attributes = array_merge($allowed_attributes, ["placeholder", "rows", "value", "minlength", "maxlength"]);

                $tag_opening = "<textarea";
                $tag_closing = "</textarea>";
                break;

            case "select":
                $select_options = $field['select_options'];

                $select_body = "<option value=''>--- Vyberte ---</option>";
                $tag_opening = "<select";   
                $tag_closing = "</select>";
                foreach($select_options as $option) {
                    $select_body .= "<option value='".$option['value']."' ".(($option['selected']) ? "selected" : "").">".$option['name']."</option>";
                }
                break;
        }
        // Add extra space for attributes
        $tag_opening .= " ";
        // Save only attributes which are allowed for desired imput
        $actual_attributes = [];
        foreach($attributes as $attribute) 
        {
            if(in_array(array_search($attribute, $attributes), $allowed_attributes))
                $actual_attributes[] = array_search($attribute, $attributes)."="."'".$attribute."'";
        }

        // Join attributes with space
        $actual_attributes = join(" ", $actual_attributes);
        // Return HTML generated field with margin and label
        return $tag_wrapper_opening . 
                $label . 
                $tag_opening . 
                $actual_attributes . (in_array($tag, ["textarea", "select"]) ? ">" : "") . // textarea / select tag need extra closing bracket '>'
                    ($tag == "textarea" ? $attributes['value'] : "") . // textarea is special case of input, because it hasn't got value as attribute
                    (isset($select_body) ? $select_body : "") .
                $tag_closing . 
            $tag_wrapper_closing;
    }

    private function generate_validation_rules()
    {
        foreach($this->fields as $field)
        {
            $rules = [];

            if(isset($field['attributes']['required']) && $field['attributes']['required']) 
                $rules[] = "required";
            else
                $rules[] = "permit_empty";

            switch($field['tag'])
            {
                case "select":
                    $rules[] = "is_not_unique[".$field['select_metadata']['referenced_table'].".".$field['select_metadata']['column_name']."]";
                    break;
            }

            switch($field['attributes']['type'])
            {
                case "checkbox":
                    $rules[] = "in_list[0,1]";
                    break;

                case "number":
                    $rules[] = "is_number";
                    break;
                
                case "date":
                    $rules[] = "is_valid_date";
                    break;

                case "datetime-local":
                    $rules[] = "is_valid_datetime";
                    break;
            }

            if(isset($field['attributes']['max_length']) && $field['attributes']['max_length']) 
                $rules[] = "max_length[".$field['attributes']['max_length']."]";

            if(isset($field['attributes']['min_length']) && $field['attributes']['min_length']) 
                $rules[] = "min_length[".$field['attributes']['min_length']."]";

            if(!empty($rules))
                $this->validation_rules[$field['attributes']['name']] = join("|", $rules);
        }
    }

    public function get_validation_rules()
    {
        $this->generate_validation_rules();
        return $this->validation_rules;
    }

    public function get_html_result()
    {
        $this->generate_form();
        return $this->html_result;
    }

    public function get_fields()
    {
        return $this->fields;
    }
}