<?php

/**
 * Smart Report Engine
 * Community Edition
 * Author : Webuccino 
 * All copyrights are preserved to Webuccino
 * URL : https://mysqlreports.com/
 *
 */

namespace SRE\Engine;

class Helper {

    private static function is_valid_param($param, $param_type) {
        if (empty($param)) {
            return false;
        } elseif ($param_type == "i" && is_numeric($param)) {
            return true;
        } elseif ($param_type == "s" && is_string($param)) {
            return true;
        } else {
            return false;
        }
    }

    public static function format_date_param($parameter, $parameter_type) {

        if (strtolower($parameter_type) == "date") {
            return date("Y-m-d", strtotime($parameter));
        } else {
            return $parameter;
        }
    }

    public static function is_date($str) {
        $stamp = strtotime($str);
        if (!is_numeric($stamp)) {
            return false;
        }
        $month = date('m', $stamp);
        $day = date('d', $stamp);
        $year = date('Y', $stamp);
        if (checkdate($month, $day, $year)) {
            return true;
        } else {
            return false;
        }
    }

    public static function validate_filters_array($filters) {
        return true;
    }

    public static function remove_table_part($arr, $in_keys = false) {
        $tmp = array();


        if (!$in_keys) {
            foreach ($arr as $v) {
                if (!is_array($v)) {
                    $pieces = explode(".", $v);
                    $tmp[] = $pieces[count($pieces) - 1];
                } else {
                    $tmp[] = Helper::remove_table_part($v, false);
                }
            }
        } else {

            foreach ($arr as $k => $v) {

                $pieces = explode(".", $k);
                $tmp[$pieces[count($pieces) - 1]] = $v;
            }
        }

        return $tmp;
    }

    public static function validate_table_exist($array, $in_keys = false) {
        if (!$in_keys) {
            foreach ($array as $v) {
                if (!is_array($v)) {
                    if (!strstr($v, ".")) {
                        return false;
                    }
                } else {

                    if (!strstr($v[0], ".")) {
                        return false;
                    }
                }
            }
        } else {
            foreach ($array as $k => $v) {
                if (!strstr($k, ".")) {
                    return false;
                }
            }
        }
        return true;
    }

    public static function log($exception_message, $exception_no) {
        error_log("Exception thrown by smart report engine, exception number  $exception_no  , exception message : $exception_message");
    }
    
    public static function is_current_user_filter($filters){
        foreach($filters as $filter){
            if(strstr(strtolower($filter["param"]),"session_of")){
                return true;
            }
        }
        return false;
    }
    
    

}
