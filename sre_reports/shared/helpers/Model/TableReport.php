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

class TableReport extends Report {

    protected $table;
    protected $tables_filters;
    protected $search;
    protected $filters_grouping;

    public function __construct($table, $fields, $relationships = array(), $tables_filter = array(), $search_options = NULL, $filters_grouping = "and") {

        $this->set_search_options($search_options);
        $this->set_filters($tables_filter);
        $this->set_table($table);
        $this->set_fields($fields);
        $this->filters_grouping = (strtolower($filters_grouping) == "or") ? "or" : "and";
    }

    public function set_table($table) {
        if (is_array($table))
            $this->table = $table;
        else
            $this->table = array();
    }

    public function set_search_options($search) {
        if (!is_null($search) && get_class($search) == "Search_options")
            $this->search = $search;
        else
            $this->search = Null;
    }

    public function set_filters($filters) {
        if (is_array($filters)) {
            $this->tables_filters = $filters;
        } else {
            $this->tables_filters = array();
        }
    }

    public function Prepare_Sql() {

        // global $fields, $report_key, $table, $sort_by, $group_by, $affected_column, $groupby_column, $relationships, $tables_filters;
        //filters parameters and parametres types
        $filter_params = array();
        $filter_param_types = "";
        //All (filters + search) parameters and parameters types .  
        $parameters = array();
        $types = "";

        $sql = "select ";
        $c = 0;
        foreach ($this->fields as $f) {
            $sql .= "`$f`";
            if ($c < (count($this->fields) - 1))
                $sql .= ",";
            $c ++;
        }

        // add tables names
        $sql .= " from ";
        foreach ($this->table as $key => $val)
            $sql .= "`$val`,";
        $sql = substr($sql, 0, strlen($sql) - 1);
        if (count($this->tables_filters) > 0) {
            $sql .= " where ";
            $filter_counter = 0;
            foreach ($this->tables_filters as $filter) {

                if ($filter_counter == 0)
                    $sql .= "(";
                $filter_counter++;

                foreach ($filter as $key => $value) {
                    if ($key == "sql") {
                        $is_filter_contains_parameter = (strstr($value, "?")) ? true : false;
                        $newvalue = str_replace("\\", " ", $value);
                        $newvalue = str_replace("` <->", "", $newvalue);
                        $newvalue = str_replace("<->", " ", $newvalue);
                        $newvalue = str_replace("\\", "", $newvalue);
                        if ($this->filters_grouping == "or")
                            $sql .= " ( $newvalue )" . "  or";
                        else
                            $sql .= " ( $newvalue )" . " and";
                    }
                    if ($is_filter_contains_parameter && $key == "param" && !is_array($value)) {

                        $filter_params [] = $value;
                    } elseif ($is_filter_contains_parameter && $key == "param" && is_array($value)) {
                        $filter_params = array_merge($filter_params, $value);
                    }
                    if ($is_filter_contains_parameter && $key == "type")
                        $filter_param_types .= $value;
                }
            }

            $sql = substr($sql, 0, strlen($sql) - 3);
            if ($filter_counter != 0) 
                $sql .= " )";
            $parameters = $filter_params;
            $types = $filter_param_types;
        }



        if (!is_null($this->search)) {
            if ($this->search->search_type == "quick") {

                $search_array = $this->search->prepare_ordinary_search_statment($this->table, $this->fields);
            } else if ($this->search->search_type == "advanced") {
                $search_array = $this->search->prepare_advanced_search_statment();
            }
        }

        if (isset($search_array) && !empty($search_array)) {
            if (is_array($search_array)) {
                //case filter and search
                $search_sql = $search_array ["sql"];
                $parameters = array_merge($parameters, $search_array ["parameters"]);
                $types = $types . $search_array ["types"];
            } else {

                $search_sql = $search_array;
            }
            if (count($this->tables_filters) > 0) {
                $sql .= " and " . $search_sql;
            } else {
                $sql .= " where " . $search_sql;
            }
        }

        // group by in case of statistics


        if (count($this->sort_by) > 0 || count($this->group_by) > 0)
            $sql .= " order by ";

        $group_by_sort = array();
        foreach ($this->group_by as $g) {
            $flag = 0;
            $i = 0;

            foreach ($this->sort_by as $arr) {
                if ($g == $arr [0]) {
                    $group_by_sort [] = array(
                        $arr [0],
                        $arr [1]
                    );
                    $flag = 1;
                    $this->sort_by [$i] [0] = '~xxx~';
                    break;
                }
                $i ++;
            }
            if ($flag == 0) {
                $group_by_sort [] = array(
                    $g,
                    '0'
                );
            }
        }

        foreach ($this->sort_by as $arr_sort) {
            if ($arr_sort [0] != '~xxx~') {
                $group_by_sort [] = array(
                    $arr_sort [0],
                    $arr_sort [1]
                );
            }
        }
        $i = 0;

        foreach ($group_by_sort as $arr) {

            $sql .= "`" . $arr [0] . "`";


            if ($arr [1] == '1')
                $sql .= "desc";
            if ($i < (count($group_by_sort) - 1)) {
                $sql .= ",";
            }
            $i ++;
        }

        $new_fields = array();
        $new_sort_by = array();
        $new_group_by = array();

        // fields
        foreach ($this->fields as $key => $val) {
            // check if it's function field
            $isFunction = 0;

            if (strstr($val, ".")) {
                list ( $t, $f ) = explode(".", $val);
            } else {
                $f = $val;
            }


            $new_fields [] = $f;
        }


        // this->sort_by

        foreach ($this->sort_by as $key => $arr) {
            if (strstr($arr [0], ".")) {
                $temp = explode(".", $arr [0]);
                $t = $temp [0];
                $f = $temp [1];
            } else {
                $t = $arr [0];
                $f = "";
            }

            $new_sort_by [] = array(
                $f,
                $arr [1]
            );
        }


        // this->affected_column
        foreach ($this->group_by as $key => $val) {
            if (strstr($val, ".")) {
                list ( $t, $f ) = explode(".", $val);
            } else {
                $f = $val;
            }

            $new_group_by [] = $f;
        }



        $arr_sql [0] = $sql;
        $arr_sql [1] = $parameters;
        $arr_sql [2] = $types;
        return $arr_sql;
    }

}
?>


