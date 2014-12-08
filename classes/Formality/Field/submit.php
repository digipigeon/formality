<?php

/**
 * Formality - HTML5 Compatible PHP 5.3 form manipulation.
 *
 * You are free:
 *  to Share � to copy, distribute and transmit the work
 *  to Remix � to adapt the work
 *
 * Under the following conditions:
 *
 * Attribution � You must attribute the work in the manner specified by the author or licensor
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
 
class Formality_Field_Submit extends Formality_Field{
	public function __construct($config = Array(), &$parent = false){
		
		/*
			The display of a submit button behaves more like a label,
			So we will set the value to the label here
			This will also avoid the blank label issue on submit
			
			An overwrite value might be added in the future
		*/
		
		$config['value'] = $config['label'];
		$config['label'] = '';

		parent::__construct($config, $parent);
	}
	
	public function clicked(){
		return array_key_exists($this->config['id'], $_POST);
	}
}
	