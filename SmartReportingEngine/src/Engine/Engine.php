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

abstract class Engine {

    protected $_file_name = '';
    private $_category = "";
    private $_date_created = '';
    private $_maintainance_email = '';
    private $_headers_output_escaping = "no";
    private $_default_page_size = "A3";
    private $_output_escaping = "no";
    private $_thumnail_max_width = '40';
    private $_thumnail_max_height = '50';
    private $_show_real_size_image = '';
    private $_show_realsize_in_popup = '1';
    private $_chkSearch = 'yes';
    private $_language = "en";
    private $_db_extension = 'pdo';
    private $_datasource = 'table';
    private $_table = array();
    private $_tables_filters = array();
    private $_fields = array();
    private $_fields2 = array();
    private $_records_per_page = "25";
    private $_style_name = 'default';
    private $_title = '';
    private $_header = '';
    private $_footer = '';
    private $_group_by = array();
    private $_sort_by = array();
    private $connection_db_user = "";
    private $connection_db_pass = "";
    private $connection_db_host = "";
    private $connection_db_name = "";
    private $is_template = "";
    private $filters_grouping = "and";
    private $save_template_name = "";
    protected $_obj_report_path;
    protected $_reports_parent_directory_physical_path;
    protected $_generated_report_directory_physical_path;

    protected function validate_request() {
        //request must be from admin
        //validate security rules
        //No fields
        if (count($this->_table) > 1) {
            Helper::log(ErrorMessages::$messages["en"]["61"], 61);
            throw new \Exception(ErrorMessages::$messages["en"]["61"], 61);
            return false;
        }

        if (SRE__Auto__Replace__Reports__ == 0) {


            if (file_exists($this->_generated_report_directory_physical_path)) {

                Helper::log(\SRE\Engine\ErrorMessages::$messages["en"]["29"], 29);
                throw new \Exception(ErrorMessages::$messages["en"]["29"], 29);
                return false;
            }
        }



        //case private report and no session validations required


        if ($this->connection_db_host == "" || $this->connection_db_name == "" || $this->connection_db_user == "") {
            Helper::log(ErrorMessages::$messages["en"]["19"], 19);
            throw new \Exception(ErrorMessages::$messages["en"]["19"], 19);
            return false;
        }

        if ($this->_file_name == "" || $this->_file_name == "rep") {
            Helper::log(ErrorMessages::$messages["en"]["20"], 20);
            throw new \Exception(ErrorMessages::$messages["en"]["20"], 20);
            return false;
        }

        if (SRE__REPORTS__DIR__ == "") {
            Helper::log(ErrorMessages::$messages["en"]["21"], 21);
            throw new \Exception(ErrorMessages::$messages["en"]["21"], 21);
            return false;
        }

        if (SRE__Engin__DIR__ == "") {
            Helper::log(ErrorMessages::$messages["en"]["22"], 22);
            throw new \Exception(ErrorMessages::$messages["en"]["22"], 22);
            return false;
        }

        if (SRE__CORE__DIR__ == "") {
            Helper::log(ErrorMessages::$messages["en"]["23"], 23);
            throw new \Exception(ErrorMessages::$messages["en"]["23"], 23);
            return false;
        }


        if ($this->_datasource == "table" && empty($this->_table)) {
            Helper::log(ErrorMessages::$messages["en"]["16"], 16);
            throw new \Exception(ErrorMessages::$messages["en"]["16"], 16);
            return false;
        } elseif ($this->_datasource == "sql") {
            Helper::log(ErrorMessages::$messages["en"]["61"], 61);
            throw new \Exception(ErrorMessages::$messages["en"]["61"], 61);
            return false;
        } elseif ($this->_datasource != "table") {
            Helper::log(ErrorMessages::$messages["en"]["18"], 18);
            throw new \Exception(ErrorMessages::$messages["en"]["18"], 18);
            return false;
        }



        if (empty($this->_fields)) {
            Helper::log(ErrorMessages::$messages["en"]["15"], 15);
            throw new \Exception(ErrorMessages::$messages["en"]["15"], 15);
            return false;
        }

        if ($this->_fields != array("*")) {
            if (!empty(array_diff(array_map("strtolower", $this->_group_by), array_map("strtolower",$this->_fields)))) {
                Helper::log(ErrorMessages::$messages["en"]["62"], 62);
            throw new \Exception(ErrorMessages::$messages["en"]["62"], 62);
            return false;
            }
           
            
         
            
        }



        if (!is_array($this->_group_by)) {
            Helper::log(ErrorMessages::$messages["en"]["26"], 26);
            throw new \Exception(ErrorMessages::$messages["en"]["26"], 26);
            return false;
        }



        if (!is_array($this->_sort_by)) {
            Helper::log(ErrorMessages::$messages["en"]["27"], 27);
            throw new \Exception(ErrorMessages::$messages["en"]["27"], 27);
            return false;
        }


        if (!Helper::validate_filters_array($this->_tables_filters)) {
            Helper::log(ErrorMessages::$messages["en"]["35"], 35);
            throw new \Exception(ErrorMessages::$messages["en"]["35"], 35);
            return false;
        }

        if (Helper::is_current_user_filter($this->_tables_filters)) {
            Helper::log(ErrorMessages::$messages["en"]["61"], 61);
            throw new \Exception(ErrorMessages::$messages["en"]["61"], 61);
            return false;
        }






        return true;
    }

    /*
     * RecursiveMkdir
     *
     * Creates the report folder
     *
     * @param (path) path of the report directory
     */

    protected function RecursiveMkdir($path) {
        if (!file_exists($path)) {
            $this->RecursiveMkdir(dirname($path));
            if (mkdir($path, 0755)) {
                return true;
            } else {
                Helper::log(ErrorMessages::$messages["en"]["1"], 1);
                throw new \Exception(ErrorMessages::$messages["en"]["1"], 1);
            }
        }
    }

    /**
     * create_report_structure()
     * This function copy necessary files to create the report
     *    * 
     */
    protected function create_report_structure() {

        $path = $this->_obj_report_path->get_core_directory_physical_path();


        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ('.' === $file)
                    continue;
                if ('..' === $file)
                    continue;


                $source = $path . $file;

                if ("report_index.php" === $file)
                    $target = $this->_generated_report_directory_physical_path . $this->_file_name . ".php";
                else
                    $target = $this->_generated_report_directory_physical_path . $file;



                copy($source, $target);
            }
            closedir($handle);
        }
        //creating the images directory and copy index to it
        if (!file_exists($this->_generated_report_directory_physical_path . "/images")) {
            mkdir($this->_generated_report_directory_physical_path . "/images", 0755, true);
        }
        copy($path . "index.html", $this->_generated_report_directory_physical_path . "/images/index.html");
    }

    /**
     * create_init_file
     * This function creates the init file which stores the connection to the database
     * 
     */
    protected function create_init_file() {

        $fp = fopen($this->_generated_report_directory_physical_path . "/init.php", "w+");
        if ($fp) {
            if (fwrite($fp, '<?php' . PHP_EOL)) {
                fwrite($fp, 'if (! defined("DIRECTACESS")) exit("No direct script access allowed"); ' . PHP_EOL);
                fwrite($fp, '$DB_HOST = "' . $this->connection_db_host . '";' . PHP_EOL);
                fwrite($fp, '$DB_USER = "' . $this->connection_db_user . '";' . PHP_EOL);
                fwrite($fp, '$DB_PASSWORD = "' . $this->connection_db_pass . '";' . PHP_EOL);
                fwrite($fp, '$DB_NAME = "' . $this->connection_db_name . '";' . PHP_EOL);
                fclose($fp);
            } else {
                Helper::log(ErrorMessages::$messages["en"]["2"], 2);
                throw new \Exception(ErrorMessages::$messages["en"]["2"], 2);
            }
        } else {
            Helper::log(ErrorMessages::$messages["en"]["3"], 3);
            throw new \Exception(ErrorMessages::$messages["en"]["3"], 3);
        }
    }

    /**
     * create_report_config
     * 
     * This function creates the config file of the report
     * 
     */
    protected function create_report_config() {

        $fp = fopen($this->_generated_report_directory_physical_path . "/config.php", "w+");

        if ($fp) {
            if (fwrite($fp, '<?php' . PHP_EOL)) {

                if ($this->_title == "") {
                    fwrite($fp, "//Untitled Report," . $this->_date_created . PHP_EOL);
                } else {
                    fwrite($fp, "//" . $this->_title . "," . $this->_date_created . PHP_EOL);
                }
                if (SRE_TEST_MODE)
                    fwrite($fp, '$testing_mode = ' . SRE_TEST_MODE . ';' . PHP_EOL);
                fwrite($fp, 'if (! defined("DIRECTACESS") && !$testing_mode) exit("No direct script access allowed"); ' . PHP_EOL);

                fwrite($fp, '$file_name = "' . $this->_file_name . '";' . PHP_EOL);
                $this->write_customization_settings($fp);
                $this->write_wizard_settings($fp);
                fclose($fp);
            } else {
                Helper::log(ErrorMessages::$messages["en"]["4"], 4);
                throw new \Exception(ErrorMessages::$messages["en"]["4"], 4);
            }
        } else {
            Helper::log(ErrorMessages::$messages["en"]["5"], 5);
            throw new \Exception(ErrorMessages::$messages["en"]["5"], 5);
        }
    }

    /**
     * write_customization_settings
     * This private function is called by the create_report_config() and write the customization settings
     * @param type $fp is a refrence to the config file
     */
    private function write_customization_settings($fp) {
        fwrite($fp, '//  customization settings' . PHP_EOL);
        fwrite($fp, '$template_title = "' . $this->save_template_name . '";' . PHP_EOL);
        fwrite($fp, '$category = "Dynamic Report Generated via Smart Report Engine";' . PHP_EOL);
        fwrite($fp, '$date_created = "' . $this->_date_created . '";' . PHP_EOL);
        fwrite($fp, '$maintainance_email = "";' . PHP_EOL);
        fwrite($fp, '$maintainance_mode = false;' . PHP_EOL);
        fwrite($fp, '$maintainance_log_path = "";' . PHP_EOL);
        fwrite($fp, '$images_path = "";' . PHP_EOL);
        fwrite($fp, '$headers_output_escaping = "' . $this->_headers_output_escaping . '";' . PHP_EOL);
        fwrite($fp, '$default_page_size = "' . $this->_default_page_size . '";' . PHP_EOL);
        fwrite($fp, '$output_escaping = "' . $this->_output_escaping . '";' . PHP_EOL);
        fwrite($fp, '$thumnail_max_width = "' . $this->_thumnail_max_width . '";' . PHP_EOL);
        fwrite($fp, '$thumnail_max_height = "' . $this->_thumnail_max_height . '";' . PHP_EOL);
        fwrite($fp, '$show_real_size_image = "' . $this->_show_real_size_image . '";' . PHP_EOL);
        fwrite($fp, '$show_realsize_in_popup = "' . $this->_show_realsize_in_popup . '";' . PHP_EOL);
        fwrite($fp, '$chkSearch = "' . $this->_chkSearch . '";' . PHP_EOL);
    }

    /**
     * process_filter_array
     * This function remove any redunduncies from the filter array, it's called only when the filter array is not empty
     */
    protected function process_filter_array() {
        $params = array();
        $sql = array();
        $types = array();
        foreach ($this->_tables_filters as $key => $filter) {

            $sql[] = $filter["sql"];
            $params[] = $filter["param"];
            $types[] = $filter["type"];
        }
    }

    /**
     * This function is called by the create_report_config() to write the apperance and wizard settings
     * @param type $fp : refrence to the config file
     */
    private function write_wizard_settings($fp) {
        fwrite($fp, '//  wizard settings' . PHP_EOL);
        fwrite($fp, '$language = "' . $this->_language . '";' . PHP_EOL);
        fwrite($fp, '$db_extension = "' . strtolower($this->_db_extension) . '";' . PHP_EOL);
        fwrite($fp, '$datasource = "table";' . PHP_EOL);


        fwrite($fp, '$table = ' . $this->serialize_array($this->_table) . ';' . PHP_EOL);
        fwrite($fp, '$tables_filters = ' . $this->serialize_array($this->_tables_filters) . ';' . PHP_EOL);
        fwrite($fp, '$filters_grouping = "' . $this->filters_grouping . '";' . PHP_EOL);

        fwrite($fp, '$fields = ' . $this->serialize_array($this->_fields) . ';' . PHP_EOL);
        fwrite($fp, '$fields2 = ' . $this->serialize_array($this->_fields) . ';' . PHP_EOL);
        fwrite($fp, '$group_by = ' . $this->serialize_array($this->_group_by) . ';' . PHP_EOL);
        fwrite($fp, '$sort_by = ' . $this->serialize_array($this->_sort_by) . ';' . PHP_EOL);
        // apperance and security
        fwrite($fp, '$records_per_page = "' . $this->_records_per_page . '";' . PHP_EOL);
        fwrite($fp, '$layout = "alignleft";' . PHP_EOL);
        fwrite($fp, '$style_name = "' . $this->_style_name . '";' . PHP_EOL);
        fwrite($fp, '$title = "' . $this->_title . '";' . PHP_EOL);
        fwrite($fp, '$header = "' . $this->_header . '";' . PHP_EOL);
        fwrite($fp, '$footer = "' . $this->_footer . '";' . PHP_EOL);
        fwrite($fp, '$access_mode = "PUBLIC_REPORT";' . PHP_EOL);
        fwrite($fp, '$is_mobile = "no";' . PHP_EOL);
        fwrite($fp, '$sub_totals_enabled  = "0";' . PHP_EOL);
    }

    /**
     * This function converts the cells array from the session formats to the report config formats
     * @param type $cells is the cells array as stored in the session
     * @return type the $cells array as it should be stored in the config
     */
    /**
     * This function is called if aggregation functions are used . it affects the fields, sort by and group by arrays
     */

    /**
     * This function create a way to write array variables in the cofig file of reports
     * @param teh array in a session variable
     * @return type string to be written in the config file
     */
    private function serialize_array($arr) {


        $str = "array(";
        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                //case two dimensional array where 2nd level is not associative
                //case second dimension where 2nd level is associative
                if (is_array($v)) {
                    $str .= PHP_EOL . " '" . $k . "' => " . $this->serialize_array($v) . ",";
                }
                //case one dimensional associative array
                else {
                    $str .= PHP_EOL . '"' . $k . '" => "' . str_replace('"', "'", $v) . '",';
                }
            }

            $str .= ")";
            $str = str_replace(",)", ")", $str);

            return $str;
        } elseif (empty($arr) || $arr === "") {
            return '""';
        } else {

            return $arr;
        }
    }

    /**
     * 
     * @return typecheck if an array is associative or not
     */
    private function is_associative_array() {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    /**
     * create_report
     * This is the template method that creates the report if called by an object of a subclass.
     * @ return string the report url, or false if failed
     */
    public function create_report() {


        if ($this->validate_request()) {

            $this->remove_table_part();

            $this->RecursiveMkdir($this->_generated_report_directory_physical_path); //done           
            $this->create_report_structure(); //done

            if ($this->_datasource == "table" && $this->_tables_filters != array()) {
                $this->process_filter_array();
            }
            $this->create_init_file(); //done
            $this->create_report_config(); //done
            //   return $this->get_report_url($this->_layout);

            return $this->_obj_report_path->get_report_url($this->_file_name);
        } else {
            return false;
        }
    }

    protected function set_file_name($_file_name) {
        $_file_name = "rep" . $_file_name;
        $_file_name = str_replace(" ", "", $_file_name);
        $this->_file_name = str_replace(".php", "", $_file_name);
    }

    protected function get_file_name() {
        return $this->_file_name;
    }

    protected function set_filters_grouping($_filters_grouping) {
        $this->filters_grouping = $_filters_grouping;
    }

    protected function set_date_created($_date_created) {
        $this->_date_created = $_date_created;
    }

    protected function set_chkSearch($_chkSearch) {
        $this->_chkSearch = $_chkSearch;
    }

    protected function set_language($_language) {
        $this->_language = $_language;
    }

    protected function set_db_extension($_db_extension) {
        $this->_db_extension = $_db_extension;
    }

    protected function set_table($_table) {
        $this->_table = $_table;
    }

    protected function set_tables_filters($_tables_filters) {
        $this->_tables_filters = $_tables_filters;
    }

    protected function set_fields($_fields) {
        $this->_fields = $_fields;
    }

    protected function set_fields2($_fields2) {
        $this->_fields2 = $_fields2;
    }

    protected function set_records_per_page($_records_per_page) {
        if ($_records_per_page < 1)
            $_records_per_page = 25;
        $this->_records_per_page = (int) $_records_per_page;
    }

    protected function set_style_name($_style_name) {
        $all_styles = array(
            "blue",
            "grey",
            "teal"
        );
        if (in_array($_style_name, $all_styles)) {
            $this->_style_name = $_style_name;
        } else {
            $this->_style_name = "default";
        }
    }

    protected function set_title($_title) {
        $this->_title = $_title;
    }

    protected function set_header($_header) {
        $this->_header = $_header;
    }

    protected function set_footer($_footer) {
        $this->_footer = $_footer;
    }

    protected function set_group_by($_group_by) {
        $this->_group_by = $_group_by;
    }

    protected function set_sort_by($_sort_by) {
        $this->_sort_by = $_sort_by;
    }

    protected function set_connection_user($_db_user) {
        $this->connection_db_user = $_db_user;
    }

    protected function set_connection_pass($_db_pass) {
        $this->connection_db_pass = $_db_pass;
    }

    protected function set_connection_db_name($_db_name) {
        $this->connection_db_name = $_db_name;
    }

    protected function set_connection_host($_db_host) {
        $this->connection_db_host = $_db_host;
    }

    private function is_multi_dimension_array($array) {
        foreach ($array as $v) {
            if (is_array($v))
                return true;
        }
        return false;
    }

    private function remove_table_part() {
        $this->_fields = !empty($this->_fields) ? Helper::remove_table_part($this->_fields) : array();
        $this->_group_by = !empty($this->_group_by) ? Helper::remove_table_part($this->_group_by) : array();
        $this->_sort_by = !empty($this->_sort_by) ? Helper::remove_table_part($this->_sort_by) : array();
    }

}
