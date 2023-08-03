<?php
/**
 * Smart Report Engine
 * Community Edition
 * Author : Webuccino 
 * All copyrights are preserved to Webuccino
 * URL : https://mysqlreports.com/
 *
 */
define ( "DIRECTACESS", "true" );
ob_start();
require_once 'request.php';
// valid only for mobile views
if (strtolower($layout) != "mobile") {
    ob_end_clean();
	header ( 'Location: ' . $file_name . '.php' );
        exit();
}
//case mobile layout displayed in a mobile or tablet screen
if (isset ( $detect )) {
	if ($detect->isMobile () || $detect->isTablet ()) {
            ob_end_clean();
		header ( 'Location: ' . $file_name . '.php' );
                exit();
	}
}

$mobile_report_url =  $file_name . '.php';
if ($_SERVER ['QUERY_STRING'] !== "") {
	$mobile_report_url = $mobile_report_url . "?" . $_SERVER ['QUERY_STRING'];
}
ob_end_flush();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title ?> - Tablet View </title>
<style type="text/css">
.ipad {
	background-image: url(../shared/images/icons/ipad-emulator.jpg);
	background-repeat: no-repeat;
	height: 727px;
	width: 800px;
	margin-right: auto;
	margin-bottom: 10px;
	margin-left: auto;
}
</style>
</head>

<body>
	<div
		style="text-align: center; position: absolute; width: 200px; margin: 0px auto;">
		<a title="<?php echo $Mobile_view_language;?>" href="Mobile.php"><img
			border="0" src="../shared/images/icons/view_mobile.png" /></a>
	</div>
	<div class="ipad">
		<iframe src="<?php echo $mobile_report_url; ?>" frameborder="0" width="582" height="423"
			style="background-color: #FFF; margin-left: 108px; margin-top: 157px;"></iframe>

	</div>

</body>
</html>
