<?php
//Kohana 3.0 Class

class Formality extends Formality_Form{
	public static function config($key){
		return Kohana::config("formality.$key");
	}
}