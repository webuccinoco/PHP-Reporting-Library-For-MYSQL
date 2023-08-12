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

require_once 'MysqliHandler.php';
require_once 'PDOHandler.php';
class DatabaseHandler {
	protected $link;
	public $used_extension; // this value will be used in the get_used_extension function
	public $extension = '';
	public $debug = false;
	public function __construct($host, $user, $pass, $db = '', $allow_debug = false, $extension = '') {
		$this->debug = $allow_debug;
		$extensions = array (
				"mysqli",
				"pdo" 
		);
		if (in_array ( strtolower ( $extension ), $extensions ))
			$this->extension = strtolower ( $extension );
		else
			$this->extension = "";
		
		$this->extension = strtolower ( $extension );
		if (extension_loaded ( 'pdo' ) && version_compare ( PHP_VERSION, '5.1.0' ) >= 0 && $this->extension != 'mysqli') {
			$this->used_extension = "pdo";
			$this->link = new PDOHandler ( $host, $user, $pass, $db, $this->debug );
		} elseif (extension_loaded ( 'mysqli' ) && function_exists ( 'mysqli_stmt_get_result' )) {
			$this->used_extension = "mysqli";
			$this->link = new MysqliHandler ( $host, $user, $pass, $this->debug, $db );
		} else {
			die ( "No Db drive is recognized" );
		}
	}
	public function get_used_extension() {
		return $this->used_extension;
	}
	
	// this function make query to fetch data from database ( Like using SELECT & SHOW ), this function return array and not handler
	public function query($sqlStatement, $keyType = "NUM", $params = array(), $paramsType = '') // $keyType = ASSOC, NUM, BOTH
{
		if (extension_loaded ( 'pdo' ) && version_compare ( PHP_VERSION, '5.1.0' ) >= 0 && $this->extension != 'mysqli') {
			return $this->link->query ( $sqlStatement, $keyType, $params ); // pdo
		} else {
			$edited_params = array ();
			if ($paramsType !== '')
				$edited_params = array_merge ( array (
						$paramsType 
				), $params );
			return $this->link->query ( $sqlStatement, $keyType, $edited_params ); // mysqli
		}
	}
	
	// sanitize string
	public function sanitize_values($string) {
		return $this->link->sanitize_values ( $string );
	}
	
	// sanitize array
	public function sanitize_array($array) {
		$clean = array ();
		foreach ( $array as $key => $value ) {
			if (is_array ( $value ))
				$clean [] = $this->sanitize_array ( $value );
			else
				$clean [] = $this->sanitize_values ( $value );
		}
		return $clean;
	}
	
	// this function to check if connection failed or succeeded
	public function is_connection_failed() // if connection failed return true
{
		return $this->link->is_connection_failed ();
	}
	
	// this function return number of rows for current query
	public function get_num_rows() {
		return $this->link->get_num_rows ();
	}
	
	// this function return database handler type
	public function get_db_handler_type() {
		return $this->link->get_db_handler_type ();
	}
	
	// this function for close connection
	public function close_connection() {
		$this->link->close_connection ();
	}
	public function available_extensions() {
		$available_extensions = array ();
		if (extension_loaded ( 'mysqli' ) && function_exists ( 'mysqli_stmt_CLEANED_result' ))
			$available_extensions [] = 'Mysqli';
		if (extension_loaded ( 'pdo' ) && version_compare ( PHP_VERSION, '5.1.0' ) >= 0)
			$available_extensions [] = 'PDO';
		if (extension_loaded ( 'mysql' ))
			$available_extensions [] = 'Mysql';
		
		return $available_extensions;
	}
}
?>