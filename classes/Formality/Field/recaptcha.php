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
 * @copyright  2005-2011 Digipigeon Limited
 * @license    http://creativecommons.org/licenses/by/3.0/  Creative Commons Attribution 3.0 Unported Licens
 * @version    SVN: $Id$
 * @link       http://www.digipigeon.com/formality
 */
 
class Formality_Field_Recaptcha extends Formality_Field{
	protected $local_fieldset;

//	public function __construct(){
//		$this->validate[] = 
//		parent::__construct();
//	}
//	
	public function validate(){
		if (!isset($_POST['g-recaptcha-response'])) return false;

		$request = Request::factory("https://www.google.com/recaptcha/api/siteverify");			
		$request->post('secret', $this->config['secret']);
		$request->post('response', $_POST['g-recaptcha-response']);
		$request->post('remoteip', $_SERVER['REMOTE_ADDR']);
		$request->method('POST');

		$response = $request->execute();

		$data_res = json_decode($response,true);
		
		return $data_res['success'];
	}
	
	public function render($label=true, $container=true){
		HTML::add_file('https://www.google.com/recaptcha/api.js');
		return "<div class=\"g-recaptcha\" data-sitekey=\"" . $this->config['sitekey'] . "\"></div>";
	}
	
}
	