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

class Formality_Label{
	public $config;
	public $parent;
	
	public function __construct($config = Array(), &$parent = false){
		$this->parent = $parent;
		$this->config = array_merge($config, Formality::config('lib.label._default'));
		$this->config['id'] = str_replace('[:id]', $this->config['id'], $this->config['idmask']);
	}
	
	public static function factory($id, $config = Array(), $parent=false){		
		$config['id'] = $id;
		$field = new Formality_Label($config, $parent);
		return $field;
	}
	
	public function render($field){
		$str_attributes = '';
		foreach($this->config['attributes'] as $key){
			if (array_key_exists($key,$this->config)){
				$str_attributes .= " $key=\"{$this->config[$key]}\"";
			}
		}
		$output = $this->config['html'];
		$output = str_replace('[:attr]', $str_attributes, $output);
		$output = str_replace('[:label]', __($this->config['label']), $output);
		$output = str_replace('[:field]', $field, $output);
		$output = str_replace('[:id]', $this->config['id'], $output);
		return $output;
	}
	
	public function __call($name, array $argument){
		if ($name == '') $name = 'label';
		if (count($argument) == 0){
			if (array_key_exists($name, $this->config)){
				return $this->config[$name];
			}
		}else{
			$this->config[$name] = $argument[0];
		}
		return $this;
	}
}