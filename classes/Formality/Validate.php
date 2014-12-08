<?php

class Formality_Validate{
	public $config;
	public $parent;

	public function __construct($config = Array(), &$parent = false){
		$this->parent = $parent;
		$this->config = array_merge($config, Formality::config('lib.validate._default'));
	}

	public function __call($name, array $argument){
		Formality::path_set($this->config, $name, $argument[0], true);
		//$this->config[$name] = $argument[0];
		return $this;
	}
	
	public static function factory($config = Array(), $parent=false){		
		return new Formality_Validate($config, $parent);
	}

	public function single_check($type, $setting,$value){
		switch($type){
			case "number":
			case "numeric":
				return is_numeric($value);
				break;
			case "integer":
				return (is_numeric($value) && (intval($value) == $value));
				break;
			case "max":
				return (is_numeric($value) && ($value <= $setting));
				break;
			case "min":
				return (is_numeric($value) && ($value >= $setting));
				break;
			case "positive":
				return $value >= 0;
				break;
			case "alpha":
				return preg_match("~^[a-zA-Z]+$~", $value);
				break;
			case "text":
				return preg_match("~^[a-zA-Z0-9 /.]+$~", $value);
				break;
			case "alphanumeric":
				return preg_match("~^[a-zA-Z0-9]+$~", $value);
				break;
			case "filename":
				return preg_match("~([0-9a-z_-]+[\.][0-9a-z_-]{1,4})$~", $value);
				break;
			case 'regex':
				return preg_match($setting, $value);
				break;
			case 'minlen':
				return strlen($value) >= $setting;
				break;
			case 'maxlen':
				return strlen($value) <= $setting;
				break;
		}
		return true;
	}

	public function check($value){
		$val = true;
		//Check for empty validate, which allows blank fields to be filled in
		if (!empty($this->config['empty']) && empty($value)) return true;
		foreach($this->config as $config_key => $config_value){
			$err_msg = $this->config['err']['_default'];
			if (!empty($this->parent->config['err']['_default'])){
				$err_msg = $this->parent->config['err']['_default'];
			}
			if (!empty($this->parent->config['err'][$config_key])){
				$err_msg = $this->parent->config['err'][$config_key];
			}

			$valid = true;
			
			if (!$this->single_check($config_key, $config_value, $value)){
				$valid = false;	
			} 
			if (!$valid){
				$val = false;
				if (in_array('inline', $this->config['errpos'])){
					$output = $this->config['mask']['inline'];
					$output = str_replace('[:err]', $err_msg, $output);
					$this->parent->container->err($output);
				}
				break;
			}
		}
		return $val;
	}
	
	public static function closure(&$parent, &$closure, $value){
		if ($r = $closure($parent, $value)){
			if ($r !== true){
				$parent->container->err($r);
				return false;
			}
		}
		return true;
	}
}

///**
// * DP_Validate_Email
// *
// * @package DP_Form
// * @author Jonathan Hulme
// * @copyright Digipigeon Limited 2008
// * @version 0.3
// * @access public
// */
//class DP_Form_Validate_Email extends DP_Form_Validate{
//  /**
//   * DP_Validate_Email::__construct()
//   *
//   * @param DP_Field $parent
//   * @param Array $prop
//   * @return
//   */
//	function __construct(&$parent,&$prop){
//		parent::__construct($parent,$prop);
//		$this->regex = "~^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9})$~";
//	}
//}
//
///**
// * DP_Validate_Date
// *
// * @package DP_Form
// * @author Jonathan Hulme
// * @copyright Digipigeon Limited 2008
// * @version 0.3
// * @access public
// */
//class DP_Form_Validate_Date extends DP_Form_Validate{
//  /**
//   * DP_Validate_Date::__construct()
//   *
//   * @param DP_Field $parent
//   * @param Array $prop
//   * @return void
//   */
//	function __construct(&$parent,&$prop){
//		parent::__construct($parent,$prop);
//		$this->regex = "~^(((0[1-9]|[12][0-9]|3[01])([-./])(0[13578]|10|12)([-./])(\d{4}))|(([0][1-9]|[12][0-9]|30)([-./])(0[469]|11)([-./])(\d{4}))|((0[1-9]|1[0-9]|2[0-8])([-./])(02)([-./])(\d{4}))|((29)(\.|-|\/)(02)([-./])([02468][048]00))|((29)([-./])(02)([-./])([13579][26]00))|((29)([-./])(02)([-./])([0-9][0-9][0][48]))|((29)([-./])(02)([-./])([0-9][0-9][2468][048]))|((29)([-./])(02)([-./])([0-9][0-9][13579][26])))$~";
//		$this->derr = "{field} is required in DD/MM/YYYY format";
//	}
//  /**
//   * DP_Validate_Date::process()
//   *
//   * @return boolean
//   */
//	public function process(){
//		if (!empty($this->prop["value"])) return parent::process();
//	}
//}
//
///**
// * DP_Validate_PasswordConfirm
// *
// * @package DP_Form
// * @author Jonathan Hulme
// * @copyright Digipigeon Limited 2008
// * @version $Id$
// * @access public
// */
//class DP_Form_Validate_PasswordConfirm extends DP_Form_Validate{
//  /**
//   * DP_Validate_PasswordConfirm::__construct()
//   *
//   * @param DP_Form $parent
//   * @param Array $prop
//   * @return
//   */
//	function __construct(&$parent,&$prop){
//		parent::__construct($parent,$prop);
//		$this->derr = Array();
//		$this->derr["match"] = "{field} not match Confirm Password";
//		$this->derr["maxlen"] = "Field '{field}' exceeds maximum allowed length";	
//		$this->derr["minlen"] = "Field '{field}' does not reach minimum required length";;	
//	}
//  /**
//   * DP_Validate_PasswordConfirm::process()
//   *
//   * @return boolean
//   */
//	public function process(){
//		if (!empty($this->prop["maxlen"]) && strlen($this->prop["value"])>$this->prop["maxlen"]){
//			$this->err = $this->parent->get_error_text("maxlen");
//			return false;
//		} 
//		if (!empty($this->prop["minlen"]) && strlen($this->prop["value"])<$this->prop["minlen"]){
//			$this->err = $this->parent->get_error_text("minlen");
//			//$this->err = "Field '{$this->prop["label"]}' does not reach minimum required length";	
//			return false;
//		} 
//		if (!empty($this->prop["match"]) && !empty($this->parent->parent->field[$this->prop["match"]])){
//			if ($this->parent->parent->field[$this->prop["match"]]["value"] != $this->prop["value"]){
//				$this->err = $this->parent->get_error_text("match");
//				return false;
//			}
//		}
//		return true;
//	}	
//}
//
///**
// * DP_Validate_Upload
// *
// * @package DP_Form
// * @author Jonathan Hulme
// * @copyright Digipigeon Limited 2008
// * @version 0.3
// * @access public
// */
//class DP_Form_Validate_Upload extends DP_Form_Validate{
//  /**
//   * DP_Validate_Upload::__construct()
//   *
//   * @param DP_Field $parent
//   * @param Array $prop
//   * @return
//   */
//	function __construct(&$parent,&$prop){
//		parent::__construct($parent,$prop);
//		if (!empty($prop["regex"])) $this->regex = "~" . $prop["regex"] . "~";
//		$this->derr = Array();
//		$this->derr["size"] = "'{field}' filesize is too large.";
//		$this->derr["name"] = "'{field}' filename has been rejected.";
//	}
//	
//  /**
//   * DP_Validate_Upload::process()
//   *
//   * @return boolean
//   */
//	public function process(){
//		if (!empty($this->prop["allow_empty"]) && empty($_FILES[$this->prop["id"]])) return true;
//		if (!$this->upload_waiting()) return false;
//		$file_info = $_FILES[$this->prop["id"]];
//
//		if (!empty($this->prop["maxsize"]) && $file_info["size"] > $this->prop["maxsize"]){
//			$this->err = $this->parent->get_error_text("size");
//			//$this->gen_err();
//			return false;
//		}
//		if (!empty($this->regex) && !preg_match($this->regex,$file_info["name"])){
//			$this->err = $this->parent->get_error_text("name");
//			//$this->gen_err("'{$this->prop["label"]}' filename has been rejected.");
//			return false;
//		}
//		return true;
//	}
//
//  /**
//   * DP_Validate_Upload::upload_waiting()
//   *
//   * @return boolean
//   */
//	public function upload_waiting(){
//		return array_key_exists($this->prop["id"],$_FILES);
//	}
//}
//?>