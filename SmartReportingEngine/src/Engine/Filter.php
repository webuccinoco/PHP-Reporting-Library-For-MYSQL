<?php

/**
 * Smart Report Engine
 * Community Edition
 * Author : Webuccino
 * All copyrights are preserved to Webuccino
 * URL : https://mysqlreports.com
 *
 */

namespace SRE\Engine;

class Filter {

    private $tables_filters;

    public function __construct() {
        $this->tables_filters = array();
    }

    public function get_tables_filters() {
        return $this->tables_filters;
    }

    protected function filter($table, $column, $operator, $parameter = "", $column_data_type = SRE_NUMBER) {

        $this_table_filter_array = array();
        $allowed_parameter_types = array(SRE_DATE, SRE_NUMBER, SRE_TEXT);
        $allowed_operators = array("equal", "not_equal", "less", "more", "less_and_equal", "more_and_equal", "like", "not_like","is_year","is_month","is_today","is_user","is_null","is_not_null");


        if (!empty($column) && in_array(strtolower($operator), $allowed_operators) && in_array($column_data_type, $allowed_parameter_types)) {
            //construct array
         
            $column = str_replace("`","",strtolower($column));
          
            $index = count($this->tables_filters) + 1;
            $key = "filter$index";
            if(strstr(strtolower($operator),"null")){
                 $this_table_filter_array = array(
                    "sql" => "`" . $column . "`  <->  " . $this->get_edited_operator(strtolower($operator)) ,
                    "param" => "null_value",
                    "type" => "null_value"
                );
            }
            elseif ($column_data_type === SRE_DATE) {
                $date_param = (Helper::is_date($parameter))?Helper::format_date_param($parameter, $column_data_type):$parameter;
                $this_table_filter_array = array(
                    "sql" => "`" . $column . "`  <->  " . $this->get_edited_operator(strtolower($operator)) . "  ?",
                    "param" => $date_param,
                    "type" => $this->get_edited_parameter_type($column_data_type)
                );
            } elseif ($column_data_type === SRE_NUMBER || $column_data_type === SRE_TEXT) {
            
              
                $this_table_filter_array = array(
                    "sql" => "`" . $column . "`  <->  " . $this->get_edited_operator(strtolower($operator)) . "  ?",
                    "param" => $parameter,
                    "type" => $this->get_edited_parameter_type($column_data_type)
                );
            }
            if (!empty($this_table_filter_array)) {
                $existed_kay = array_search($this_table_filter_array, $this->tables_filters);

                if ($existed_kay)
                    $this->tables_filters[$existed_kay] = $this_table_filter_array;
                else
                    $this->tables_filters[$key] = $this_table_filter_array;
            }
        }
    }

    public function between($column, $first_param, $second_param, $parameters_type = SRE_NUMBER) {
        $allowed_parameter_types = array(SRE_DATE, SRE_NUMBER, SRE_TEXT);

        if (!empty($column) && in_array($parameters_type, $allowed_parameter_types)) {
            $this->filter("", $column, "more", $first_param, $parameters_type);
            $this->filter("", $column, "less", $second_param, $parameters_type);
        }
    }

    public function more( $column, $param, $is_or_equal = false, $parameters_type = SRE_NUMBER) {
        $allowed_parameter_types = array(SRE_DATE, SRE_NUMBER, SRE_TEXT);

        if (!empty($column) && in_array($parameters_type, $allowed_parameter_types)) {
            if ($is_or_equal)
                $this->filter("", $column, "more_and_equal", $param, $parameters_type);
            else
                $this->filter("", $column, "more", $param, $parameters_type);
        }
    }

    public function less($column, $param, $is_or_equal = false, $parameters_type = SRE_NUMBER) {
        $allowed_parameter_types = array(SRE_DATE, SRE_NUMBER, SRE_TEXT);

        if ( !empty($column) && in_array($parameters_type, $allowed_parameter_types)) {
            if ($is_or_equal)
                $this->filter("", $column, "less_and_equal", $param, $parameters_type);
            else
                $this->filter("", $column, "less", $param, $parameters_type);
        }
    }

    public function equal( $column, $param, $parameters_type = SRE_NUMBER) {
        $allowed_parameter_types = array(SRE_DATE, SRE_NUMBER, SRE_TEXT);

        if ( in_array($parameters_type, $allowed_parameter_types)) {
            $this->filter("", $column, "equal", $param, $parameters_type);
        }
    }

    public function not_equal($column, $param, $parameters_type = SRE_NUMBER) {
        $allowed_parameter_types = array(SRE_DATE, SRE_NUMBER, SRE_TEXT);

        if ( !empty($column) && in_array($parameters_type, $allowed_parameter_types)) {
            $this->filter("", $column, "not_equal", $param, $parameters_type);
        }
    }

    public function like($column, $param) {


        if (!empty($column)) {
            $this->filter("", $column, "like", $param, SRE_TEXT);
        }
    }

    public function not_like($column, $param) {


        if (!empty($column)) {
            $this->filter("", $column, "not_like", $param, SRE_TEXT);
        }
    }

    public function is_null($column) {


        if (!empty($column)) {
            $this->filter("", $column, "is_null","",SRE_TEXT);
        }
    }

    public function is_not_null($column) {


        if (!empty($column)) {
            $this->filter("", $column, "is_not_null","",SRE_TEXT);
        }
    }

  
   

    private function get_edited_operator($operator) {
        switch ($operator) {


            case "equal":
                return "=";
                break;
            case "not_equal":
                return "!=";
                break;
            case "more":
                return ">";
                break;
            case "less":
                return "<";
                break;
            case "more_and_equal":
                return ">=";
                break;
            case "less_and_equal":
                return "<=";
                break;
            case "like":
                return "like";
                break;
            case "not_like":
                return "not like";
                break;
            case "is_null":
                return "IS NULL";
            case "is_not_null":
                return "IS NOT NULL";
           
        }
    }

    //$allowed_types = array("date", "number", "string");
    private function get_edited_parameter_type($type) {
        switch ($type) {
            case SRE_NUMBER:
                return "i";
                break;
            default:
                return "s";
                break;
        }
    }

}

/*
 * \endcond
 */