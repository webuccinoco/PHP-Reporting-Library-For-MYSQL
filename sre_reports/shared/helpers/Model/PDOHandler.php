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

class PDOHandler {

    protected $host,
            $user,
            $pass,
            $db,
            $link,
            $debug = false,
            $numOfRows = '';
    protected $keyTypes = array("NUM", "ASSOC", "BOTH");

    public function __construct($host, $user, $pass, $db, $isDebug) {
        $this->is_debug($isDebug);
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;
        $this->link = $this->connect();
    }

    private function connect() {
        $this->debug_mode('PDO::connect', 'info', '#Attempt connection');
        try {
            try {
                $connection = @new pdo('mysql:host=' . $this->host . ';dbname=' . $this->db, $this->user, $this->pass);
            } catch (PDOException $ex) {
                $connection = @new pdo('mysql:host=' . $this->host . ';dbname=' . $this->db . ';charset=UTF-8', $this->user, $this->pass);
            }

            if ($this->debug) {

                $this->debug_mode('connect', 'info', "");
            }
        } catch (PDOException $e) {
            $this->debug_mode('connect', 'error', '#connection failed: ' . $e->getMessage());
            debug("## PDO Connection failed".$e->getMessage(),"error");
            return false;
        }
        $this->debug_mode('connect', 'success', '#connected successfully');
        return $connection;
    }

    // this function make query to fetch data from database ( Like using SELECT & SHOW ), this function return array and not handler
    public function query($sqlStatement, $keyType = "NUM", $params = array()) { // $keyType = ASSOC, NUM, BOTH

        if ($keyType === 'BOTH')
            $keyType = PDO::FETCH_BOTH; //PDO::FETCH_BOTH
        else if ($keyType === 'ASSOC')
            $keyType = PDO::FETCH_ASSOC; //PDO::FETCH_ASSOC
        else
            $keyType = PDO::FETCH_NUM; //PDO::FETCH_NUM

        $this->debug_mode('query', 'info', $sqlStatement);
        if ($this->link) {
            try {
                $this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $query = $this->link->prepare($sqlStatement);
                if ($query && count($params) > 0)
                    $result = $query->execute($params);
                else
                    $result = $query->execute();

                if (!$result) {
                    
                    $this->debug_mode('query', 'fail', '#Query failed' . implode(",", $this->link->errorInfo()));
                      debug("#### PDO Query error". $this->link->errorInfo(),"error");
                    return false;
                } else {
                    $this->numOfRows = @$query->rowCount();
                    $this->debug_mode('query', 'success', '#Query success : it returns ' . $this->numOfRows . ' rows');

                    $fetchedData = $query->fetchAll($keyType);

                    $query->closeCursor();
                    return $fetchedData;
                }
            } catch (PDOException $e) {
                $this->debug_mode('query', 'error', '#query failed: ' . $e->getMessage());
                debug("#### PDO Query error". $e->getMessage(),"error");
                return false;
            }
        }
    }

    public function sanitize_values($string) {
        $cleaned_string = $string;

        $this->debug_mode('sanitize_values', 'info', '#input string for the sanitize function : ' . $string);
        if ($this->link) {
           // $cleaned_string = (get_magic_quotes_gpc()) ? stripslashes($string) : $string;
            // $cleaned_string =  $this->link->quote($string);
            $cleaned_string = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $cleaned_string);
            $cleaned_string = str_replace(array(" union", " delete", " select", " insert", "=", "\t", "*", " limit", " null", " table", "(", ")", " where", " update"), "", strtolower($cleaned_string));
            $this->debug_mode('sanitize_values', 'success', '#sanitized string : ' . $cleaned_string);
        }
        return $cleaned_string;
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

            debug("\n ## DbHandler -> PDO : " . $msg);
        }
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

    // this function return database handler type
    public function get_db_handler_type() {
        return 'pdo';
    }

    // this function set debug mode
    public function is_debug($bool) {
        if ($bool !== false)
            $bool = true;
        $this->debug = $bool;
    }

    // this function for close connection
    public function close_connection() {
        if ($this->link) {
            $this->link = null;
        }
    }

}
