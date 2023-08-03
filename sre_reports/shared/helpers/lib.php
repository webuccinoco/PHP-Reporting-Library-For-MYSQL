<?php

/**
 * Smart Report Engine
 * Community Edition
 * Author : Webuccino 
 * All copyrights are preserved to Webuccino
 * URL : https://mysqlreports.com/
 *
 */
error_reporting(0);
if (!defined("DIRECTACESS"))
    exit("No direct script access allowed");

/*
 * #################################################################################################
 * Filtering the Super Global Arrays and get the expected members after Sanitization.
 * ################################################################################################
 */
$_CLEANED = array();
if ($datasource == 'table') {
    $_CLEANED = array_merge($_CLEANED, remove_unexpected_superglobals($_POST, array(
        "SearchField",
        "keyWord",
        "keyWord2",
        "btnSearch",
        "HdSearchval",
        "txtordnarySearch",
        "btnordnarySearch",
        "btnShowAll",
        "btnShowAll2"
    )));
}
$_CLEANED = array_merge($_CLEANED, remove_unexpected_superglobals($_GET, array(
    "start",
    "print",
    'export',
    'detail',
    'setStyle',
    'cp'
        )));


//exit();
$_GET = array();
$_POST = array();
$_REQUEST = array();
$_ENV = array();
$_FILES = array();
$_COOKIE = array();
debug("\n   ###The Post and GET array  after lib sanitization \n");


/*
 * #################################################################################################
 * Changing the layout (if needed)
 * ################################################################################################
 */

$all_layouts = array(
    "AlignLeft"   
);



/*
 * #################################################################################################
 * Changing the style (if needed)
 * ################################################################################################
 */
$all_styles = array(
    "blue",
    "grey",
    "default"
);
if (isset($_SESSION ["change_style_srm7"])) {
    $posted_style_key = array_search(strtolower($_SESSION ["change_style_srm7"]), $all_styles);
    $keys = array(
        0,
        1,
        2
    );
    if (in_array($posted_style_key, $keys)) {
        $style_name = $all_styles [$posted_style_key];
    }
}

/*
 * #################################################################################################
 * Intializing input Variables
 * ################################################################################################
 */
$possible_attack = false;
$empty_search_parameters = false;
if (!isset($allow_only_admin))
    $allow_only_admin = "no";
$flush = false; // flag to send the logging data , it turns to true when the log message is complete
$used_extension = "";
$Search_Type = "";
$_print_option = 0;
if (!file_exists("../shared/views/layout_views/$layout.php")) {
    $layout = "AlignLeft";
    $style_name = "blue";
}
if (!file_exists("../shared/styles/$style_name.css")) {
    if (strtolower($layout) != "mobile")
        $style_name = "blue";
    else
        $style_name = "mobile";
}

if (isset($_CLEANED ['print']) && isset($_CLEANED["RequestToken"])) {
    if ($_CLEANED ['print'] == 1 || $_CLEANED ['print'] == 2) {
        $_print_option = (int) $_CLEANED ['print'];
    } else {
        $_print_option = 0;
        $_CLEANED ['print'] = 0;
        unset($_CLEANED ['print']);
    }
} else {
    $_print_option = 0;
    $_CLEANED ['print'] = 0;
    unset($_CLEANED ['print']);
}



if (!isset($table) || empty($table))
    $table = array();
else
    $table = array_map('strtolower', $table);

if (count($fields) == 1 && $fields[0] == "*") {
    $fields = get_all_fields();
} elseif (!isset($fields) || empty($fields)) {
    $fields = array();
}
//Adjusting arrays case
$fields = array_map('strtolower', $fields);
$fields2 = $fields;


if (!isset($tables_filters) || empty($tables_filters))
    $tables_filters = array();


    $relationships = array();
if (!isset($table) || empty($table))
    $table = array();
if (!isset($fields) || empty($fields))
    $fields = array();
if (!isset($fields2) || empty($fields2))
    $fields2 = array();
if (!isset($group_by) || empty($group_by))
    $group_by = array();
else {
    $group_by = array_map("strtolower", $group_by);
}
if (!isset($sort_by) || empty($sort_by))
    $sort_by = array();

    $conditional_formating = array();

if (!isset($records_per_page) || empty($records_per_page))
    $records_per_page = 10;
$options = array(
    "Yes",
    "No",
    ""
);
if (!isset($headers_output_escaping) || !in_array($headers_output_escaping, $options))
    $headers_output_escaping = "";
if (!isset($output_escaping) || !in_array($output_escaping, $options))
    $output_escaping = "";

if (!isset($chkSearch) && $datasource == "table")
    $chkSearch = 'yes';

    $labels = convert_to_associative_array($fields);

    $cells = convert_to_associative_array($fields,"value");




if (isset($header)) {
    $header = $headers_output_escaping == "Yes" ? escape($header) : $header;
} else {
    $header = "";
}

if (isset($footer)) {
    $footer = $headers_output_escaping == "Yes" ? escape($footer) : $footer;
} else {
    $footer = "";
}


    $sub_totals_enabled = "0";
    $sub_totals = array();

if (!isset($filters_grouping) || $filters_grouping == "null")
    $filters_grouping = "and";


foreach ($group_by as $key => $val) {
    if (!in_array($val, $cells) || !in_array($val, $fields))
        $group_by[$key] = strtolower($val);
}

debug("The labels Array" . PHP_EOL);
log_array($labels);

debug("\n ## Data Source : $datasource\n ");
debug("\n ## Table(s) : ");
debug(implode(PHP_EOL, $table) . PHP_EOL);
debug("\n ## Filter(s) : ");
log_array($tables_filters);
debug("\n ## Relations(s) : \n");
debug(implode(PHP_EOL, $relationships));
debug("\n ## Fields(s) : \n");
debug(implode(PHP_EOL, $fields));
debug("\n ## Extension : $db_extension ");
debug("\n ## Search : $chkSearch ");
log_array($cells);
log_array($conditional_formating);
/*
 * #################################################################################################
 * Process the search options and create the search object to pass it to the table report .
 * ################################################################################################
 */

function prepare_search_statment() {
    global $possible_attack, $empty_search_parameters, $Enter_your_search_lang, $fields, $table, $Search_Type, $datasource, $chkSearch, $_CLEANED, $report_filtering_key, $request_token;
    // Add any special characters that you want to allow in search terms in this array
    debug("Entered prepare search statment");
    $allowed = array(
        "#",
        "$",
        "@",
        "_",
        ".",
        ":"
    );
    if (strtolower($chkSearch) != "yes") {
        debug("************prepare_search_statment returned Null *************");
        return Null;
    }

    if (isset($_SESSION [$report_filtering_key]) === false) {
        $_SESSION [$report_filtering_key] = array(
            "type" => ""
        );
    }
    $arr = $_SESSION [$report_filtering_key];

    // case show all order sent through post array
    // ################################################
    if (isset($_CLEANED ['btnShowAll2']) || isset($_CLEANED ['btnShowAll'])) {
        debug("************Show all  *************");
        $Search_Type = "Show All ";
        $_SESSION [$report_filtering_key] = array(
            "type" => ""
        );

        return Null;
    }

    // Case Fresh quick search (case quick search in the post array)
    // ################################################################
    elseif (isset($_CLEANED ['btnordnarySearch']) && isset($_CLEANED ['txtordnarySearch'])) {
        debug("Ordinary search");
        //validating request token : 
        //fresh search attemp
        if (!isset($_CLEANED["RequestToken"]) || $_CLEANED["RequestToken"] != $_SESSION[$request_token]) {
            debug("************Request token is either is not exist in the search request or it's incorrect*************");
            return Null;
        }
        // Validating the posted search keyword
        // allowable search special characters

        if (!check_no_specials($_CLEANED ['txtordnarySearch'], $allowed)) {
            debug("************Ordinary  Search but not complete cecause of a possible attack . The search term is " . $_CLEANED ['txtordnarySearch'] . " *************");
            $Search_Type = "Fresh Ordinary search but not complete cecause of a possible attack . The search term is " . $_CLEANED ['txtordnarySearch'];
            $possible_attack = true;

            $_SESSION [$report_filtering_key] = array(
                "type" => ""
            );

            return Null;
        } elseif ($_CLEANED ['txtordnarySearch'] === "" || $_CLEANED ['txtordnarySearch'] === $Enter_your_search_lang) {
            debug("************Ordinary  Search but empty because the search keyword is the either empty or equal to the search tip . The search term is " . $_CLEANED ['txtordnarySearch'] . " *************");
            $empty_search_parameters = true;
            $_SESSION [$report_filtering_key] = array(
                "type" => ""
            );

            return Null;
        } else {
            debug("************Ordinary  Search*************" . PHP_EOL);
            $Search_Type = "Fresh Ordinary search for " . $_CLEANED ['txtordnarySearch'];
            $_SESSION [$report_filtering_key] = array(
                "type" => "quick",
                "keyword" => $_CLEANED ['txtordnarySearch']
            );

            $quick_search = new Search_options("quick", $_CLEANED ['txtordnarySearch']);
            return $quick_search;
        }
    } // Case Fresh advanced search (case advanced search in the post array)
    // ###################################################################
    elseif (isset($_CLEANED ['btnSearch']) && isset($_CLEANED ["SearchField"]) && isset($_CLEANED ["HdSearchval"])) {
        // validate entered parameters
        debug("************ Advanced Search *************" . PHP_EOL);
        //check request token
        //fresh search attemp
        if (!isset($_CLEANED["RequestToken"]) || $_CLEANED["RequestToken"] != $_SESSION[$request_token]) {
            debug("************Request token is either is not exist in the search request or it's incorrect*************");
            return Null;
        }

        $dataTypes = array(
            "int",
            "string",
            "bool",
            "date"
        );

        $_keyword = isset($_CLEANED ["keyWord"]) ? $_CLEANED ["keyWord"] : "";
        $_keyWord2 = isset($_CLEANED ["keyWord2"]) ? $_CLEANED ["keyWord2"] : "";
        $_posted_field = isset($_CLEANED ["SearchField"]) ? (int) $_CLEANED ["SearchField"] : "";
        $_posted_dataType = isset($_CLEANED ["HdSearchval"]) ? $_CLEANED ["HdSearchval"] : "";
        $_posted_field = str_replace("`", "", $_posted_field);
        // the posted field restored from the $fields array
        logging("posted_field, $_posted_field  ");
        if (is_numeric($_posted_field) && $_posted_field >= 1 && $_posted_field <= count($fields)) {
            $_posted_field = $_posted_field - 1;
            $_search_field = $fields [$_posted_field];
        } else {
            $_search_field = "";
        }

        $_atual_datatype = array_search($_posted_dataType, $dataTypes) !== false ? $dataTypes [array_search($_posted_dataType, $dataTypes)] : "";
        debug("Search Parameters : " . PHP_EOL . " keyword : $_keyword " . PHP_EOL . " Second KeyWord : $_keyWord2 " . PHP_EOL . " Data Type : $_atual_datatype " . PHP_EOL . "search Field: $_search_field");

        // validating advanced search parameters

        if (!check_no_specials($_keyword, $allowed) || // no harmful special characters in 1st keyword
                !check_no_specials($_keyWord2, $allowed) || // no harmful special characters in 2nd keyword
                !check_search_keywords($_atual_datatype, $_keyword, $_keyWord2) || // validating keyword for empty and datat type
                !in_array($_search_field, $fields) || // search fiel is as expected
                !in_array($_atual_datatype, $dataTypes) || // datatype as expected
                $_atual_datatype == "" || $_search_field == "") {
            // case values falls outside expected loockups or contains unallowed characters
            $possible_attack = true;
            logging("keyword".$_keyword." ,second keyword $_keyWord2, search field $_search_field, actual data type, $_atual_datatype");
            $Search_Type = "Fresh advanced  search but not complete coz of a possible attack teh search term is " . $_CLEANED ["keyWord"];
            $_SESSION [$report_filtering_key] = array(
                "type" => ""
            );
            return Null;
        } else {
            // normal case when every thing is validated oki

            $Search_Type = "Fresh advanced  search  search term is " . $_CLEANED ["keyWord"];
            $_SESSION [$report_filtering_key] = array(
                "type" => "advanced",
                "keyword" => $_keyword,
                "keyword2" => $_keyWord2,
                "column" => $_search_field,
                "datatype" => $_atual_datatype
            );
           
            $advanced_search = new Search_options("advanced", $_keyword, $_atual_datatype, $_search_field, $_keyWord2);
            return $advanced_search;
        }
    }

    // case a quick search saved in the session
    // ##############################################
    elseif (isset($arr ["type"]) && $arr ["type"] === "quick") {
        $Search_Type = "Saved quick search  " . $arr ['keyword'];
        $quick_search = new Search_options("quick", clean_input($arr ["keyword"]));
        return $quick_search;
    }

    // case advanced search saved in the session
    // ##############################################
    elseif (isset($arr ["type"]) && $arr ["type"] === "advanced") {
        $Search_Type = "Saved advanced  search the search term is " . $arr ["keyword"];
        $advanced_search = new Search_options("advanced", $arr ["keyword"], $arr ["datatype"], $arr ["column"], $arr ["keyword2"]);
        return $advanced_search;
    } else {
        $Search_Type = "Case No search object";
        return Null;
    }
}

/*
 * #################################################################################################
 * Create The SQL statment creating the report if it is based on table datasource .
 * ################################################################################################
 */

function Prepare_TSql() {
    global $fields, $table, $sort_by, $group_by, $affected_column, $groupby_column, $relationships, $tables_filters, $Search_Type, $filters_grouping;
    debug("*****Table Report started***********");
    $funcations_arr = array(
        "sum(",
        "avg(",
        "min(",
        "max(",
        "count("
    );

    $search = prepare_search_statment();
    debug("*****Search Prefrence: $Search_Type***********");
    debug("***** Table Report started***********");
    if (isset($filters_grouping) && strtolower($filters_grouping) === "or")
        $report = new TableReport($table, $fields, $relationships, $tables_filters, $search, "or");
    else
        $report = new TableReport($table, $fields, $relationships, $tables_filters, $search, "and");

    $report->set_group_by($group_by);
 
    $report->set_sort_by($sort_by);

    return $report->Prepare_Sql();
}

/*
 * #################################################################################################
 * Create The SQL statment creating the report if it is based on SQL datasource .
 * ################################################################################################
 */

function Prepare_QSql() {
    global $sql, $group_by, $sort_by;
    debug("*****Query Report started***********");
    $report = new QueryReport($sql);
    $report->set_group_by($group_by);
    $report->set_sort_by($sort_by);
    return $report->Prepare_Sql();
}

/*
 * #################################################################################################
 * Expoerting validtion.
 * ################################################################################################
 */

function validate_export_parameters($limits, $start, $duration, $records_count, $is_sub_totals = false, $is_pdf = false) {
    global $used_extension, $all_records, $empty_Report, $empty_search_parameters, $_CLEANED, $flush;

    $flush = true;
    if (check_numeric_parameter($start, $records_count)) {
        $validated_start = (int) $start;
    } else {
        $validated_start = 0;
    }

    if (is_bool($limits)) {
        $validated_limits = $limits;
    } else {
        $validated_limits = true;
    }

    if (is_numeric($duration)) {
        $validated_duration = (int) $duration;
    } else {
        $validated_duration = 10;
    }
    if (isset($all_records) && (!$is_sub_totals || !$is_pdf ) && !$limits) {
        return $all_records;
    } elseif (isset($all_records) && (!$is_sub_totals || !$is_pdf ) && $limits) {
        return array_slice($all_records, (int) $validated_start, (int) $validated_duration);
    }  else {
        // no data to export
        return array();
    }
}

function get_column_part($compond_name) {
    if (strstr($compond_name, ".")) {
        $tmp = explode(".", $compond_name);
        return $tmp[1];
    } else {
        return $compond_name;
    }
}

/*
 * #################################################################################################
 * Returning the posted value in the search to be remembered (appear in the search box in the response) .
 * ################################################################################################
 */

function get_default_value($var) {
    global $_CLEANED;

    //case reloading the page so remeber the previous value is needed
    $default = isset($_CLEANED [$var]) ? $_CLEANED [$var] : "";



    return $default;
}

 


?>