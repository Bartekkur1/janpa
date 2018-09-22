<?php

class QueryBuilder
{

    public $query, $mysqli;

    function __construct()
    {
        $config = parse_ini_file("app/config.ini");
        $this->mysqli = new mysqli("localhost", $config["login"], $config["password"], $config["dbname"]);
        if ($this->mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error;
            die();
        }
        $this->mysqli->set_charset('utf8');
        $this->connected = true;
    }
    
    /**
     * returns injection clean array
     */
    private function escape_array($inputs = array())
    {
        $clean_inputs = array();
        foreach($inputs as $name => $input) {
            $clean_inputs[$name] = $this->mysqli->real_escape_string($input);
        }
        return $clean_inputs;
    }

    /**
     * executes object query
     */
    public function execute()
    {
        // echo $this->query . "</br>";
        $result = $this->mysqli->query($this->query);
        $this->query = "";
        if(is_bool($result)) {
            // returns bool
            return $result;
        } else if(is_object($result)) {
            // returns object array
            $objects = array();
            while($obj = $result->fetch_object()) {
                array_push($objects, $obj);
            }
            mysqli_free_result($result);
            return $objects;
        }
    }

    public function exists($table_name, $values = array())
    {
        $this->query .= "SELECT `id` FROM `$table_name` WHERE ";
        $valueIndx = 0;
        foreach ($values as $name => $value) {
            $valueIndx++;
            $this->query .= " `$name` = '$value' ";
            if ($valueIndx < count($values)) {
                $this->query .= " AND ";
            }
        }
    }

    /*
     * UPDATE `$table_name` SET '$key' = `$value`
     */
    public function update($table_name, $values = array())
    {
        $values = $this->escape_array($values);
        $this->query .= "UPDATE `$table_name` SET ";
        $valueIndx = 0;
        foreach($values as $name => $value) {
            $valueIndx++;
            $this->query .= "`$name` = '$value'";
            if ($valueIndx < count($values)) {
                $this->query .= ", ";
            }
        }
    }

    /**
     * INSERT INTO $table_name ($params) VALUES ($values)
     */
    public function insert($table_name, $values = array())
    {
        $values = $this->escape_array($values);
        $this->query .= "INSERT INTO `$table_name` (";
        $paramIndx = 0;
        foreach($values as $name => $value) {
            $paramIndx++;
            $this->query .= "`$name`";
            if ($paramIndx < count($values)) {
                $this->query .= ", ";
            }
        }
        $this->query .= ") VALUES (";
        $valueIndx = 0;
        foreach($values as $value) {
            $valueIndx++;
            $this->query .= "'$value'";
            if ($valueIndx < count($values)) {
                $this->query .= ", ";
            }
        }
        $this->query .= ")";
    }

    /**
     * ORDER BY `$row_name` $type
     * DESC or ASC
     */
    public function order_by($row_name, $type)
    {
        $this->query .= "ORDER BY `$row_name` $type ";
    }

    /**
     * SELECT COUNT(`id`) as `count` FROM `$table_name`
     */
    public function count($table_name)
    {
        $this->query = "SELECT COUNT(`id`) as 'count' FROM `$table_name`";
    }

    /**
     * SELECT * FROM $table_name
     * SELECT $values FROM $table_name
     */
    public function select($table_name, $values = array())
    {
        $values = $this->escape_array($values);
        $this->query .= "SELECT";
        if (!empty($values)) {
            $valuesIndx = 0;
            foreach ($values as $value) {
                $valuesIndx++;
                $this->query .= " `$value`";
                if ($valuesIndx < count($values)) {
                    $this->query .= ", ";
                }
            }
        } else {
            $this->query .= " * ";
        }
        $this->query .= " FROM `$table_name`";
    }

    /**
     * WHERE $key = $param AND ...
     * WHERE 1
     */
    public function where($params = array())
    {
        $params = $this->escape_array($params);
        $this->query .= " WHERE ";
        if (!empty($params)) {
            $paramIndx = 0;
            foreach ($params as $name => $param) {
                $paramIndx++;
                $this->query .= " `$name` = '$param' ";
                if ($paramIndx < count($params)) {
                    $this->query .= " AND ";
                }
            }
        } else {
            $this->query .= " 1";
        }
    }

    /**
     * LIMIT $start, $amount
     */
    public function limit($start, $amount)
    {
        $this->query .= " LIMIT $start, $amount";
    }

    /**
     * DELETE FROM $table_name
     */
    public function delete($table_name)
    {
        $this->query .= "DELETE FROM `$table_name` ";
    }
}