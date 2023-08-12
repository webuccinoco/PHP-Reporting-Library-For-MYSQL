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
class Report{
	protected  $fields;
	protected $report_key;	
	protected $sort_by;
	protected $group_by;
	
	public function __Construct(){
		$this->report_key = sha1 ( str_replace ( ".", "", $_SERVER["REMOTE_ADDR"] ) . "secure_login" );		
	}
	
	public function set_group_by ($group_by){
	$this->group_by = is_array($group_by) ? $group_by : array();
			
	}
	
	public function set_fields($fields){
	$this->fields = is_array($fields)? $fields : array();		   
	}
	
	public function set_sort_by($sort_by){
	$this->sort_by = is_array($sort_by)? $sort_by : array();
	}
	

}
?>
