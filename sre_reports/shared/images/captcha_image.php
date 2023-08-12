<?php

/**
 * Smart Report Engine
 * Community Edition
 * Author : Webuccino
 * All copyrights are preserved to Webuccino
 * URL : https://mysqlreports.com/
 *
 */
define("DIRECTACESS", "true");
error_reporting(0);
ob_start();
require_once("../config/general_config.php");
require_once("../config/admin.php");
require_once("../helpers/session.php");
require_once("../helpers/Model/safeValue.php");


$_GET = array();
$_POST = array();
$_REQUEST = array();
$_ENV = array();
$_FILES = array();
$_COOKIE = array();
if ($obj_captcha) {
    header('Content-Type: image/png');
    $obj_captcha->generate_security_code();
    $obj_captcha->render_captcha();
}
ob_end_flush();
?>