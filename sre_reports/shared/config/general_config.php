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
/*
 * #################################################################################################
 * Sessions and Request Settings
 * ################################################################################################
 */
$general_maintainance_mode = false;
$general_maintainance_log_path = "";
$logging_severity = "error"; 
$custom_logo="";
$show_mobile_layout = "no";
$limited_time_session = "no"; // session has an expiration time .
$session_timeout = 7200; // The maximum time a report is left idle in seconds
$proxy_detect = "no";




/*
 * #################################################################################################
 * Report Tool bar settings
 * ################################################################################################
 */
$allow_print_view = "yes"; // show print icon in the menu of the report
$allow_export = "yes"; // show export icon in the menu of the report
$allow_change_style = "yes"; // show change style icon in the menu of the report
$allow_change_layout = "yes"; // show change layout icon in the menu of the report
$allow_email = "yes"; // show send email icon on the menu of the report
$chkSearch = 'yes'; // show the search box in the report
$allow_delete_filter = "yes"; //delete filter to show popup again 
$allow_request_token_login = "yes"; // validate a token when login
$automatic_mobile_view = "yes"; //if any layout is loaded from a mobile screen it should be the mobile layout . 
$prevent_overwrite_existing_tables = "yes"; //if turn to no will overwrite existing reports when creating a new report with the same name
$languages_array = array(
    "Arabic" => "ar",
    "English" => "en",
    "French" => "fr",
    "German" => "de",
    "spanish" => "es"
);

