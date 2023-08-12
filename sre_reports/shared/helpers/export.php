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
 * Expoert to CSV.
 * ################################################################################################
 */

function export_csv($sql, $limits, $start, $duration, $records_count) {
   // var_dump($sql);
    global $fields, $empty_report_lang;
    
    // validation of exporting parameters
    $result = validate_export_parameters($limits, $start, $duration, $records_count, false);
   //var_dump($result);
    // adjust header to send the file
   
    $html = "";

    $fields_count = count($fields);
    $header = "";
    foreach ($fields as  $v) {
        $field = $v;
        $header .= str_replace(',', ';', $field) . ',';
    }
    $header = rtrim($header, ", ");    
    //var_dump($header);
    // output CSV field names
    $header = $header . " " . PHP_EOL;
   
  
    $k = 0;
    $records = $header;
   
    if (!empty($result)) {
    
        foreach ($result as $row) {
          

            // $i++;
            // $field_data = "";
            foreach ($row as $key => $val) {
                $field_data = $val;
                $field_data = str_replace("\r\n", ' ', $field_data);
                $field_data = str_replace(',', ';', $field_data);
                $field_data = str_replace("\n", ' ', $field_data);

                $field_data .= ',';

                $records .= $field_data;
            }
            $records =  rtrim($records, ", ");
            $records .= PHP_EOL;
           
            // $records = mb_convert_encoding($records, 'UCS-2LE', 'UTF-8');
        }
    } else {
        $records .= $empty_report_lang . PHP_EOL;
    }
    
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=export.csv;');
    header('Content-Transfer-Encoding: binary');

    $fp = fopen('php://output', 'w');

    // add BOM to fix UTF-8 in Excel
    fputs($fp, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
    if ($fp) {
        fwrite($fp, $records);
    }

    fclose($fp);
    exit();
}

/*
 * #################################################################################################
 * Expoerting to XML .
 * ################################################################################################
 */

function export_xml($sql,$limits, $start, $duration, $records_count) {

    // adjust header to send the file
    global $fields, $empty_report_lang,$calculated_columns;
    // validation of exporting parameters
    $result = validate_export_parameters($limits, $start, $duration, $records_count, false);
  
    $fields_arr = array();
    // output CSV HTTP headers ...
    // start getting data from the sql statement

    $fields_count = count($fields);
    $tags = array();

    // add fields names to the array
    for ($i = 0; $i < $fields_count; $i ++) {
        $field = $fields [$i];
        $field_name = str_replace(']]>', ']>', $field);
        // removing invalid characters from field name
        $chars = array(
            "(",
            ")"
        );
        foreach ($chars as $v) {
            $field_name = str_replace($v, "", $field_name);
        }

        $field_name = str_replace(' ', '_', $field_name);
        array_push($tags, $field_name);
    }

    // xml header

    $html = "<?xml version='1.0'  encoding='utf-8' ?>" . PHP_EOL;
    $html .= "<RECORDS>" . PHP_EOL;
    // iterate through rows
    if (!empty($result)) {
        foreach ($result as $row) {
          
            $html .= "<RECORD>" . PHP_EOL;
            $i = 0;
            foreach ($fields as $f) {

                // $html .= "<" . $tags [$i] . ">" . escape (clean_input($v) ) . "</" . $tags [$i] . ">". PHP_EOL;

                $html .= "<" . $tags [$i] . "><![CDATA[" . escape($row[get_field_part($f, $row)]) . "]]></" . $tags [$i] . ">" . PHP_EOL;
                // }
                $i ++;
            }

            $html .= "</RECORD>" . PHP_EOL;
        }
    } else {
        $html .= $empty_report_lang . PHP_EOL;
    }
    $html .= "</RECORDS>" . PHP_EOL;
    ob_start();
    header("Cache-control: private");
    header("Content-type: application/force-download");

    if (strstr($_SERVER ["HTTP_USER_AGENT"], "MSIE"))
        header("Content-Disposition: filename=data.xml"); // For IE
    else
        header("Content-Disposition: attachment; filename=data.xml"); // For Other browsers
    echo $html;
    ob_end_flush();
}




function check_is_sub_total() {
 return false;
}

?>