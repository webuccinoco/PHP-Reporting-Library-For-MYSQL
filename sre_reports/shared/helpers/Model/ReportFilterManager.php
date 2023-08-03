<?php

/**
 * Smart Report Engine
 * Community Edition
 * Author : Webuccino
 * All copyrights are preserved to Webuccino
 * URL : https://mysqlreports.com/
 */
if (!defined("DIRECTACESS"))
    exit("No direct script access allowed");

class FilterManager {

    
    public $column;
    public $column_datatype;
    public $filter_type;
    public $filter_value_1;
    public $filter_value_2;
    public $all_filters;
    private $name;
    

    public function __construct() {
       
        $this->filter_value_2 = "";

        $this->all_filters = array(
            "like",
            "not like",
            "begin with",
            "end with",
            "contain",
            "equal",
            "not equal",
            "greater than",
            "less than",
            "greater than or equal",
            "less than or equal",
            "is NULL",
            "is not NULL"
          
                )
        ;
    }



   


    private function get_type() {
        if ($this->filter_value_2 == "" && (strtolower($this->column_datatype) == "int" || strtolower($this->column_datatype) == "integer")) {
            return "i";
        } elseif ($this->filter_value_2 != "" && (strtolower($this->column_datatype) == "int" || strtolower($this->column_datatype) == "integer")) {
            return "ii";
        } elseif ($this->filter_value_2 == "" && (strtolower($this->column_datatype) == "decimal" || strtolower($this->column_datatype) == "float" || strtolower($this->column_datatype) == "double")) {
            return "d";
        } elseif ($this->filter_value_2 != "" && (strtolower($this->column_datatype) == "decimal" || strtolower($this->column_datatype) == "float" || strtolower($this->column_datatype) == "double")) {
            return "dd";
        } elseif ($this->filter_value_2 != "") {
            return "ss";
        } else {
            return "s";
        }
    }



    private function get_sql() {
        $sql = "";
     
        $this->column = str_replace("`", "", $this->column);
        $sql .= "`.`" . $this->column . "` ";
        if (strtolower($this->filter_type) == "is null" || strtolower($this->filter_type) == "is not null") {

            $sql .= " <-> " . $this->get_operator() . "";
        } elseif (strtolower($this->filter_type) != "between") {
            $sql .= " <-> " . $this->get_operator() . " ?";
        } else {
            $sql .= "  <-> > ? and `" . $this->column . "`  <-> < ?";
        }
        return $sql;
    }

    private function get_operator() {
        switch (strtolower($this->filter_type)) {
            case "like" :
                return "LIKE";
            case "not like" :
                return "NOT LIKE";
            case "begin with" :
                return "LIKE";
            case "end with" :
                return "LIKE";
            case "contain" :
                return "LIKE";
            case "equal":
                return "=";
            case "not equal":
                return "!=";
            case "greater than":
                return ">";
            case "less than":
                return "<";
            case "greater than or equal":
                return ">=";
            case "less than or equal":
                return "<=";
            case "is NULL":
                return "is_null";
            case "is not NULL":
                return "is_not_null";
           
        }
    }

}
