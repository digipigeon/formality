<?php
 
class Formality_Field_Codemirror extends Formality_Field{
	public function __construct($config = Array(), &$parent = false){
		parent::__construct($config, $parent);
	}	

	public function render($label=true, $container=true){
		HTML::add_file('/js-back/codemirror.js');
		HTML::add_file('/css-back/codemirror.css','css');
		HTML::add_js('
			if (!editor){	
				var editor = CodeMirror.fromTextArea(document.getElementById("' . $this->config['id'] . '"), {
					lineNumbers: true,
					matchBrackets: true,
					mode: "application/x-httpd-php",
					indentUnit: 4,
					indentWithTabs: true
				});
			}
		');
		return parent::render($label, $container);	
	}
}