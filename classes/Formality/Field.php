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
 
class Formality_Field{
	public $parent;
	public $id;
	public $attributes = Array();	

	public $div;
	public $label;
	public $container;
	public $config;
	public $validate = Array();
	
	/**
	 * Formality_Field::__construct()
	 * 
	 * @param mixed $id
	 * @param mixed $prop
	 * @param bool $parent
	 * @return object Formality_Field
	 */
	public function __construct($config = Array(), &$parent = false){
		$this->parent = &$parent;	

		$this->container = Formality_Container::factory($config, $this);
		$this->label = Formality_Label::factory($config['id'], Array(), $this);
		//Todo: This will need to be changed to allow modification of the default
		$this->validate['default'] = Formality_Validate::factory(Array(), $this);

		//$this->label->config['label'] = $this->config['label'];

		foreach(Formality::config('lib.field._default') as $key => $val){
			$this->__call($key,Array($val));			
		}

		if (array_key_exists('type', $config) && $type_template = Formality::config('lib.field._' . $config['type'])){
			foreach($type_template as $key => $val){
				$this->__call($key,Array($val));			
			}
		}

		foreach($config as $key => $val){
			$this->__call($key,Array($val));
		}
		if (empty($config['name'])) $this->config['name'] = $this->config['id'];
	}
	
	/**
	 * Formality_Field::factory()
	 * 
	 * @param mixed $id
	 * @param mixed $prop
	 * @param bool $parent
	 * @return object Formality_Field
	 */
	public static function factory($id, $config = Array(),&$parent = false){
		$template_id = $id;
		if (array_key_exists('template', $config)) $template_id = $config['template'];
		$template_config = Formality::config('lib.field.' . $template_id);

		if (!empty($template_config)){
			$config = Formality_Form::array_merge_recursive_unique($template_config, $config);
			//$config = array_merge($template_config, $config);			
			unset($config['template']);
		}

		$config['id'] = $id;
		if (!empty($config['type'])){
			$type = $config['type'];
			$class = "Formality_Field_$type";

			if (class_exists($class)) return new $class($config, $parent);			
		}
		return new Formality_Field($config, $parent);
	}

	/**
	 * Formality_Field::toString()
	 * 
	 * @return string
	 */
	public function toString(){
		return $this->render();
	}
	
	/**
	 * Formality_Field::render()
	 * 
	 * @return string
	 */
	public function render($label=true, $container=true){
		$str_attributes = '';
		foreach($this->config['attributes'] as $key){
			if (array_key_exists($key,$this->config)){
				$str_attributes .= " $key=\"{$this->config[$key]}\"";
			}
		}
		$output = $this->config['html'];
		$output = str_replace('[:attr]',	$str_attributes,		$output);
//		$output = str_replace('[:id]',		$this->config['id'],	 $output);

		if (!empty($this->config['htmlspecialchars'])){
			$value = htmlspecialchars($this->config['value']);
			$output = str_replace('[:value]', $value, $output);
//			die($output);
		} else {
			$output = str_replace('[:value]', $this->config['value'], $output);
		}
		
		foreach($this->config as $k => $v){
			if (!is_string($v)) continue;
			$output = str_replace("[:$k]",	$v,	$output);
		}

		if ($label && !empty($this->label->config['label'])) $output = $this->label->render($output);
		if ($container) $output = $this->container->render($output);
		return $output;
	}
	
	public function pull(){
		if (!isset($_POST[$this->config['id']])) return null;
		$this->__call('value', Array($_POST[$this->config['id']]));
	}
	
	public function validate(){
		$val = true;
		foreach($this->validate as &$validate){
			if (is_callable($validate) && is_object($validate)){
				//Dealing with a closure
				if (!Formality_Validate::closure($this, $validate, $this->config['value'])) $val = false;
			} else {
				if (!$validate->check($this->config['value'])) $val = false;
			}
		}
		return $val;
	}
	
	public function __call($name, array $argument){
		$name = str_replace('_', '.', $name);
		if (substr($name,0, 9) == 'container'){
			$this->container->__call(substr($name, 10), Array($argument[0]));
		}elseif (substr($name,0, 5) == 'label'){
			$this->label->__call(substr($name, 6), Array($argument[0]));
		}elseif (substr($name,0, 8) == 'validate'){
			$key = substr($name,9); 
			if ($key == ''){ // substr($name,8) = Validation Class Name
				//We are dealing with a general we need to check and look into $arguement[0]
				if (is_array($argument[0])){
					foreach($argument[0] as $k => $v){
						$this->__call($name . '.' . $k, Array($v));
					}
				}
			}else{
				$key_array = explode('.', $key);
				if (count($key_array) == 1){
					if (is_string($argument[0]) && class_exists($argument[0],true)){
						//Dealing with a Class	
						$class_name = $argument[0];
						$arr = Array();
						$this->validate[$key] = new $class_name($arr, $this);
						return $this;
					}if (is_object($argument[0])){
						// Dealing with a Closure Function
						$this->validate[$key] = $argument[0];
						return $this;
					}
					$setting_name = $key_array[0];
				} else {
					$setting_name = ((count($key_array) > 1) ? $key_array[1] : '');
				}
				//We are dealing with setting a value
				$setting_class = array_key_exists($key_array[0], $this->validate) ? $key_array[0] : 'default';
//				var_dump($setting_class);
				$this->validate[$setting_class]->__call($setting_name, $argument);
			}
		}else{
			if (count($argument) == 0){
				if (array_key_exists($name, $this->config)){
					return $this->config[$name];
				}
			}else{
				$this->config[$name] = $argument[0];
			}
		}
		return $this;
	}
	
	protected function auto_array($array){
		if (!empty($array[0]) && is_array($array[0]) && key_exists('label',$array[0])){
			return $array;
		}elseif (key_exists(0,$array)){
			$na = Array();
			foreach($array as $item) $na[] = Array('label'=>$item,'value'=>$item);
			return $na;
		}else{
			$na = Array();
			foreach($array as $n=>$v) $na[] = Array('label'=>$v,'value'=>$n);
			return $na;
		}
	}	
	
	public function template(){
		throw new Exception('Can not set template after instantiation.');	
	}
}