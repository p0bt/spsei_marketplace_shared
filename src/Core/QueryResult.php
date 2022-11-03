<?php 
namespace SpseiMarketplace\Core;

class QueryResult
{
    protected $mysqli_result;
    protected $escape_html;

    /**
     * Create QueryResult instance
     * @param mysqli_result $mysqli_result
     * @param boolean $escape_html Convert special characters to html
     */
    public function __construct($mysqli_result, $escape_html)
    {
        $this->mysqli_result = $mysqli_result;
        $this->escape_html = $escape_html;
    }
    
    public function getResultArray()
    {
        if($this->mysqli_result)
        {
            $tmp = $this->mysqli_result->fetch_all(MYSQLI_ASSOC);
            $this->escape($tmp);
            return $tmp;
        }
        return false;
    }

    public function getRowArray()
    {
        if($this->mysqli_result)
        {
            $result_array = $this->getResultArray();
            if(isset($result_array[0]))
                return $result_array[0];
        }
        return false;
    }

    public function countAll()
    {
        if($this->mysqli_result)
            return count($this->getResultArray());
        return false;
    }
    
    /* 
    * Prevent XSS
    */
    private function escape(&$arr) {
        if($this->escape_html)
        {
            if ($arr) 
            {
                foreach ($arr as &$value) 
                {
                    if (is_array($value)) 
                    {
                        $this->escape($value);
                    } 
                    else 
                    {
                        // Convert special characters from string to HTML entities (Prevent XSS)
                        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                    }
                }
            }
        }
    }
}