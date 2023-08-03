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


class CustomEngine extends Engine {

    private $ReportOptions;

    public function __construct($ReportOptions) {
        $this->_obj_report_path = new \SRE\Engine\ReportPath();
        $this->_reports_parent_directory_physical_path = $this->_obj_report_path->get_report_directory_physical_path();
        $this->ReportOptions = $ReportOptions;
        $this->set_file_name($this->ReportOptions->get_file_name());
        $this->_generated_report_directory_physical_path = $this->_reports_parent_directory_physical_path . $this->_file_name . "/";

        $this->set_connection_user($this->ReportOptions->get_MYSQL_Connection_username());
        $this->set_connection_pass($this->ReportOptions->get_MYSQL_Connection_password());
        $this->set_connection_db_name($this->ReportOptions->get_MYSQL_db_name());
        $this->set_connection_host($this->ReportOptions->get_MYSQL_hostname());
        $this->set_filters_grouping($this->ReportOptions->get_filters_grouping());
       
        $this->set_date_created(date("F j, Y, g:i a"));
        $this->set_language($this->ReportOptions->get_language());
        if (extension_loaded("pdo"))
            $this->set_db_extension("pdo");
        else
            $this->set_db_extension("mysqli");
        $this->set_fields($this->ReportOptions->get_fields());
        $this->set_fields2($this->ReportOptions->get_fields());
        $this->set_records_per_page($this->ReportOptions->get_records_per_page());      
        $this->set_style_name($this->ReportOptions->get_style_name());
        $this->set_title($this->ReportOptions->get_title());
        $this->set_header($this->ReportOptions->get_header());
        $this->set_footer($this->ReportOptions->get_footer());
       
        
        
        $this->set_group_by($this->ReportOptions->get_grouping());
        $this->set_sort_by($this->ReportOptions->get_sort_by());
     
            $this->set_table($this->ReportOptions->get_table());
            $this->set_tables_filters($this->ReportOptions->get_tables_filters());
       
            $this->set_chkSearch($this->ReportOptions->get_chkSearch());
       
    }

    //! @cond
    
}

?>