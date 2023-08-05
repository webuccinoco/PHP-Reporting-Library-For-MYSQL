<?php
use SRE\Engine\CustomEngine;
use SRE\Engine\ReportOptions;

if (file_exists(__DIR__ ."../vendor/autoload.php"))
    require_once(__DIR__ ."../vendor/autoload.php");
else
    require_once __DIR__ ."../sre_bootstrap.php";

try {

    $report = new ReportOptions();
    $report->select_tables("items")
            ->set_grouping("country")
            ->set_title("Items Per country")
            ->select_all_fields();
    $engine = new CustomEngine($report);
    $report_path = $engine->create_report();
    if ($report_path) {
        // The user will be redirected to the URL of the generated report. All generated reports are stored as subdirectories under /sre_reports.
        header("location: " . $report_path);
        exit();
    }
} catch (Exception $e) {
    echo $e->getMessage();
}