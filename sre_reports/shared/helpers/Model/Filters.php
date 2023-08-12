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
 * Filtration functions (All Filters starts with "check_"
 * ################################################################################################
 */
	
/*
 * check_numeric_parameter
 *
 * validate weather a variable is a numeric (integer or float)
 *
 * @param (var) the variable to be checked
 * @param (max) if there is a maximum value to check against, if not, default is 0
 * @param(allow float) set to true, to allow float checking, otherwise, it will check for integers only
 * @param(min) if there is a min value to check against, default is -1
 * @return type boolen depends on the fiter results
 */
function check_numeric_parameter($var, $max = 0, $allow_float = false, $min = -1) {
	$float_var = ( float ) $var;
	if ($max != 0) {
		
		if ($float_var > $max) {
			logging ( PHP_EOL . " var : $var Not accepted because it is more than $max " );
			return false;
		}
	}
	
	// the zero case
	if ($var == 0 && $var > $min && check_no_specials ( $var ) && ( int ) $var == $var && is_numeric ( $var )) {
		return true;
	}
	
	if ($allow_float) {
		if (is_numeric ( $var ) && $float_var > $min && check_no_specials ( $var ) && ( float ) $var == $var) {
			logging ( PHP_EOL . "  var : $var is  validated as float  \n" );
			return true;
		} else {
			logging ( PHP_EOL . " var : $var is NOT validated as float  \n" );
			return false;
		}
	} else {
		if (is_numeric ( $var ) && filter_var ( $var, FILTER_VALIDATE_INT ) !== false && $float_var > $min && check_no_specials ( $var ) && ( int ) $var == $var) {
			logging ( PHP_EOL . " var: $var is  validated as int   \n" );
			return true;
		} else {
			logging ( PHP_EOL . " var : $var is NOT validated as int  \n" );
			return false;
		}
	}
}

/*
 * check_no_specials
 *
 * validate weather a variable is free from special characters, it can use for any language not just english
 *
 * @param (var) the variable to be checked
 * @param (allow_spaces) flagset to true if spaces are allowed
 * @param(allow_specials) array includs any special characters to be excluded from the filteration
 *
 * @return type boolen depends on the fiter results
 */
function check_no_specials($var, $allowed_specials = array()) {
	global $language;
	if ($var === "")
		return true;
	
	logging ( PHP_EOL . " filtering var $var" . PHP_EOL );
	$harmfuls = array (
			">",
			"<",
			'"',
			"'",
			"&lt;",
			"&gt;",
			"&quot;",
			"&#039;",
			"UNION ",
			"insert ",
			"drop ",
			"delete ",
			"select ",
			"update " 
	);
	
	foreach ( $harmfuls as $val ) {
		if (stristr ( $var, $val )) {
                                      
			logging ( PHP_EOL . " Result Invalid reason : string $var includs harmful character $val" . PHP_EOL );
			return false;
		}
	}
	
	if (! empty ( $allowed_specials ) && is_array ( $allowed_specials )) {
		foreach ( $allowed_specials as $special )
			
			$var = str_ireplace ( $special, "", $var );
	}
        
        return true;
	
	// case forign languages
	/*if (preg_match ( '/^[\p{L}\p{N} .-]+$/u', $var )) {
		
		logging ( PHP_EOL . " var: $var is validated as alphanumeric " . PHP_EOL );
		return true;
	} else {
		
		logging ( PHP_EOL . " var: $var is  NOT validated as alphanumeric, so it's rejected " . PHP_EOL );
		return false;
	}*/
}

/*
 * check_in_lockup
 *
 * validate weather a variable is already existed in a lockup array
 *
 * @param (needle) the variable to be checked
 * @param (haystack) the lockup array
 *
 *
 * @return type the index of the element in the array or false if not found
 */
function check_in_lockup($needle, $haystack) {
	$key = array_search ( $needle, $haystack );
	
	logging ( PHP_EOL . " checking lockup value, the results : $key" );
	return $key;
}

/*
 * check_is_email
 *
 * validate weather the input string is an email
 *
 * @param (var) the input string to be checked
 * @return type boolen depends on the fiter results
 */
function check_is_email($var) {
	if (! check_is_clean ( $var )) {
		logging ( PHP_EOL . "check email, the var: $var is NOT an email" );
		return false;
	}
	
	if (filter_var ( $var, FILTER_VALIDATE_EMAIL )) {
		logging ( PHP_EOL . " check email, the var: $var is an email" );
		return true;
	} else {
		logging ( PHP_EOL . " check email, the var: $var is NOT an email" );
		return false;
	}
}

/*
 * check_is_clean
 *
 * validate weather a variable is free from harmful special characters, can be used with any language
 *
 * @param (str) the variable to be checked
 * @param (no_space) flagset to true if spaces are NOT allowed
 *
 *
 * @return type boolen depends on the fiter results
 */
function check_is_clean($str, $no_space = false) {
	// No attacks and No special characters and No spaces
	logging ( PHP_EOL . " Check variable : $str, for harmful specials, check include spaces : $no_space " . PHP_EOL );
	$str = strtolower ( $str );
	// dangrous special characters
	
	if (empty ( $str )) {
		Logging ( "Data is empty " . PHP_EOL );
		return true;
	}
	
	$specials = array (
			"/",
			"\\",
			"'",
			'"',
			"&",
			"%",
			
			"<",
			">",
			
			"*",
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
			"update " 
	);
	foreach ( $specials as $val ) {
		if (stristr ( $str, $val )) {
                                
			logging ( PHP_EOL . " Result Invalid reason : string $str includs harmful special chracters $val " . PHP_EOL );
			return false;
		}
	}
	
	// case No spaces
	if (strstr ( $str, " " ) && $no_space == true) {
		logging ( "Result Invalid reason : string $str contains spaces " . PHP_EOL );
		return false;
	}
	logging ( PHP_EOL . " string $str valid " . PHP_EOL );
	return true;
}

/*
 * check_string_length
 *
 * validate weather the input string has a specific length
 *
 * @param (var) the input string to be checked
 * @param (max) the max string length
 * @param (min) the min string length
 *
 *
 * @return type boolen depends on the fiter results
 */
function check_string_length($var, $max = 30, $min = 0) {
	if (strlen ( $var ) > $min && strlen ( $var ) < $max) {
		logging ( PHP_EOL . " string $var length is valid  " . PHP_EOL );
		return true;
	} else {
		logging ( PHP_EOL . " string $var length is  Not valid  " . PHP_EOL );
		return false;
	}
}

/*
 * check_is_date
 *
 * validate weather the input string is a date
 *
 * @param (var) the input string to be checked
 * @return type boolen depends on the fiter results
 */
function check_is_date($str) {
	$stamp = strtotime ( $str );
	if (! is_numeric ( $stamp )) {
		logging ( PHP_EOL . " $str is Not a date" );
		return false;
	}
	$month = date ( 'm', $stamp );
	$day = date ( 'd', $stamp );
	$year = date ( 'Y', $stamp );
	if (checkdate ( $month, $day, $year )) {
		logging ( "\n $str is  a date" );
		return true;
	} else {
		logging ( PHP_EOL . " $str is Not a date" );
		return false;
	}
}

/*
 * check_search_keywords
 *
 * Prepared specifically for validating the advanced search keywords by making sure that
 * they are not empty and they are in their expected formats related to their datatypes
 *
 * @param (datatype) the data type of the search hich must be one of the following values:
 * "int", "string", "bool","date"
 * @keyword must never be empty
 * @keyword2 must not be empty if the data type is int or date
 * @return type boolen depends on the fiter results
 */
function check_search_keywords($datatype, $keyword, $keyWord2) {
	debug ( "checking the search keywords for the following parameters :" . PHP_EOL . "keyword: $keyword , keyword2: $keyWord2,datatype : $datatype" . PHP_EOL );
	if ($keyword === "") {
		debug ( "failed validation because first keyword is empty" . PHP_EOL );
		return false; // key word must never be empty
	}
	if ($datatype == "int") {
		// both keywords should not be empty and both must be numeric
		if ($keyWord2 === "" || ! is_numeric ( $keyword ) || ! is_numeric ( $keyWord2 )) {
			debug ( "failed validation because 2nd keyword could be empty or any of the keywords are not of numeric value as expected !" . PHP_EOL );
			return false;
		} else
			return true;
	} elseif ($datatype == "date") {
		// both keywords should not be empty and should be date
		if ($keyWord2 === "" || ! check_is_date ( $keyword ) || ! check_is_date ( $keyWord2 )) {
			debug ( "failed validation because 2nd keyword could be empty or any of the keywords are not of date value as expected!" . PHP_EOL );
			return false;
		} else
			return true;
	} elseif ($datatype == "bool") {
		$bits = array (
				"1",
				"0" 
		);
		// second key word must be empty and 1st keyword should be in a bool formats
		if (($keyword == "1" || $keyword == "0") && $keyWord2 == "") {
			return true;
		} else {
			debug ( "failed validation because 2nd keyword could be not empty or any of the keywords are not of bool value as expected!" . PHP_EOL );
			return false;
		}
	} elseif ($datatype == "string") {
		// 2nd key word must be empty andother function should check for special characters
		if ($keyWord2 !== "") {
			debug ( "failed validation because 2nd keyword is not empty while data type is string so it's not expected" . PHP_EOL );
			return false;
		} else
			return true;
	} else {
		// un expected data type
		return false;
	}
}

/*
 * check_password_formats
 *
 * make sure that the a string formats is a valid passowrd formats, by valid password formats we mean a set of formatting rules.
 *
 * @param (password) the string to be validated as password .
 * @return type boolen depends on the fiter results
 */

// Rules for validating any password formats
//
// No spaces
// No qoutes
// No harmful special characters (* , % , & , / , . , ~ , ` , ;)
// No sql commands
// Not less than 8 characters
// Not more than 16 characters
// at least one uppercase chracter
// at least one lower case character
// at least one digit
function check_password_formats($password) {
	global $maximum_password_length, $minimum_password_length;
	
	if (! is_numeric ( $maximum_password_length ) || $maximum_password_length == "" || $minimum_password_length == "" || $maximum_password_length == 0) {
		$maximum_password_length = 16;
		$minimum_password_length = 8;
	}
	$specials = array (
			"/",
			"\\",
			"'",
			'"',
			"&",
			"%",
			"<",
			">",
			
			"*",
			
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
			"0x00",
			";",
			"~",
			"`",
			".",
			"^",
			"&amp;",
			"&lt;",
			"&gt;",
			"&quot;",
			"&#039;",
			"UNION",
			"insert",
			"drop",
			"delete",
			"select",
			"update" 
	);
	// password not empty
	if ($password == "") {
		return false;
	} // length check
elseif ((strlen ( $password ) > $maximum_password_length) || (strlen ( $password ) < $minimum_password_length)) {
		return false;
	}	

	// No spaces
	elseif (stristr ( $password, " " )) {
		return false;
	} // NO specials
elseif (! check_harmful_chars ( $password, $specials )) {
		return false;
	} // one upper case char atleast
elseif (strtolower ( $password ) === $password) {
		return false;
	} // One lower case char atleast
elseif (strtoupper ( $password ) === $password) {
		return false;
	} // One number at least
elseif (! preg_match ( '~\d~', $password )) {
		return false;
	} else {
		return true;
	}
}

/*
 * check_username_formats
 *
 * make sure that the a string formats is a valid username formats, by valid username formats we mean a set of formatting rules.
 *
 * @param (username) the string to be validated as username.
 * @param (min) the min length of the username
 * @param (max) the max length of the username
 * @param (is_no_specials) prevent all special characters
 * @param (allowed_specials) allowed special characters
 * @return type bool depends on the fiter results
 */

// Rules for validating any password formats
//
// No spaces
// No qoutes
// No harmful special characters (* , % , & , / , . , ~ , ` , ;)
// No sql commands
// Not less than min
// Not more than max
function check_username_formats($username, $max, $min, $allowed_specials = array()) {

	//the word admin is forbidden 
	if(strtolower($username) === "admin"){
		return false;
	}
	
	
	if ($max == "" || $max == 0 || $min == 0 || $min == "") {
		$max = 16;
		$min = 8;
	}
	if ($username == "") {
		return false;
	} elseif (stristr ( $username, " " )) {
		return false;
	} elseif (strlen ( $username ) > $max || strlen ( $username ) < $min) {
		return false;
	} 

	elseif (! check_no_specials ( $username, $allowed_specials )) {
		return false;
	} elseif (! check_is_clean ( $username )) {
		return false;
	} else {
		return true;
	}
}
/*
 * check_harmful_chars
 *
 * make sure that the a string dose NOT contain a specific array of chracters.
 *
 * @param (var) the string to be validated
 * @param (chars) the array of characters which should be validated against .
 * @return type boolen depends on the fiter results
 */
function check_harmful_chars($var, $chars) {
	if (is_array ( $chars ) && ! empty ( $chars )) {
		foreach ( $chars as $char ) {
			if (stristr ( $var, $char )) {
				return false;
			}
		}
		// No specified chracters found in the string
		return true;
	} else {
		return false;
	}
}