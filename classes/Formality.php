<?php
//Kohana 3.0 Class

class Formality extends Formality_Form{
	public static function config($key){
		if (substr($key,0,8) == 'lib.form'){
			$config = Kohana::$config->load("formality.$key");
			if (!empty($config)) return $config;
			return Kohana::$config->load("form/" . substr($key,9));
		}else{
			return Kohana::$config->load("formality.$key");
		}
	}
	
	public static function orm($orm, $values=false){
		$id = substr(strtolower(get_class($orm)),6);
		$config = Array(
			'id'	=> $id,
			'fieldset'	=> Array(
				'basic' => Array(
					'legend' => 'Basic Information',
					'fields' => Array('save')
				)
			),
			'field' => Array(
				'save'
			),
		);
		$fields = Array();
		foreach ($orm->list_columns() as $key => $col){
			$col_config = Array();
			$col_config['label'] = ucwords(str_replace('_',' ',$key));
			$fields[] = $key;
			switch($col['data_type']){
				case 'varchar':
//					$col_config['type'] = 'tet'
//				'minval' => $col['min'],
					$col_config['validate'] = Array(
						'maxlen' => $col['character_maximum_length'],
					);
					break;
				case 'integer':
					$col_config['validate'] = Array(
						'minval' => $col['min'],
						'maxval' => $col['max'],
					);
					break;
			}
			$config['field'][$key] = $col_config;
		};
		$config['fieldset']['basic']['fields'] = array_merge($fields,$config['fieldset']['basic']['fields']);
		return $config;
	}
}