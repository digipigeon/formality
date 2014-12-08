<?php

return Array(
	'fieldset'	=> Array(
		'basic' => Array(
			'legend' => 'Wiki',
			'fields' => Array('key', 'title', 'body', 'author', 'save')
		)
	),
	'field' => Array(
		'key' => Array(
			'label' => 'Key'
		),
		'title' => Array(
			'label' => 'Title'
		),
		'body' => Array(
			'label' => 'Body',
			'type'	=> 'textarea'
		),
		'author' => Array(
			'label' => 'Author'
		),
		'save'	=> Array(
			'label'	=> 'Save'
		)
	),
);