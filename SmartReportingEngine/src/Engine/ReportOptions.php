<?php

/**
 * Smart Report Engine
 * Version Community Edition 
 * Author : Webuccino 
 * All copyrights are preserved to Webuccino
 * URL : https://mysqlreports.com/
 *
 */

namespace SRE\Engine;

/**
 * Includes all the necessary options from the community edition to support the report to be created..
 *
 *  
 */
class ReportOptions {

    private $file_name = '';
    private $chkSearch = 'yes';
    private $language = SRE__DEFAULT_REPORT_LANGUAGE__;
    private $datasource = 'table';
    private $table = array();
    private $fields = array();
    private $records_per_page = '10';
    private $layout = "alignleft";
    private $style_name = 'default';
    private $title = '';
    private $header = '';
    private $footer = '';
    private $group_by = array();
    private $sort_by = array();
    private $user = SRE__DEFAULT__USER__;
    private $pass = SRE__DEFAULT__PASS__;
    private $db = SRE__DEFAULT__DB__;
    private $host = SRE__DEFAULT__HOST__;
    private $filter;
    private $filters_grouping = "and";

    public function __construct($access_mode = SRE_PUBLIC_REPORT, $data_source = SRE_Table, $report_name = "") {


        $this->filter = new Filter();
        if ($report_name === "")
            $report_name = time() . rand(0, 1000000);
        $this->file_name = $report_name;
        $this->datasource = "table";
    }

    /*
     * get file name
     */

    public function get_file_name() {
        return $this->file_name;
    }

    public function get_MYSQL_Connection_username() {
        return $this->user;
    }

    public function set_MYSQL_Connection_userName($user = SRE__DEFAULT__USER__) {
        $this->user = $user;
        return $this;
    }

    public function get_MYSQL_Connection_password() {
        return $this->pass;
    }

    public function set_MYSQL_Connection_password($password = SRE__DEFAULT__PASS__) {
        $this->pass = $password;
        return $this;
    }

    public function get_MYSQL_db_name() {
        return $this->db;
    }

    public function set_MYSQL_db_name($db = SRE__DEFAULT__DB__) {
        $this->db = $db;
        return $this;
    }

    public function get_MYSQL_hostname() {
        return $this->host;
    }

    public function set_MYSQL_hostname($host = SRE__DEFAULT__HOST__) {
        $this->host = $host;
        return $this;
    }

    public function get_chkSearch() {
        return $this->chkSearch;
    }

    public function set_chkSearch($allow = true) {
        if ($allow == false || $allow == "false" || $allow == 0) {
            $this->chkSearch = "no";
        } else {
            $this->chkSearch = "yes";
        }
        return $this;
    }

    public function set_filters_grouping($filters_grouping = "or") {
        $this->filters_grouping = $filters_grouping;
        return $this;
    }

    public function get_filters_grouping() {
        return $this->filters_grouping;
    }

    public function get_language() {
        return $this->language;
    }

    public function set_language($language) {
        $language = strtolower($language);
        if($language == "french" || $language == "france" )$language = "fr";
        if($language == "arabic") $language = "ar";
        if($language == "german" || $language == "german" || $language== "dutch") $language  = "de";
        if($language == "spanish" || $language == "spain" || $language == "espaniol"  ) $language = "es";
        if($language == "italian" || $language == "italy") $language =  "it";
        
        if (in_array(strtolower($language), json_decode(SRE__ALLOWED_REPORT_LANGUAGES__))) {
            $this->language = strtolower($language);
        } else {
            $this->language = SRE__DEFAULT_REPORT_LANGUAGE__;
        }
        return $this;
    }

    public function get_datasource() {
        return "table";
    }

    public function get_table() {
        return $this->table;
    }

    public function select_tables($selected_tables) {

        if (is_array($selected_tables)) {
            $trimmed_selected_table = array_map("trim", $selected_tables);
            $this->table = array($trimmed_selected_table[0]);
        } else {
            $this->table = array(trim($selected_tables));
        }
        return $this;
    }

    public function get_tables_filters() {
        return $this->filter->get_tables_filters();
    }

    public function filter_between( $column, $first_param, $second_param, $parameters_type = SRE_NUMBER) {
        $this->filter->between($column, $first_param, $second_param, $parameters_type);
        return $this;
    }

    public function filter_more($column, $param, $is_or_equal = false, $parameters_type = SRE_NUMBER) {
        $this->filter->more($column, $param, $is_or_equal, $parameters_type);
        return $this;
    }

    public function filter_less($column, $param, $is_or_equal = false, $parameters_type = SRE_NUMBER) {
        $this->filter->less($column, $param, $is_or_equal, $parameters_type);
        return $this;
    }

    public function filter_equal($column, $param, $parameters_type = SRE_NUMBER) {
        $this->filter->equal( $column, $param, $parameters_type);
        return $this;
    }

    public function filter_not_equal($column, $param, $parameters_type = SRE_NUMBER) {
        $this->filter->not_equal($column, $param, $parameters_type);
        return $this;
    }

    public function filter_like( $column, $param) {
        $this->filter->like($column, $param);
        return $this;
    }

    public function filter_not_like($column, $param) {
        $this->filter->not_like( $column, $param);
        return $this;
    }

    public function filter_not_null( $column) {
        $this->filter->is_not_null($column);
        return $this;
    }

    public function filter_is_null( $column) {
        $this->filter->is_null( $column);
        return $this;
    }

    public function get_fields() {
        return $this->fields;
    }

    public function select_fields($selected_fields) {
        $trimmed_selected_fields = array_map("trim", $selected_fields);
        $this->fields = $trimmed_selected_fields;
        return $this;
    }

    public function select_all_fields() {
        $this->fields = array("*");
        return $this;
    }

    public function get_records_per_page() {
        return $this->records_per_page;
    }

    public function set_records_per_page($records_number = 25) {
        $this->records_per_page = $records_number;
        return $this;
    }

    public function get_style_name() {
        return $this->style_name;
    }

    public function set_style_name($style_name) {
        $this->style_name = strtolower($style_name);
        return $this;
    }

    public function get_title() {
        return $this->title;
    }

    public function set_title($title) {
        $this->title = $title;
        return $this;
    }

    public function get_header() {
        return $this->header;
    }

    public function set_header($header) {
        $this->header = $header;
        return $this;
    }

    public function get_footer() {
        return $this->footer;
    }

    public function set_footer($footer) {
        $this->footer = $footer;
        return $this;
    }

    public function get_grouping() {
        return $this->group_by;
    }

    public function set_grouping($group_by_array) {
        if (is_array($group_by_array)) {
            $trimmed_grouped_by_array = array_map("trim", $group_by_array);
            $this->group_by = $trimmed_grouped_by_array;
        } else {
            $this->group_by = array(trim($group_by_array));
        }
        return $this;
    }

    public function get_sort_by() {
        return $this->sort_by;
    }

    public function sort_by($column, $order = 0) {
        if (!empty($column) && is_string($column)) {
            if ($order == 1 || $order == "1" || strtolower($order) == "dsc" || strtolower($order) == "desc" || strtolower($order) == "descending")
                $temp = array($column, "1");
            else
                $temp = array($column, "0");

            if (empty($this->sort_by)) {
                $this->sort_by[0] = $temp;
            } else {
                $key = array_search($temp, $this->sort_by);
                if ($kay) {
                    $this->sort_by[$key] = $temp;
                } else {
                    $this->sort_by[] = $temp;
                }
            }
        }
        return $this;
    }

}
