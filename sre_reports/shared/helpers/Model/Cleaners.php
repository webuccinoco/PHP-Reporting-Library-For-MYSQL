<?php
/**
 * Smart Report Engine
 * Community Edition
 * Author : Webuccino 
 * All copyrights are preserved to Webuccino
 * URL : https://mysqlreports.com/
 *
 */
if (! defined ( "DIRECTACESS" ))
	exit ( "No direct script access allowed" );
	
	/*
 * #################################################################################################
 * Sanitization functions (All Sanitization functions starts with "clean_"
 * ################################################################################################
 */
	
/*
 * clean_input
 *
 * Basic Sanitization of input which include stripping of harmful special characters .
 * It is called by other sanitization methods .
 *
 * @param (str) theinput to Sanitize
 * @param (remove_all_specials) if set to true it will strip all special characters
 * @param (remove_spaces) if set to true will strip all spaces
 * @param (remove_percentage) if set to false, "%" will be left intact .
 *
 * @return the sanitized variable .
 */
function clean_input($str, $remove_all_specials = false, $remove_spaces = false, $remove_percentage = true,$allowed_chars = array()) {
	debug ( "string to clean" . $str, false );
	$str = trim ( $str );
	$str = strip_tags ( $str );
	if ($remove_percentage == true) {
		$str = str_ireplace ( "%20", "", $str );
		$str = str_ireplace ( "%22", "", $str );
		$str = str_ireplace ( "%", "", $str );
	}
	// allowed @ . , _- # and space rest are prevented .
	$harmfuls_filter_clean = array (
			
			"\\",
			"'",
			'"',
			"&",
			"?",
			"<",
			">",
			"}",
			"\t",
			"\0",
			'\b',
			'\n',
			'\r',
			'\t',
			'\Z',
			"\n",
			"\r",
			"\x1a",
			"=",
			"+",
			"|",
			"0x00",
			"{",
			"}",
			"!",
			
			'<!--',
			'<![CDATA[',
			'&lt;!--',
			'--&gt;',
			";",
			"[",
			"]",
			"~",
			"`",
			"..",
			"^",
			"&amp;",
			"&lt;",
			"&gt;",
			"&quot;",
			"&#039;",
			"UNION ",
			"insert ",
			"drop ",
			"delete ",
			"select ",
			
			"%3c", // <
			"%253c", // <
			"%3e", // >
			"%0e", // >
			"%28", // (
			"%29", // )
			"%2528", // (
			"%26", // &
			"%24", // $
			"%3f", // ?
			"%3b", // ;
			"%3d", // =
			"update" 
	);
	$harmful_clean_only = array (
			"$" 
	);
	
	$str = str_ireplace ( array_diff($harmfuls_filter_clean,$allowed_chars), "", $str );
	$str = str_ireplace ( array_diff($harmful_clean_only,$allowed_chars), "", $str );
	
	if ($remove_all_specials) {
		// all are prevented
		$str = preg_replace ( '~[^\p{L}\p{N}]++~u', '', $str );
	}
	
	if ($remove_spaces) {
		$str = str_replace ( " ", "", $str );
	}
	return trim ( $str );
}

/*
 * clean_email
 *
 * Basic Sanitization of input which include stripping of harmful special characters .
 * It is called by other sanitization methods .
 *
 * @param (str) theinput to Sanitize
 *
 * @return the sanitized variable .
 */
function clean_email($var) {
	return filter_var ( clean_input ( $var ), FILTER_SANITIZE_EMAIL );
}

/*
 * clean_array
 *
 * Basic Sanitization of array which include stripping of harmful special characters .
 *
 *
 * @param (arr) the input array to be Sanitized
 *
 * @return the sanitized array .
 */
function clean_array($arr, $remove_percentage = true) {
	// this function is called to clean the query parameters
	$tmp = array ();
	foreach ( $arr as $k => $v ) {
		
		$tmp [clean_input ( $k )] = clean_input ( $v, false, false, false,array("&","?") );
	}
	return $tmp;
}
/*
 * clean_number
 *
 * Sanitize a numerical value both int or float
 *
 * @param (var) the numerical value to Sanitize
 * @param (data_type) the data type of the input variable either int or float .
 * @return the sanitized variable .
 */
function clean_number($var, $data_type = "int") {
	if ($data_type == "int") {
		$result = filter_var ( $var, FILTER_SANITIZE_NUMBER_INT );
		return ( int ) clean_input ( $result, true );
	} else {
		$result = filter_var ( $var, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND );
		return ( float ) clean_input ( $result, false );
	}
}

/*
 * clean_boolean
 *
 * Sanitize a button value make it an acceptable boolean True or false .
 * If the button value is posted it will be replaced with True otherwise false .
 *
 * @param (var) the value to Sanitized
 *
 * @return the sanitized variable .
 */
function clean_boolean($var) {
	$var = clean_input ( $var );
	if (isset ( $var ) && ! empty ( $var ))
		return True;
	else
		return false;
}

/*
 * clean_lockup
 *
 * Sanatize a lockup value by setting it to one of the predefined options
 * or to a default value if none of the options is recognized
 *
 * @param (var) the value to Sanitized
 * @param(options) the predefined options array
 * @param (default) the option to load if no of the options is recognized
 *
 * @return the sanitized variable .
 */
function clean_lockup($var, $options = array(), $default = "") {
	$var = clean_input ( $var );
	$key = array_search ( strtolower ( $var ), array_map ( 'strtolower', $options ) );
	if (! $key) {
		return $default;
	} else {
		return $options [$key];
	}
}

?>
