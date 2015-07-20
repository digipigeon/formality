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
 
class Formality_Field_Select extends Formality_Field{

	public function render($label=true, $container=true){
		$str_attributes = '';
		foreach($this->config['attributes'] as $key){
			if (array_key_exists($key,$this->config)){
				if (!is_array($this->config[$key])){
					$str_attributes .= " $key=\"{$this->config[$key]}\"";
				}
			}
		}

		$output = $this->config['html'];
		$output = str_replace('[:attr]',	$str_attributes, $output);
		$output = str_replace('[:name]',	$this->config['id'] . (!empty($this->config['multiple']) ? '[]' : ''), $output);
		$output = str_replace('[:value]',	$this->content(), $output);
		foreach($this->config as $k => $v){
			if ($k == 'value') continue;
			if (!is_string($v)) continue;
			$output = str_replace("[:$k]",	$v,	$output);
		}

		if ($label && !empty($this->label->config['label'])) $output = $this->label->render($output);
		if ($container) $output = $this->container->render($output);
		return $output;
	}
	
	protected function content(){
		$html = '';
		if (!empty($this->config['flatten']) && is_string($this->config['value'])){
			$this->config['value'] = explode(',', $this->config['value']);
		}
		foreach($this->auto_array($this->config['list']) as $item){
			$prop = '';
			$selected = false;
			if (isset($item["group"])){
				$html .= "<optgroup label='{$item["label"]}'></option>";
				continue;
			}
			if (isset($this->config["value"])){
				if (!empty($this->config['multiple'])){
					$selected = in_array($item["value"], $this->config["value"]);
				}else{
					$selected = $this->config["value"] == $item["value"];
				}
			}

			if (!empty($item['disabled'])) $prop .= "disabled='disabled' ";
			if ($selected){
				$prop .= "selected='selected' ";
			}

			foreach($item as $k => $v){
				if (substr($k,0,5) == 'data-'){
					$prop .= " $k=\"$v\"";
				}
			}		


			$html .= "<option $prop value='{$item["value"]}'>{$item["label"]}</option>";
		}
		return $html;
	}

	public function pull(){
		if (!empty($this->config['multiple'])){
			if (!isset($_POST[$this->config['id']])) $_POST[$this->config['id']] = Array();
			$val = $_POST[$this->config['id']];
			if (is_string($val)){
				if (!isset($this->config['xss']) || $this->config['xss']){
					$val = $this->xss_clean($val);
				}
			}			
			
			$this->__call('value', Array($val));
			if (!empty($this->config['flatten']) && is_array($this->config['value'])){
				$this->config['value'] = implode(',', $this->config['value']);
			}
		} else {
			return parent::pull();
		}
		
	}

}
	