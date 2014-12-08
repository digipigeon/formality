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

class Formality_Fieldset{
	public $config;
	public $parent;
	
	public function __construct($config = Array(), &$parent = false){
		$this->parent = &$parent;
		$this->config = array_merge(Formality::config('lib.fieldset._default'), $config);
	}
	
	public static function factory($config = Array(), $parent=false){		
		$field = new Formality_Fieldset($config, $parent);
		return $field;
	}
	
	public function render($content=false){
		$str_attributes = '';
		foreach($this->config['attributes'] as $key){
			if (array_key_exists($key,$this->config)){
				$str_attributes .= " $key=\"{$this->config[$key]}\"";
			}
		}

		if (!$content && $this->parent){
			foreach($this->config['fields'] as $key){
				if (empty($this->parent->field[$key])) throw new Exception("Field '$key' not found");
				$content .= $this->parent->field[$key]->render();
			}
		}
		
		$output = $this->config['html'];
		$output = str_replace('[:id]', $this->config['id'], $output);
		$output = str_replace('[:attr]', $str_attributes, $output);
		if (empty($this->config['legend'])){
			$output = str_replace('<legend>[:legend]</legend>', '', $output);
		} else {
			$output = str_replace('[:legend]', $this->config['legend'], $output);
		}
		$output = str_replace('[:content]', $content, $output);
		return $output;		
	}

	public function pull($content=false){
		foreach($this->config['fields'] as $key){
			if (empty($this->parent->field[$key])) throw new Exception("Field '$key' not found");
			$this->parent->field[$key]->pull();
		}
	}
	
	public function validate(){
		$val = true;
		foreach($this->config['fields'] as $key){
			if (empty($this->parent->field[$key])) throw new Exception("Field '$key' not found");
			if (!$this->parent->field[$key]->validate()) $val = false;
		}		
		return $val;
	}

	
	public function __call($name, array $argument){
		$this->config[$name] = $argument[0];
		return $this;
	}
}