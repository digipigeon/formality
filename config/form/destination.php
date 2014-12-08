<?php

return Array(
	'fieldset'	=> Array(
		'destination' => Array(
			'legend' => 'Edit Destination',
			'fields' => Array('name','regex','capped_rate','save')
		)
	),
	'field' => Array(
		'name' => Array(
			'label' => 'Name'
		),
		'capped_rate' => Array(
			'label' => 'Capped Rate (GBP)',
			'validate' => Array(
				'isnumeric' => 'number',
			)
		),
		'regex' => Array(
			'label' => 'Regex Rule',
			'validate' => Array(
				'regexval' => 'Formality_Validate_Regexval',
			)
		),
		'save'
	),
);