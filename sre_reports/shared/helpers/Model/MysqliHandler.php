<?php

/**
 * Smart Report Engine
 * Community Edition
 * Author : Webuccino 
 * All copyrights are preserved to Webuccino
 * URL : https://mysqlreports.com/
 *
 */
if (!defined("DIRECTACESS"))
    exit("No direct script access allowed");

class MysqliHandler {

    protected $host,
            $user,
            $pass,
            $db,
            $link,
            $debug = false,
            $numOfRows = '';

    public function __construct($host, $user, $pass, $isDebug, $db) {
        $this->is_debug($isDebug);
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;
        $this->link = $this->connect();
    }

    private function connect() {
        $this->debug_mode('Mysqli::connect', 'info', '#Attempt connection');
        try {
            $connection = new mysqli($this->host, $this->user, $this->pass, $this->db);
            if ($this->debug) {

                $this->debug_mode('connect', 'info', "");
            }
            $this->debug_mode('connect', 'success', '#connected successfully');
            return $connection;
        } catch (mysqli_sql_exception $e) {
            
          
                $this->debug_mode('connect', 'error', '#connection failed <br/>' . $e->getMessage());
                debug("#### MYSQLI connection error" . $e->getMessage(), "error");
                $this->errorMsg = $e->getMessage();
                
           
            return false;
            
        }catch (Exception $e) {
            
          
                $this->debug_mode('connect', 'error', '#connection failed <br/>' . $e->getMessage());
                debug("#### MYSQLI connection error" . $e->getMessage(), "error");
                $this->errorMsg = $e->getMessage();
                
           
            return false;
            
        }
    }

// this function make query to fetch data from database ( Like using SELECT & SHOW ), this function return array and not handler
    public function query($sqlStatement, $keyType = "NUM", $params = array()) { // $keyType = ASSOC, NUM, BOTH
        $this->debug_mode('query', 'info', '#SQL Query > ' . $sqlStatement);
//$this->debug_mode('query', 'info', '#SQL Query > ' . $params);

        if ($this->link) {
            if ($keyType === "ASSOC")
                $keyType = MYSQLI_ASSOC;
            else if ($keyType === "BOTH")
                $keyType = MYSQLI_BOTH;
            else
                $keyType = MYSQLI_NUM;

            $this->link->set_charset('utf8');
            try {
                $stmt = $this->link->prepare($sqlStatement);
                if (!$stmt) {


                    $this->debug_mode('query', 'error', '#Query Failed while prepare the statment' . $this->link->error);
                    debug("#### Mysqli query error" . $this->link->error, "error");
                    return false;
                }
            } catch (Exception $e) {
                $this->debug_mode('query', 'error', '#Query Failed while prepare the statment' . $this->link->error);
                debug("#### Mysqli query error" . $this->link->error, "error");
                return false;
            }

// this for each to make all items in the params array have new referance to use it in call_user_func_array ...				
            foreach ($params as $key => $value)
                $parameters[$key] = &$params[$key];

            if (count($params) > 0 && isset($params[0])) {
                /*
                  this function do :
                  ------------------
                  if i have function like that
                  function x($z1, $z2){}
                  and i want to get the value from array and put it into this arguments(parameters) here we use
                  call_user_func_array(function if it not in class or if it in class array(object, function),array but make sure all
                  items in this array have reference) and it will bind array value into function arguments ..

                  array(type, value, value, .....)
                  like >> array('is', 1, 'mohamed'); types : i >> integer, s >> string, d >> double, b >> blob

                 */

                $bindparam = @call_user_func_array(array($stmt, "bind_param"), $parameters);
                if (!$bindparam) {

                    $this->debug_mode('query', 'error', '#Query Failed, parameters error <br/>' . $this->link->error);
                    debug("#### Mysqli query error" . $this->link->error, "error");
                    $this->debug_mode('query', 'error', '#Query Failed<br/>  parameters erro)');
                    return false;
                }
            }

            $exec = $stmt->execute();
            if (!$exec) {

                $this->debug_mode('query', 'error', '#Query Failed<br/>' . $this->link->error);
                debug("#### Mysqli query error" . $this->link->error, "error");
                return false;
            }

            $result = $stmt->get_result();

            if (!$result) {
                $this->debug_mode('query', 'error', '#Query Failed while executing the query<br/>' . $this->link->error);
                debug("#### Mysqli query error" . $this->link->error, "error");
                return false;
            } else {
                $this->numOfRows = @$result->num_rows;
                $this->debug_mode('query', 'success', '#Query success : it returns ' . $this->numOfRows . ' rows');
                $fetchedData = $this->get_result($result, $keyType);

                return $fetchedData;
// return $result;
            }
        }
    }

    public function command($sqlStatement, $params = array()) {
        $this->debug_mode('command', 'info', '#SQL Query > ' . $sqlStatement);
        if ($this->link) {
            $this->link->set_charset('utf8');
            $stmt = $this->link->prepare($sqlStatement);
            if (!$stmt) {
                $this->debug_mode('command', 'error', '#Command Failed<br/>' . $this->link->error);
                return false;
            }

// this for each to make all items in the params array have new referance to use it in call_user_func_array ...
            foreach ($params as $key => $value)
                $parameters[$key] = &$params[$key];

            if (count($params) > 0 && isset($params[0])) {
                /*
                  this function do :
                  ------------------
                  if i have function like that
                  function x($z1, $z2){}
                  and i want to get the value from array and put it into this arguments(parameters) here we use
                  call_user_func_array(function if it not in class or if it in class array(object, function),array but make sure all
                  items in this array have reference) and it will bind array value into function arguments ..

                  array(type, value, value, .....)
                  like >> array('is', 1, 'mohamed'); types : i >> integer, s >> string, d >> double, b >> blob

                 */

                $bindparam = @call_user_func_array(array($stmt, "bind_param"), $parameters);
                if (!$bindparam) {
                    $this->debug_mode('command', 'error', '#Command Failed<br/>' . $this->link->error);
                    $this->debug_mode('command', 'error', '#Command Failed<br/> check array of parameters it must be like that array(types, param)');
                    return false;
                }
            }

            $exec = @$stmt->execute();
            if (!$exec) {
                $this->debug_mode('command', 'error', '#Command Failed<br/>' . $this->link->error);
                return false;
            } else {
                $result = $stmt->get_result();
                $this->numOfRows = @$stmt->affected_rows or @ $result->num_rows;
                $this->debug_mode('command', 'success', '#Command success : it returns ' . $this->numOfRows . ' rows');
                return true;
            }
        }
    }

    private function get_result($result, $keyType) {
        $fetchedData = array();
        while ($row = $result->fetch_array($keyType))
            $fetchedData[] = $row;
        return $fetchedData;
    }

    public function sanitize_values($string) {
        $cleaned_string = $string;

        $this->debug_mode('sanitize_values', 'info', '#input string for the sanitize function : ' . $string);
        if ($this->link) {
// $cleaned_string = (get_magic_quotes_gpc()) ? stripslashes($string) : $string;
            $string = str_replace(array("=", "*", " union", "\t", " delete", " select", " insert", " limit", " null", " table", "(", ")", " where", " update"), "", strtolower($string));
            $cleaned_string = $this->link->real_escape_string($string);

            $this->debug_mode('sanitize_values', 'success', '#sanitized string : ' . $cleaned_string);
        }
        return $cleaned_string;
    }

// this function to check if connection failed or succeeded
    public function is_connection_failed() { // if connection failed return true
        if ($this->link === false)
            return true;
        else
            return false;
    }

// this function return number of rows for current query
    public function get_num_rows() {
        return $this->numOfRows;
    }

// this function display what's happened while object of this class start actions ( Just work when debug = true )
    private function debug_mode($functionName, $type, $msg) {
        if ($this->debug) {
            $color = "black"; // by default
            if ($type === "error")
                $color = "red"; // error
            else if ($type === "success")
                $color = "green"; // success
            else if ($type === "info")
                $color = "blue"; // info


            logging("\n ## DbHandler -> mysqli  : " . $msg);
        }
    }

// this function return database handler type
    public function get_db_handler_type() {
        return 'mysqli';
    }

// this function set debug mode
    public function is_debug($bool) {
        if ($bool === true)
            $this->debug = true;
        else
            $this->debug = false;
    }

// this function for close connection
    public function close_connection() {
        if ($this->link) {
            $this->link->close();
            $this->link = null;
        }
    }

}

?>