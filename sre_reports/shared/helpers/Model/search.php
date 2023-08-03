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
class Search_options {
	public $search_type;
	protected $search_keyword;
	protected $search_column;
	protected $dataType;
	protected $second_search_keyWord;
	protected $all_dataTypes;
	public function __construct($type = "quick", $search_keyword="", $dataType = "", $search_column = "", $second_search_keyWord = "") {
		if ($type == "quick")
			$this->search_type = "quick";
		if ($type == "advanced")
			$this->search_type = "advanced";
		
		$this->all_dataTypes = array (
				"int",
				"string",
				"bool",
				"date" 
		);
		$this->set_keyword ( $search_keyword );
		$this->set_search_column ( $search_column );
		$this->set_data_type ( $dataType );
		$this->set_second_key_word ( $second_search_keyWord );
	}
	public function set_keyword($keyword) {
		$this->search_keyword = $keyword;
	}
	public function set_search_column($column) {
		$this->search_column = $column;
	}
	public function set_data_type($data_type) {
		if (! empty ( $data_type ) && in_array ( $data_type, $this->all_dataTypes ))
			$this->dataType = $data_type;
	}
	public function set_second_key_word($keyword) {
		$this->second_search_keyWord = $keyword;
	}
	public function prepare_ordinary_search_statment($table, $fields) {
		$results = array ();
		$results ["types"] = "";
		$results ["parameters"] = array ();
		foreach ( $fields as $key => $val ) {
			$field = '';
			
			if (! strstr ( $val, "(" )) {
				if (count ( $table ) == 1) {
					
					$fildval = "`" . $val . "`";
				} else {
					$field = explode ( '.', $val );
					
					$fildval = "`" . $field [0] . "`." . "`" . $field [1] . "`";
				}
                                
                               $conditions [] = 'CONCAT(' . $fildval . ') like ? ';
                               $results ["types"] .= "s";
                               $results ["parameters"][] = '%' . trim ( $this->search_keyword ) . '%';

			}
			
		}
		if (count ( $conditions ) > 0)
			$conditions = '(' . implode ( ' OR ', $conditions ) . ')';
		
		$results ["sql"] = $conditions;	
		
		return $results;
	}
	public function prepare_advanced_search_statment() {
		$results = array ();
		if (! in_array ( $this->dataType, $this->all_dataTypes ))
			return false;
		if ($this->dataType === "int" && is_numeric ( $this->search_keyword )) {
			
			if (! empty ( $this->second_search_keyWord ) && is_numeric ( $this->second_search_keyWord )) {
				// $results["sql"]= "$this->search_column >= $this->search_keyword and $this->search_column <= $this->second_search_keyWord ";
				$results ["sql"] = "$this->search_column >= ? and $this->search_column <= ? ";
				$results ["parameters"] = array (
						( float ) $this->search_keyword,
						( float ) $this->second_search_keyWord 
				);
				
				$results ["types"] = get_param_type ( $this->search_keyword );
				$results ["types"] .= get_param_type ( $this->second_search_keyWord );
				return $results;
			} else {
				// $results["sql"]= "$this->search_column >= $this->search_keyword ";
				$results ["sql"] = "$this->search_column >= ? ";
				$results ["parameters"] = array (
						$this->search_keyword 
				);
				$results ["types"] = get_param_type ( $this->search_keyword );
				
				return $results;
			}
		} elseif ($this->dataType === "date" && check_is_date ( $this->search_keyword )) {
			
			if (! empty ( $this->second_search_keyWord ) && check_is_date ( $this->second_search_keyWord )) {
				// $results["sql"] = "$this->search_column >= '$this->search_keyword' and $this->search_column <= '$this->second_search_keyWord' ";
				$results ["sql"] = "$this->search_column >= ? and $this->search_column <= ? ";
				$results ["parameters"] = array (
						trim ( $this->search_keyword ),
						trim ( $this->second_search_keyWord ) 
				);
				$results ["types"] = "ss";
				return $results;
			} else {
				// $results["sql"] = "$this->search_column >= $this->search_keyword ";
				$results ["sql"] = "$this->search_column >= ? ";
				$results ["parameters"] = array (
						$this->search_keyword 
				);
				$results ["types"] = "s";
				return $results;
			}
		} elseif ($this->dataType === "string") {
			// $results["sql"] = "CONCAT( ". $this->search_column . ") like '%" . $this->search_keyword ."%'";
			$results ["sql"] = "CONCAT( " . $this->search_column . ") like ? ";
			$results ["parameters"] = array (
					"%" . trim($this->search_keyword) . "%" 
			);
			$results ["types"] = "s";
			return $results;
		} elseif ($this->dataType === "bool" && (is_bool ( $this->search_keyword ) || $this->search_keyword == 1 || $this->search_keyword == 0)) {
			// $results["sql"] = "$this->search_column = $this->search_keyword";
			$results ["sql"] = "$this->search_column = ?";
			$results ["parameters"] = array (
					( int ) $this->search_keyword 
			);
			$results ["types"] = "i";
			return $results;
		} else {
			return false;
		}
	}
	private function get_param_type($var) {
		// this function should be used with numeric parameters to decide wether it is an integer or double
		$int_var = ( int ) $var;
		
		if ($var == $int_var) {
			return "i";
		} else {
			return "d";
		}
	}
}