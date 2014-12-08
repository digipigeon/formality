<?php

return Array(
	'fieldset' => Array(
		'fs_detail' => Array(
			'legend' => 'Details',
			'fields' => Array('amount','save')
		)
	),
	'field' => Array('amount' => array(
		'validate' => Array(
			'numeric'	=> true,
			'minlen'	=> 1
		)),
		'save'
	)
);