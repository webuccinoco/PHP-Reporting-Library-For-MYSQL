<?php
use SRE\Engine\CustomEngine;
use SRE\Engine\ReportOptions;
if (file_exists("../vendor/autoload.php"))
    require_once("../vendor/autoload.php");
else
    require_once("../sre_bootstrap.php");

try {

    $report = new ReportOptions();
    $report->select_tables("items")
            ->set_grouping(array("category","country"))
            ->sort_by("category",1)
            ->filter_between("price", 15, 50)
            ->filter_more("rating", 3.5)
            ->filter_not_null("code")
            ->set_filters_grouping("or")
            ->set_chkSearch(false)
            ->select_fields(array("id","code","name","price","reorder_level","units_in_stock","category","country","rating"))
            ->set_style_name("grey")
            ->set_records_per_page(25)
            ->set_language("es")
            ->set_title("Items Per category")
            ->set_header("Here is some HTML code that you can use to customize the header of the report.");
    $engine = new CustomEngine($report);
    $report_path = $engine->create_report();
    if ($report_path) {
       // The user will be redirected to the URL of the generated report. All generated reports are stored as subdirectories under /sre_reports.
       header("location: ".$report_path);
       exit();
    }
} catch (Exception $e) {
    echo $e->getMessage();
}