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
            ->set_grouping("country")
            ->sort_by("country", 1)
            ->filter_like("category", "sunglasses")
            ->filter_between("price", 15, 50)
            ->filter_more("rating", 3.5)
            ->filter_not_null("code")
            ->set_title("Using data filters")
            ->select_fields(array("id","code","name","price","reorder_level","units_in_stock","category","country","rating"));
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