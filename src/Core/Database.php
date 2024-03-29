<?php
namespace SpseiMarketplace\Core;

use mysqli, mysqli_stmt;

class Database 
{
    private $conn;

    public function __construct()
    {
        $this->conn = self::get_new_connection();
    }

    public function get_connection()
    {
        return $this->conn;
    }

    public static function get_new_connection()
    {
        return new mysqli(DB_hostname, DB_username, DB_password, DB_name);
    }

    public function get_last_inserted_id()
    {
        return $this->conn->insert_id;
    }

    /**
     * Execute prepared sql with binds
     * @param string $sql
     * @param array $binds
     * @return QueryResult|false
     */
    public function query($sql, $binds = null, $escape = true)
    {
        $db = $this->get_connection();
        $stmt = new mysqli_stmt($db, $sql);
        $types = '';

        $stmt->prepare($sql);

        if(isset($binds))
        {
            foreach($binds as $bind)
            {
                if(is_integer($bind))
                    $types .= 'i';
                else if(is_numeric($bind))
                    $types .= 'd';
                else
                    $types .= 's';
            }
            
            $stmt->bind_param($types, ...$binds);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return new QueryResult($result, $escape);
    }

    /**
     * Join SQL parts (array) to one SQL statement (string)
     * @param array $sql
     * @return string joined sql
     */
    public function join_sql($sql)
    {
        if(!empty($sql))
        {
            // https://www.php.net/manual/en/function.array-map.php
            return implode("", (array_map(function($value, $key, $count) {
                $s = " ";
                if($key != 0 && $key != $count-1) $s .= "AND ";
                $s .= "(";
                return $s.$value.")";
            }, $sql, array_keys($sql), [count($sql)])));
        }
        return "";
    }
}