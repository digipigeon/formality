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
 
class Formality_Field_Radio extends Formality_Field{
	protected $local_fieldset;

	public function render($label=true, $container=true){
		$str_attributes = '';
		foreach($this->config['attributes'] as $key){
			if (array_key_exists($key,$this->config)){
				$str_attributes .= " $key=\"{$this->config[$key]}\"";
			}
		}
		
		$local_fieldset = new Formality_Fieldset(Array('id' => $this->config['id'] . '_fieldset'));
		
		$output = $this->config['html'];
		$output = str_replace('[:attr]',	$str_attributes,		$output);
		$output = str_replace('[:id]',		$this->config['id'],	 $output);
		$output = str_replace('[:value]',	$local_fieldset->render($this->content()),	$output);

		if ($label && !empty($this->label->config['label'])) $output = $this->label->render($output);
		if ($container) $output = $this->container->render($output);
		return $output;
	}
	
	protected function content(){
		$html = '';
		foreach($this->auto_array($this->config['list']) as $item){
			$prop = '';
			$selected = false;
			if (isset($this->config["value"])){
				$selected = $this->config["value"] == $item["value"];
			}

			if (key_exists('disabled', $item)) $prop .= "disabled='disabled' ";
			if ($selected){
				$prop .= "checked='checked' ";
			}
			$html .= "<label><input name='{$this->config['id']}' type='radio' $prop value='{$item["value"]}' />{$item["label"]}</label>";
		}
		return $html;
	}
}
	