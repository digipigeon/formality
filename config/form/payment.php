<?php

return Array(
	'fieldset' => Array(
		'fs_detail' => Array(
			'legend' => 'New Payment',
			'fields' => Array('desc', 'amount', 'notify','save')
		)
	),
	'field' => Array(
		'amount' => array(
			'label' => 'Amount',
			'validate' => Array(
				'numeric'	=> true,
				'minlen'	=> 1
			)
		),
		'desc' => Array(
			'label'		=> 'Description',
			'default'	=> 'Account Credit',
		),
		'notify' => Array(
//			'attributes' => Array('id','class','style','value','type','data-role'),
//			'data-role' => 'controlgroup',
			'label' => 'Notify Customer',
			'type'	=> 'checkbox',
			'simple'	=> true,
		),
		'save'
	)
);