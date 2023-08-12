<?php

/**
 * Smart Report Engine
 * Community Edition
 * Author : Webuccino 
 * All copyrights are preserved to Webuccino
 * URL : https://mysqlreports.com/
 *
 */
define("DIRECTACESS", "true");
ob_start();
/*
 * #################################################################################################
 * Load Report Files .
 * ################################################################################################
 */
// Main files to load report settings, ACL, detect user screen and security

require_once ("request.php");
ob_end_clean();
// the active report file name
$_SESSION ["active_report_srm7"] = basename(__FILE__);


$_export_option_options = array(
    "pdf",
    "pdf1",
    "csv",
    "csv1",
    "xml",
    "xml1",
    "xls",
    "xls1"
);
// files to load report's data
if (isset($_GET ["export"]) && in_array($_GET ["export"], $_export_option_options)) {
    ob_start();
    require_once ("auto_load.php");
    ob_end_clean();
} else {
    require_once ("auto_load.php");
}


/*
 * #################################################################################################
 * Getting print options
 * ################################################################################################
 */
$link_home = $_SERVER ["PHP_SELF"];

/*
 *
 *
 * /*
 * #################################################################################################
 * Handling Paggination
 * ################################################################################################
 */
if (!$empty_Report) {
    // case there is records for pagginations
    if (isset($_CLEANED ['start']) && isset($_CLEANED ["RequestToken"]) && $_CLEANED ["RequestToken"] == $_SESSION [$request_token]) {
        if (check_numeric_parameter($_CLEANED ['start'], $nRecords - 1)) {
            $_startRecord_index = (int) $_CLEANED ['start'];
        } else {
            $_CLEANED ['start'] = 0;
            $_startRecord_index = 0;
            // case start is set but not clean
            header("location: " . $_SERVER ["PHP_SELF"] . "?start=$_startRecord_index" . "&&RequestToken=$request_token_value");
            exit();
        }
    } else {
        $_startRecord_index = 0;
    }

    $numberOfPages = ceil($nRecords / $records_per_page);
    if (isset($_CLEANED ['cp']) && isset($_CLEANED ["RequestToken"]) && $_CLEANED ["RequestToken"] == $_SESSION [$request_token]) {
        if (check_numeric_parameter($_CLEANED ['cp'], $numberOfPages, false, 0)) {
            $currentPage = (int) $_CLEANED ['cp'];
            $_correct_startRecord_index = ($currentPage - 1) * (int) $records_per_page;
            $_startRecord_index = $_correct_startRecord_index;
            if (isset($_CLEANED ['start']) && $_CLEANED ['start'] != $_correct_startRecord_index) {

                // case of conflect between the value of $start and the value of $cp

                header("location: " . $_SERVER ["PHP_SELF"] . "?start=$_correct_startRecord_index&&cp=$currentPage" . "&&RequestToken=$request_token_value");
                exit();
            }
        } else {
            // case $cp is not clean

            header("location: " . $_SERVER ["PHP_SELF"] . "?cp=1" . "&&RequestToken=$request_token_value");
            exit();
        }
    } else {
        // case $cp is not set
        $currentPage = $_startRecord_index == 0 ? 1 : floor($_startRecord_index / $records_per_page) + 1;
    }

    if ($_startRecord_index >= $nRecords || $_startRecord_index < 0 || $currentPage > $numberOfPages || $currentPage < 1) {
        // case values of start or cp are too big or too small

        header("location: " . $_SERVER ["PHP_SELF"] . "?cp=1" . "&&RequestToken=$request_token_value");
        exit();
    }

    $firstPage = $_SERVER ['PHP_SELF'] . "?cp=1" . "&&RequestToken=$request_token_value" ;
    $lastPage = $_SERVER ['PHP_SELF'] . "?cp=" . (int) $numberOfPages . "&&RequestToken=$request_token_value" ;
    $nextPage = $currentPage + 1 <= $numberOfPages ? $_SERVER ['PHP_SELF'] . "?cp=" . (int) ($currentPage + 1) . "&&RequestToken=$request_token_value"  : '#';
    $prevPage = $currentPage - 1 > 0 ? $_SERVER ['PHP_SELF'] . "?cp=" . (int) ($currentPage - 1) . "&&RequestToken=$request_token_value"  : '#';
    $fromRecordNumber = $_startRecord_index == 0 ? 1 : (int) $_startRecord_index;
    $toRecordNumber = $currentPage == $numberOfPages ? (int) ($_startRecord_index + ($nRecords - $_startRecord_index)) : (int) ($_startRecord_index + $records_per_page);
} else {
    // no records for validation like in empty search results
    $_startRecord_index = 0;
    $currentPage = 1;
}

if (isset($_CLEANED ['export']) && !empty($_CLEANED ['export']) && isset($_CLEANED ["RequestToken"]) && $_CLEANED ["RequestToken"] == $_SESSION [$request_token]) {
    $_export_key = array_search($_CLEANED ['export'], $_export_option_options);
    if (is_numeric($_export_key) && $_export_key > - 1 && $_export_key < count($_export_option_options)) {
        $_export_option = $_export_option_options [$_export_key];
    } else {
        $_export_option = "";
    }
} else {
    $_export_option = "";
}

$_CLEANED ['export'] = "";
unset($_CLEANED ['export']);

if ($_export_option == 'csv') {

    export_csv($sql, false, 0, 10, $nRecords);

    exit();
} elseif ($_export_option == 'csv1') {

    export_csv($sql, true, $_startRecord_index, $records_per_page, $nRecords);

    exit();
} else if ($_export_option == 'xml') {

    export_xml($sql, false, 0, 10, $nRecords);

    exit();
} elseif ($_export_option == 'xml1') {

    export_xml($sql, true, $_startRecord_index, $records_per_page, $nRecords);

    exit();
}  // elseif ($_export_option == 'xls1') {
// export_xls ( $sql, true, $_startRecord_index, $records_per_page, $nRecords );
// exit ();
// }
// elseif ($_export_option == 'xls') {
// export_xls ( $sql, false, 0, 10, $nRecords );
// exit ();
// }
elseif ($_export_option == 'pdf1' && version_compare(PHP_VERSION, '5.3.0') >= 0) {

    if (count($fields) > 5)
        route_pdf( 'a4', 'landscape', 10, 10, 10, 10, 780, 800, 10, 11, true, $_startRecord_index, $records_per_page, $nRecords);
    else
        route_pdf( 'a4', 'portrait', 10, 10, 10, 10, 490, 500, 9, 10, true, $_startRecord_index, $records_per_page, $nRecords);

    exit();
}elseif ($_export_option == 'pdf1' && version_compare(PHP_VERSION, '5.3.0') == -1) {
    if (count($fields) > 5)
        route_pdf( 'a4', 'landscape', 10, 10, 10, 10, 780, 800, 10, 11, true, $_startRecord_index, $records_per_page, $nRecords);
    else
        route_pdf( 'a4', 'portrait', 10, 10, 10, 10, 490, 500, 9, 10, true, $_startRecord_index, $records_per_page, $nRecords);

    exit();
}

else if ($_export_option == 'pdf') {

    if (count($fields) > 8)
        route_pdf( '', 'landscape', 10, 10, 10, 10, 780, 800, 10, 11, false, $_startRecord_index, $records_per_page, $nRecords);
    else
        route_pdf( '', 'portrait', 10, 10, 10, 10, 490, 500, 9, 10, false, $_startRecord_index, $records_per_page, $nRecords);

    exit();
}

/*
 * #################################################################################################
 * Creating the report Links
 * ################################################################################################
 */

// exporting links

$link_pdf_current = $_SERVER ["PHP_SELF"] . "?export=pdf1&&start=" . (int) $_startRecord_index . "&&RequestToken=$request_token_value" ;

$link_csv_current = $_SERVER ["PHP_SELF"] . "?export=csv1&&start=" . (int) $_startRecord_index . "&&RequestToken=$request_token_value";

$link_xml_current = $_SERVER ["PHP_SELF"] . "?export=xml1&&start=" . (int) $_startRecord_index . "&&RequestToken=$request_token_value" ;

$link_xls_current = $_SERVER ["PHP_SELF"] . "?export=xls1&&start=" . (int) $_startRecord_index . "&&RequestToken=$request_token_value" ;

$link_csv_all = $_SERVER ["PHP_SELF"] . "?export=csv" . "&&RequestToken=$request_token_value" ;

$link_xml_all = $_SERVER ["PHP_SELF"] . "?export=xml" . "&&RequestToken=$request_token_value";

$link_pdf_all = $_SERVER ["PHP_SELF"] . "?export=pdf" . "&&RequestToken=$request_token_value";

$link_pdf_xls = $_SERVER ["PHP_SELF"] . "?export=xls" . "&&RequestToken=$request_token_value" ;

// ********************************************/
// **************************print links******************************

$link_print1 = $_SERVER ["PHP_SELF"] . "?print=1&&start=" . (int) $_startRecord_index . "&&RequestToken=$request_token_value";

$link_print2 = $_SERVER ["PHP_SELF"] . "?print=2" . "&&RequestToken=$request_token_value";

$link_print_real = $_SERVER ['PHP_SELF'] . "?print=3&start=" . (int) $_startRecord_index . "&&RequestToken=$request_token_value";

// *************************next and prev links for mobile *********************
if (!$empty_Report) {
    $next_start = (int) ($_startRecord_index + $records_per_page);

    if ($next_start >= $nRecords)
        $next_start = (int) $_startRecord_index;

    $link_next = $_SERVER ["PHP_SELF"] . "?start=$next_start" . "&&RequestToken=$request_token_value";

    $prev_start = $_startRecord_index - $records_per_page;

    if ($prev_start < 0)
        $prev_start = 0;

    $link_prev = $_SERVER ["PHP_SELF"] . "?start=$prev_start&&DebugMode7=1701" . "&&RequestToken=$request_token_value";

    // initiaize vars

    $cur_row = 0;

    $toggle_row = 0;

    // previous link

    $prev_record = $_startRecord_index - $records_per_page;

    if ($prev_record >= 0) {

        $prev_link = $_SERVER ['PHP_SELF'] . "?start=$prev_record&&RequestToken=$request_token_value";
    } else {
        $prev_link = '';
    }
}

/*
 * #################################################################################################
 * Editing Report SQL to show only records of current page
 * ################################################################################################
 */
$result = array();
if (!$empty_Report) {

    if ($_print_option == 2) {
        $result = $all_records;
    } else {
        if (check_numeric_parameter($_startRecord_index) && check_numeric_parameter($records_per_page)) {

           /* if ($used_extension == "mysqli" || $used_extension == "mysql") {
                $sql [0] .= " limit ?,?";
                array_push($sql [1], intval($_startRecord_index), intval($records_per_page));
                $sql [2] .= "ii";
            } else {
                $_startRecord_index = intval($_startRecord_index);
                $records_per_page = intval($records_per_page);

                $sql [0] .= " limit $_startRecord_index,$records_per_page";
            }*/
            if ($layout == "mobile" && check_debug_mode() === 1) {
                $flush = true;
            } else {
                $flush = false;
            } 
             $result = array_slice($all_records, $_startRecord_index, $records_per_page); 
          //  $result = query($sql [0], "Layout: pager", $sql [1], $sql [2]);
        }
    }
    $cur_group_ar = array(); // the current group

    $last_group_ar = array(); // the newest grou by fields

    $actual_fields = array_diff($fields, $group_by); // actual columns which will be shown without group by fields

    $actual_columns_count = count($actual_fields); // number of columns to be shown

    $group_by_count = count($group_by);

    debug("is empty search parameters : " . $empty_search_parameters . PHP_EOL);
    debug("is empty report : " . $empty_Report . PHP_EOL);
    debug("number of All records : " . $nRecords . PHP_EOL);
    debug("count of results of current page : " . count($result) . PHP_EOL);
}
// render the view
// request token client side key in POST OR GET "RequestToken";
// save new request token in the session
$_SESSION [$request_token] = $request_token_value;
if (strtolower($layout) == "mobile")
    require_once "../shared/views/mobile_main_view.php";
else
    require_once "../shared/views/main_view.php";
?>