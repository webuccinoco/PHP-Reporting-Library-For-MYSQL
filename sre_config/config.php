<?php
/**
 * Smart Report Engine
 * Community Edition 2023
 * Author : Webuccino
 * All copyrights are preserved to Webuccinoco
 * URL : https://mysqlreports.com
 *
 */
define("SRE__DEFAULT__USER__", "");
define("SRE__DEFAULT__PASS__", "");
define("SRE__DEFAULT__HOST__", "localhost");
define("SRE__DEFAULT__DB__", "");


define("SRE__ALLOWED_REPORT_LANGUAGES__", json_encode(array("en",
    "de",
    "ar",
    "es",
    "fr",
    "it")));
define("SRE__DEFAULT_REPORT_LANGUAGE__", "en");



//directory names


define("SRE__Auto__Replace__Reports__", 0); //replace existing Reports with same name
define("SRE__LANGUAGE__", "en");  
define("SRE_DEFAULT_LAYOUT_","AlignLeft");
define("SRE_TEST_MODE",0);

define("SRE_PUBLIC_REPORT", "PUBLIC_REPORT");

  
/// a numeric data type 
/**
  * a numeric data type 
  */
define("SRE_NUMBER","NUMBER");
/**
  * A textual data type
  */
define("SRE_TEXT","TEXT");/**
  * A date data type
  */
define("SRE_DATE","DATE");
/**
  * an array data type
  */
define("SRE_ARRAY","ARRAY");
/**
  * An object data type
  */
define("SRE_OBJECT","OBJECT");
/**
  * A Boolean data type
  */
define("SRE_BOOLEAN","BOOLEAN");
/**
  * array of all allowed data types
  */
define("SRE_DATA_TYPES",json_encode(array(
    SRE_NUMBER,
    SRE_TEXT,
     SRE_DATE,
    SRE_ARRAY,
    SRE_OBJECT,
    SRE_BOOLEAN   
    
)));
/**
  * A table based data source
  */
define("SRE_Table","table");
/**
  *  A SQL query data source
  */

define('BASEPATH', true);
define("DIRECTACESS", true);
define("SRE__Product_DIR__","SmartReportingEngine");
define("SRE__src_DIR__","src");
define("SRE__Engin__DIR__", "Engine");  //The directory in which the model classes is to be saved
define("SRE__REPORTS__DIR__", "sre_reports"); //The directory in which reports to be stored
define("SRE__CORE__DIR__", "Core"); //the directory in which the core engine files is located
define("SRE_MOBILE_REDIRECT",false);
define("SRE_SUM","sum");
define("SRE_AVERGAE","average");
define("SRE_COUNT","count");
define("SRE_MAX","max");
define("SRE_MIN","min");




