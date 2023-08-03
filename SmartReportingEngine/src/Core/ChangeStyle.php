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
require_once ("request.php");
/*
 * #################################################################################################
 * hANDLING SUPER GLOBALS
 * ################################################################################################
 */
$_CLEANED = remove_unexpected_superglobals($_GET, array("setStyle"));
$_GET = array ();
$_POST = array ();
$_REQUEST = array ();
$_ENV = array ();
$_FILES = array ();
$_COOKIE = array ();
$report_url = basename ( __DIR__ );
$report_url .= ".php";
if(!file_exists($report_url)){
	$report_url = $file_name . ".php"	;
}
if(!isset($_CLEANED["RequestToken"]) || $_CLEANED["RequestToken"] != $_SESSION[$request_token]){
	ob_end_clean( );
        header ( "location: " . $report_url );
	exit ();	
}

/*
 * #################################################################################################
 * Changing the style
 * ################################################################################################
 */
$all_styles = array (
		"blue",
		"grey",
		"default" 
);
$posted_style = isset ( $_CLEANED ["setStyle"] ) ? $_CLEANED ["setStyle"]  : "";
$posted_style_key = array_search ( strtolower ( $posted_style ), $all_styles );
$keys = array (
		0,
		1,
		2 
);
if (in_array ( $posted_style_key, $keys )) {
	$_SESSION ["change_style_srm7"] = $all_styles [$posted_style_key];
}
ob_end_clean();
header ( "location: " . $report_url );
exit ();
?>