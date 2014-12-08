<?php

/**
 * Formality - HTML5 Compatible PHP 5.3 form manipulation.
 *
 * You are free:
 *  to Share — to copy, distribute and transmit the work
 *  to Remix — to adapt the work
 *
 * Under the following conditions:
 *
 * Attribution — You must attribute the work in the manner specified by the author or licensor
 * (but not in any way that suggests that they endorse you or your use of the work). 
 *
 * @category   Formality
 * @package    Formality
 * @author     Jonathan Hulme <jon@digipigeon.com>
 * @author     Jon Ellis <jellis@digipigeon.com>
 * @copyright  2005-2010 Digipigeon Limited
 * @license    http://creativecommons.org/licenses/by/3.0/  Creative Commons Attribution 3.0 Unported Licens
 * @version    SVN: $Id$
 * @link       http://www.digipigeon.com/formality
 */
 
class Formality_Field_File extends Formality_Field{
	protected $_file_info;

	public function info(){
		return $this->_file_info;
	}

	public function pull(){
		if (!$this->validate()) return false;
		$file_info = $_FILES[$this->config["id"]];
		$this->_file_info = $file_info;
		if (!empty($this->config["dest"])){
			$filename = $file_info["name"];
			
			if (!empty($this->config["forcefilename"])){		
				$dot_pos = strpos($this->config["forcefilename"],".");
				if ($dot_pos >0){
					$filename = $this->config["forcefilename"];
				}else{					
					$filename = $this->config["forcefilename"] . substr($filename,strpos($filename,"."));
				}
			} 
			$dest = $this->config["dest"] . "/" . $filename;
			move_uploaded_file($file_info["tmp_name"], $dest);
			$this->config['value'] = $dest;
			return $dest;
		} else{
			$this->config['value'] = $_FILES[$this->config["id"]]['tmp_name'];
			return $_FILES[$this->config["id"]]['tmp_name'];			
		}
	}
	
	public function validate(){
		$allow = true;
		
		if (empty($_FILES[$this->config["id"]]['tmp_name'])) return false;
		if (empty($_FILES[$this->config["id"]]['name'])) return false;
		$exts = $this->config['ext'];
		if (!is_array($exts)) $exts = explode(',',$exts);

		if (!preg_match('/\.(\w+)$/',$_FILES[$this->config["id"]]['name'],$match)) return false;

		if (!in_array($match[1], $exts)){
			$this->container->err('Extension Not Allowed');
			return false;
		}		
		return true;
	}
}
	