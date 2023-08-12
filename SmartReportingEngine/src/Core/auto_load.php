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

require_once ("../shared/helpers/Model/search.php");
require_once ("../shared/helpers/Model/Report.php");
require_once ("../shared/helpers/Model/TableReport.php");
require_once ("../shared/helpers/functions.php");
require_once ("../shared/helpers/celltypes.php");
require_once ("../shared/helpers/lib.php");
require_once ("../shared/helpers/export.php");


/*
 * #################################################################################################
 * Creating the Report Sql
 * ################################################################################################
 */


$sql = Prepare_TSql();

if ($empty_search_parameters || $possible_attack) {
    // case user send empty search keywords or entered the $Enter_your_search_lang in the search box

    $all_records = array();
    $nRecords = 0;
    $empty_Report = true;
    $numberOfPages = 1;
    $records_per_page = 10;
} else {

    $all_records = query($sql [0], "LayOut : Prepare SQL", $sql [1], $sql [2]);

    $nRecords = (is_array($all_records)) ? count($all_records) : 0;
    if ($records_per_page == 0) {
        $records_per_page = 10;
    }

    $numberOfPages = ceil($nRecords / $records_per_page);
    if ($numberOfPages == 0 || $nRecords == 0) {
        $empty_Report = true;
        $numberOfPages = 1;
    } else {
        $empty_Report = false;
    }
}
$levels = count($group_by);
