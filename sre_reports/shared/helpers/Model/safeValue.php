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
$log = "";
debug("Original request");
log_array($_POST);
log_array($_GET);
$send_log_details = false;


$obj_captcha = false;

debug("After intial sanitization");
log_array($_POST);
log_array($_GET);



/*
 * #################################################################################################
 * Logging functions
 * ################################################################################################
 */

/*
 * logging
 *
 * Add a message to the $log variable
 *
 * @param (str) the message to be logged
 */

function logging($str, $severity = "info") {
    global $log, $logging_severity;
    
    if (check_debug_mode() === 1) {
        $logging_meta_data = date("Y-m-d H:i:s").",".$_SERVER["REMOTE_ADDR"].",";
        if ($logging_severity === "error") {
            if ($severity == "error") {
                $log .= $logging_meta_data.$str . PHP_EOL;
            }
        } else {
            $log .= $str . PHP_EOL;
        }
    }
}

/*
 * logging
 *
 * Add an array to the $log variable
 *
 * @param (arr) the array to be logged
 */

function log_array($arr) {
    if (count($arr) > 0) {
        foreach ($arr as $key => $val) {
            if (!is_array($val)) {
                logging("\n   $key   : $val");
            } else {
                logging("\n   $key is an array: \n ");
                log_array($val);
            }
        }
    }
}

/*
 * #################################################################################################
 * Out put escaping .
 * ################################################################################################
 */
/*
 * escape
 *
 * output escaping of an output.
 *
 * @param (val) the output to be escaped .
 */

function escape($val) {
    $val = str_ireplace("<script>", "", $val);
    if ($val === "&nbsp;" || $val === "&nbsp")
        return $val;
    else
        return htmlentities(strip_tags($val), ENT_QUOTES, "UTF-8");
}

/*
 * #################################################################################################
 * Filtration functions .
 * ################################################################################################
 */
require_once ("Filters.php");

/*
 * #################################################################################################
 * Sanitization functions
 * ################################################################################################
 */

require_once ("Cleaners.php");

/*
 * #################################################################################################
 * SuperGlobal Security functions
 * ################################################################################################
 */
/*
 * remove_unexpected_superglobals
 *
 * Remove the "the unexpected" elements from a superglobal array AND santitized the expected elements according to sanitization types
 *
 * @param (index) the index of the element to be retrieved
 * @param (data_type) set to "int" , "float" , "no_specials" , "email", "string" and "array"
 * @param (global_array) The array to get the element from
 *
 * @return the sanitized variable or false if the index is not set .
 */

function remove_unexpected_superglobals($superGlobal, $allowedKeys) {

    // this function removes any Unexpected keys from super globals
    $integer_keys = array(
        "start",
        "print",
        'detail',
        "SearchField",
        'cp'
    );
    $email_keys = array(
        "from",
        "to"
    );
    $boolean_keys = array(
        "btnSearch",
        "btnordnarySearch",
        "btnShowAll",
        "btnShowAll2",
        "loginBtn",
        "save",
        "submit"
    );
    $login_keys = array(
        "name",
        "pass"
    );
    $no_specials = array();
    $float_keys = array();
    $arr = array();

    foreach ($superGlobal as $key => $val) {
        if (in_array($key, $allowedKeys) || $key = "RequestToken") {
            // Allowed key
            if (in_array($key, $integer_keys)) {
                // clean int keys
                $arr [$key] = (int) get($key, "int", $superGlobal);
            } elseif (in_array($key, $email_keys)) {
                // clean email keys
                $arr [$key] = get($key, "email", $superGlobal);
            } elseif (in_array($key, $no_specials)) {
                $arr [$key] = get($key, "no_specials", $superGlobal);
            } elseif (in_array($key, $boolean_keys)) {
                $arr [$key] = get($key, "boolean", $superGlobal);
            } elseif (in_array($key, $float_keys)) {
                $arr [$key] = (float) get($key, "float", $superGlobal);
            } elseif (in_array($key, $login_keys)) {

                $arr [$key] = get($key, "login_info", $superGlobal);
            } else {
                // clean strings
                $arr [$key] = get($key, "string", $superGlobal);
            }
        } else {

            // Not allowed super global .bad request .
            unset($superGlobal [$key]);
        }
    }

    return $arr;
}

/*
 * get
 *
 * Getting an element from a super global array after sanitizing it according to its data type
 *
 * @param (index) the index of the element to be retrieved
 * @param (data_type) set to "int" , "float" , "no_specials" ,"boolean", "email", "string","lockup", and "array"
 * @param (global_array) The array to get the element from
 * @param(options) and (default) can be used only with the lockup cleaner
 *
 * @return the sanitized variable or false if the index is not set .
 */

function get($index, $data_type = "string", $global_array, $options = array(), $default = "") {


    $get = $global_array [$index];


    if ($data_type == "int")
        $get = (int) clean_number($get, "int");
    elseif ($data_type == "float")
        $get = (float) clean_number($get, "float");
    elseif ($data_type == "email")
        $get = clean_email($get);
    elseif ($data_type == "no_specials")
        $get = clean_input($get, true);
    elseif ($data_type == "boolean")
        $get = clean_boolean($get);
    elseif ($data_type == "lockup")
        $get = clean_lockup($get, $options, $default);
    elseif (is_array($get))
        $get = clean_array($get);
    elseif ($data_type == "login_info") {

        $get = $get;
    } else
        $get = clean_input($get);


    return $get;
}

/*
 * clean_input_array
 *
 * Sanatize a super global array used in the wizard
 *
 * @param (arr) the super global array to be Sanitized
 *
 * @return the sanitized array .
 */

function clean_input_array($arr) {
    global $hc;
    $clean = array();
    foreach ($arr as $k => $v) {
        if (is_array($v))
            $clean[clean_input($hc->xss_clean($k))] = clean_input_array($hc->xss_clean($v));
        else
            $clean[clean_input($hc->xss_clean($k))] = clean_input($hc->xss_clean($v), false, false, true, array("`", "=", ".", "-", "_"));
    }
    return $clean;
}

/*
 * #################################################################################################
 * Encoding and Encryption functions
 * ################################################################################################
 */

/*
 * decode
 *
 * decoding the encoded variable in the config file
 *
 * @param (encoded) the encoded variable.
 */

function decode($encoded) {
    return base64_decode($encoded);
}

/*
 * #################################################################################################
 * Debuging functions & Sending Log by email while trouble shooting .
 * ################################################################################################
 */

/*
 * send_log_info
 *
 * Send $log (contains all logs) variable to the maintanance email address existed in the config file, if the debug URL is provided and a valid maintanace email exists
 * a validation process is done in the function to make sure the maintatnce email and debug URL both are valid before sending .
 * @param ($maintainance_email) the maintanance email.
 */

function send_log_info($maintainance_email) {
    global $send_log_details,$file_name, $log, $_CLEANED, $general_maintainance_log_path, $general_maintainance_mode, $maintainance_log_path;
    
     if($send_log_details){
         return;
     }else{
         $send_log_details = true;
     }

    if (check_debug_mode() === 1) {




        $email_message = "This message is sent automatically from your own server (based on your request) for troubleshooting a problem in a report generated by a full version of smart report maker installed on your own server." . PHP_EOL;
        $email_message .= "The following is a log of all processes done for generating the report, please send this log via our support system to help our team   understanding the problem(s) correctly ." . PHP_EOL;
        $message = !empty($log) ? PHP_EOL." ## Start Report Generation  ##" . PHP_EOL . $log  . " ## End Report Generation ##  " . PHP_EOL: "";
        $email_footer_message = PHP_EOL . " Please not that : " . PHP_EOL . " In order to stop receiving the same message again please open the config file of the generated report and remove this email address  from the maintainance_email by making it like the following : " . PHP_EOL;
        $email_footer_message .= PHP_EOL . 'maintainance_email = ""; ';
        if (!empty($log)&&filter_var($maintainance_email, FILTER_VALIDATE_EMAIL)) {
            @mail($maintainance_email, "Smart Report Maker Troubleshooting", $email_message . $message . $email_footer_message);
        }
        $log_file = $file_name."_log";
        // Mode if general log is enabled
        if (!empty($log)&&!empty($general_maintainance_log_path) && is_dir($general_maintainance_log_path)) {
            $general_maintainance_log_path = rtrim($general_maintainance_log_path, "/");
            $general_maintainance_log_path = $general_maintainance_log_path . "/";
            $fp1 = fopen($general_maintainance_log_path . $log_file, "a+");
            fwrite($fp1, $message);
            fclose($fp1);
        }
        // mode if log is enabled for one report
        if (!empty($log)&&!empty($maintainance_log_path) && is_dir($maintainance_log_path)) {
            $maintainance_log_path = rtrim($maintainance_log_path, "/");
            $maintainance_log_path = $maintainance_log_path . "/";
            $fp2 = fopen( $maintainance_log_path . $log_file, "a+");
            fwrite($fp2, $message);
            fclose($fp2);
        }
    }
}

function debug($str, $severity = "info") {

    logging($str, $severity);
}

function check_debug_mode() {

    global $maintainance_email, $maintainance_mode, $maintainance_log_path, $general_maintainance_log_path, $general_maintainance_mode;
    if (!isset($maintainance_log_path))
        $maintainance_log_path = "";
    if ($general_maintainance_mode === true || $maintainance_mode === true || strtolower($maintainance_mode) === "yes") {
        if ((!empty($general_maintainance_log_path) && is_dir($general_maintainance_log_path)) || filter_var($maintainance_email, FILTER_VALIDATE_EMAIL) || (!empty($maintainance_log_path) && is_dir($maintainance_log_path)))
            return 1;
        else
            return false;
    } else {
        return false;
    }
}

?>