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

abstract class Formality_Form{
	public $config;

	public $field;
	public $fieldset = Array();
	public $verify;
	public $errorbox;
	const VERSION = "0.1";
		
	/**
	 * Formality_Form::__construct()
	 * 
	 * @return object Formality_Form
	 */

	public function __construct($config = Array()){
		//$this->parent = $parent;
		$this->config = Formality::config('lib.form._default');
		foreach($config as $key => $val){
			$this->__call($key,Array($val));			
		}
		$this->errorbox = new Formality_Container(Array( 'id' => 'errorbox_' . $config['id']), $this);
	}

	/**
	 * Formality_Form::factory()
	 * 
	 * @param mixed $id
	 * @param bool $action
	 * @return object Formality_Form
	 */
	public static function factory($id, $config = Array(), $action=false){
		if (empty($action)) $action = $_SERVER['REQUEST_URI'];
		$config['action'] = $action;
		if (!isset($config['id'])) $config['id'] = $id;

		$form = new Formality($config);

		if ($form_config = Formality::config('lib.form.' . $id)){
			foreach($form_config as $key => $val){
				$form->__call($key,Array($val));			
			}			
		}
		
		return $form;
	}


	/**
	 * Formality_Form::has_upload()
	 * 
	 * @return bool
	 */
	protected function has_upload(){
		if (empty($this->config['field'])){
			throw new Exception('Config.field is missing.');	
		}
		foreach($this->config['field'] as $v) if (!empty($v['type']) && $v['type'] == 'file') return true;
		return false;
	}

	/**
	 * Formality_Form::parse()
	 * 
	 * @param bool $reload
	 * @return null
	 */
	public function parse($reload=false, $keep_loaded_values=false){
		if ($keep_loaded_values){
			$this->load($this->save());
		}
		if (!empty($this->field) && !$reload) return $this;
		foreach($this->config['field'] as $id=>$v){
			if ($reload == false && !empty($this->field[$id])) continue; // ISSUE
			if (!is_array($v) && is_numeric($id)){
				$id = $v;
				$v = Array();
			}
			$this->field[$id] = Formality_Field::factory($id, $v, $this);
		}
		
		foreach($this->config['fieldset'] as $id=>&$v){
			if ($reload == false && !empty($this->fieldset[$id])) continue; // ISSUE
			$v['id'] = $id;
			$fieldset = new Formality_Fieldset($v, $this); 
			$this->fieldset[$id] = $fieldset;
		}
		if (isset($this->config['errorbox'])){
			foreach($this->config['errorbox'] as $id=>&$v){
				$this->errorbox->__call($id,Array($v));
			}
		}
		
		return $this;
	}

	/**
	 * Formality_Form::form_start()
	 * 
	 * @return string
	 */
	public function form_start(){
		if ($this->has_upload()) $this->config['enctype'] = 'multipart/form-data';
		$str_attributes = '';
		foreach($this->config['attributes'] as $key){
			if (array_key_exists($key,$this->config)){
				$value = $this->config[$key];
				if (is_array($value)) $value = implode(' ', $value);
				$str_attributes .= " $key=\"$value\"";
			}
		}
		$output = $this->config['html']['start'];
		$output = str_replace('[:id]', $this->config['id'], $output);
		$output = str_replace('[:attr]', $str_attributes, $output);

		$err = '';
		if (isset($this->errorbox->config['err'])){
			$err = $this->errorbox->render();
		}
		$output = str_replace('[:errorbox]', $err, $output);
		
		return $output;		
	}

	/**
	 * Formality_Form::form_end()
	 * 
	 * @return string
	 */
	public function form_end(){
		return $this->config['html']['end'];
	}
	
	/**
	 * Formality_Form::render()
	 * 
	 * @return string
	 */
	public function render($fieldset = false){
		if (empty($this->config['fieldset'])){
			throw new Exception('Config.fieldset is missing.');	
		}
		$this->parse();

		if (!$fieldset){
			$html = $this->form_start();
			foreach($this->fieldset as $fs){
				$html .= $fs->render();
			}
			$html .= $this->form_end();
		} else {
			if (empty($this->config['fieldset'])){
				throw new Exception("Config.fieldset.$fieldset is missing.");	
			}
			$html = $this->fieldset[$fieldset]->render();
		} 

		return $html;
	}
	
	/**
	 * Formality_Form::process()
	 * 
	 * @return string
	 */
	public function process(){
		if (empty($_POST["form_{$this->config['id']}"])){
			return $this->render();
		}else{
			if (empty($this->config['fieldset'])){
				throw new Exception('Config.fieldset is missing.');	
			}
			$this->parse();

			foreach($this->fieldset as $fs){
				$fs->pull();
			}
			if (!$this->validate()){
				return $this->render();
			}
			
			if (is_callable($this->verify) && $func = $this->verify){

			    try{
					$err = $func($this);
				} catch (ORM_Validation_Exception $e){
			        $errors = $e->errors();
					$current = current($errors);
					$this->errorbox->err(key($errors) . ': ' . $current[0]);
			        foreach($errors as $k => $error){
			        	if (isset($this->field[$k])){
			        		$this->field[$k]->container->config['err']= $error[0];
			        	}
//			        	var_dump($k, $error);
			        }
					return $this->render();
			    }

				if ($err !== true){
					$this->errorbox->err($err);
					return $this->render();
				}
			}
			
			return true;
		}
	}	

	public function validate(){
		$val = true;
		foreach($this->fieldset as $fs){
			if (!$fs->validate()) $val = false;
		}
		return $val;
	}

  /**
   * Formality_Form::load_assoc_array()
   *
   * @param Array $values
   * @return void
   */
	public function load(Array $values){
		foreach($values as $n_val => $v_val){
			Formality::path_set($this->config, "field.$n_val.value", $v_val, false);
		}
		return $this;
	}

  /**
   * Formality_Form::save_assoc_array()
   *
   * @return Array
   */
	public function save($fields = false){
		$r = Array();
		
		foreach($this->field as $n_field => &$v_field){
			if (!is_array($fields) || in_array($n_field, $fields)){
				$r[$n_field] = $v_field->value();
			}
		}
		if (is_array($fields)){
			return $r;
		}elseif ($fields == false){
			return $r;
		} elseif (array_key_exists($fields,$r)) {
			return $r[$fields];
		}
	}	

	
	/**
	 * Formality_Form::toString()
	 * 
	 * @return string
	 */
	public function toString(){
		return $this->render();
	}
		
	public function __call($name, array $argument){
		$name = str_replace('_', '.', $name);
		$parts = explode('.', $name);
//		if (substr($parts[0],0, 5) == 'field'){
//			if (array_key_exists(1, $parts) && array_key_exists($parts[1], $this->field)){
//				$this->field[$parts[1]]->set(substr($name,strlen($parts[0] . '.' . $parts[1])),$argument[0]);
//			}
//		}
		Formality::path_set($this->config, $name, $argument[0],true);
		return $this;
	}

	public static function load_config(){
		
	}

	public static function path($array, $path, $default = NULL){
		// Split the keys by slashes
		$keys = explode('.', $path);

		do{
			$key = array_shift($keys);

			if (ctype_digit($key)){
				// Make the key an integer
				$key = (int) $key;
			}

			if (isset($array[$key])){
				if ($keys){
					if (is_array($array[$key])){
						// Dig down into the next part of the path
						$array = $array[$key];
					}else{
						// Unable to dig deeper
						break;
					}
				}else{
					// Found the path requested
					return $array[$key];
				}
			}else{
				// Unable to dig deeper
				break;
			}
		}
		while ($keys);

		// Unable to find the value requested
		return $default;
	}

	public static function path_set(&$array, $path, $value = NULL,$merge = false){
		// Split the keys by slashes
		$keys = explode('.', $path);

		do{
			$key = array_shift($keys);

			if (ctype_digit($key)){
				// Make the key an integer
				$key = (int) $key;
			}

			if (isset($array[$key])) {
				if ($keys){
					if (is_array($array[$key])){
						// Dig down into the next part of the path
						$array = &$array[$key];
					} else {
						// Unable to dig deeper
						break;
					}
				} else {
					// Found the path requested
					if ($merge){
						//$array = self::array_merge_recursive_unique($array,Array($key => $value));
						$array = array_merge_recursive($array, Array($key => $value));
					} else {
						$array[$key] = $value;
					}
				}
			}
			else{
				if (count($keys) > 0){
					$array[$key] = Array();
					$array = &$array[$key];
				} else{
					$array[$key] = $value;	
					break;
				};
			}
		}
		while ($keys);
		return false;
	}

	public static function array_merge_recursive_unique($array0, $array1){
	    $arrays = func_get_args();
	    $remains = $arrays;
	
	    // We walk through each arrays and put value in the results (without
	    // considering previous value).
	    $result = array();
	
	    // loop available array
	    foreach($arrays as $array) {
	
	        // The first remaining array is $array. We are processing it. So
	        // we remove it from remaing arrays.
	        array_shift($remains);
	
	        // We don't care non array param, like array_merge since PHP 5.0.
	        if(is_array($array)) {
	            // Loop values
	            foreach($array as $key => $value) {
	                if(is_array($value)) {
	                    // we gather all remaining arrays that have such key available
	                    $args = array();
	                    foreach($remains as $remain) {
	                        if(array_key_exists($key, $remain)) {
	                            array_push($args, $remain[$key]);
	                        }
	                    }
	
	                    if(count($args) > 2) {
	                        // put the recursion
	                        $result[$key] = call_user_func_array(__FUNCTION__, $args);
	                    } else {
	                        foreach($value as $vkey => $vval) {
	                            $result[$key][$vkey] = $vval;
	                        }
	                    }
	                } else {
	                    // simply put the value
	                    $result[$key] = $value;
	                }
	            }
	        }
	    }
	    return $result;
	}
}

