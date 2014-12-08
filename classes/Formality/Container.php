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

class Formality_Container{
	public $config;
	public $parent;
	
	public function __construct($config = Array(), &$parent = false){
		$this->parent = $parent;
		$this->config = array_merge($config, Formality::config('lib.container._default'));
		if (!empty($config['container'])){
			$this->config = array_merge($this->config, $config['container']);
		}
		if ($this->config['id']){
			$this->config['id'] = str_replace('[:id]', $this->config['id'], $this->config['idmask']);
		}
	}
	
	public static function factory($config = Array(), $parent=false){		
		$field = new Formality_Container($config, $parent);
		return $field;
	}

	public function render($content = ''){
		$str_attributes = '';
		$err = '';		
		if ($content == '' && !empty($this->config['content'])) $content = $this->config['content'];
		foreach($this->config['attributes'] as $key){
			if (array_key_exists($key,$this->config)){
				$str_attributes .= " $key=\"{$this->config[$key]}\"";
			}
		}

		if (!empty($this->config['err'])) $err = $this->config['err'];
		
		$output = $this->config['html'];
		$output = str_replace('[:attr]', $str_attributes, $output);
		if (!is_array($err)){ // Temporary Fix
			$output = str_replace('[:err]', $err, $output);
		} else {
			$output = str_replace('[:err]', '', $output);
		}
		if ($this->parent instanceof Formality_field){
			$output = str_replace('[:label]', $this->parent->label->label(), $output);
		}
		$output = str_replace('[:field]', $content, $output);
		return $output;
	}
	
	public function __call($name, array $argument){
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