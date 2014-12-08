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
 
class Formality_Field_Captcha extends Formality_Field{
	public function render($label=true, $container=true){
		$captcha = Captcha::instance();
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
		$output = $captcha->render() . $output;

		if ($label && !empty($this->label->config['label'])) $output = $this->label->render($output);
		if ($container) $output = $this->container->render($output);
		return $output;

	}

	public function validate(){
		if (Captcha::valid($this->config['value'])) return true;
		$this->container->err('Captcha Failed validation');
		return false;
	}
}