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

class ErrorMessages {

    public static $messages = array(
        "en" => array(
            "1" => "Could not create the report directory. Permission denied! Please make sure to give 755 permissions to the 'sre_reports' directory",
            "2" => "Could not write in the 'init.php' configuration file of the generated report. Permission denied! Please make sure to give 755 permissions to the 'sre_reports' directory ",
            "3" => "Could not create the 'init.php' configuration file of the generated report, permission denied! Please make sure to give 755 permissions to the 'init.php' directory ",
            "4" => 'Could not  write in the configuration file of the generated report, permission denied! Please make sure to give 755 permissions to the sre_reports directory ',
            "5" => 'Could not  create the configuration file of the generated report, permission denied! Please make sure to give 755 permissions to the sre_reports directory ',
            "15" => "No columns have been chosen for this report.",
            "16" => "No tables were selected for this report.",
            "17" => "The datasource is set to SQL yet a SQL query is not found!",
            "18" => "The datasource is not recognized!",
            "19" => "Required MYSQL connection parameters are not provided. Please update the connection parameters at 'sre_config/config.php'.",
            "20" => "The report name must not be left empty.",
            "21" => "The reports directory must have a name. Please update this in the config file.",
            "22" => "The constant 'SRE__Engin__DIR__' in the '/sre_config/config.php' file must have a value and cannot be left empty.",
            "23" => "The constant 'SRE__CORE__DIR__' in the '/sre_config/config.php' file must have a value and cannot be left empty.",
            "25" => "Invalid sql query! Please write a valid select sql query. Don't use 'order by' , 'group by' or double qoutes  in your query. Alternativly, please use the 'set_order_by' , 'set_group_by' methods and single qoutes",
            "26" => "The set_grouping function expects a single dimensional  array of the column(s)!",
            "27" => "Error in 'sort by' array",
            "28" => "Error in labels!",
            "29" => "Report with same file name already exists! If you like to auto replace reports with same name please edit this setting in '/sre_config/config.php' file. ",
            "31" => "Error in filters array  keys ",
            "32" => "Error in filters parameter types ",
            "33" => "Error in filters parameters ",
            "34" => "Error in filters array ",
            "35" => "Error in filters validation ",
            "36" => "Data Filters are available only with the 'table' data source!",
            "37" => "The report to be created is based on multiple tables, so the column name parameter which is passed to  the 'label' function should be in the form of 'TablesName.ColumnName' ",
            "38" => "The report to be created  is based on multiple tables, so the column name parameter passed to  any 'format_column' function should be in the form of 'TablesName.ColumnName'",
            "39" => "The report to be created is based on multiple tables, so each column name parameter  passed to  the 'select_fields' function should be in the form of 'TablesName.ColumnName' ",
            "40" => "The report to be created is based on multiple tables, so the column name parameter which is passed to  the 'set_grouping' function should be in the form of 'TablesName.ColumnName' ",
            "41" => "The report to be created is based on multiple tables, so the column name parameter which is passed to  the 'sort_by' function should be in the form of 'TablesName.ColumnName' ",
            "42" => "The report to be created is based on multiple tables, yet there are no relationships defined! ",
            "43" => "Data type is not supported",
            "44" => "The 'security_init()' should be called before any security_check functions.",
            "50" => "The access mode of the report is private, yet no security session validation rules were added!",
            "51" => "The access mode of report is public, yet some session validation rules are required!",
            "52" => "The access mode of report is private yet the login page is not set!",
            "53" => "The access mode of report is private yet the logout page is not set!",
            "54" => " Incorrect session key format passed to the security method : ",
            "55" => "The access mode of report is public, yet  the 'security_init()' is called!",
            "56" => "Subtotal function is invalid",
            "57" => "Subtotal group by column is invalid",
            "58" => "The affected columns of subtotal function must be a valid array of columns",
            "59" => "The function of subtotals must be a valid function",
            "60" => "Filteration by the current active user is allowed only for private reports ",
            "61" => "Community edition does not support this feature",
            "62" => "One or more of the group by fields are not included in the selected fields array. Please ensure that all the group by fields are included in the selected fields array.",
            "63" => "One or more of the sort by fields are not included in the selected fields array. Please ensure that all the sort by fields are included in the selected fields array."
        )
    );

}
