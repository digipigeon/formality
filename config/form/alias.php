<?php

return Array(
	'fieldset'	=> Array(
		'basic' => Array(
			'legend' => 'Alias',
			'fields' => Array('name','dest_type','destination', 'save')
		)
	),
	'field' => Array(
		'id' => Array(
			'label' => 'ID',
			'type'	=> 'select',
			'list'	=> Array()
		),
		'name' => Array(
			'label' => 'Name',
		),
		'dest_type' => Array(
			'label' => 'Type',
			'type'	=> 'select',
			'list'	=> Array('SIP URI','External Number','Internal Number')						
		),
		'destination' => Array(
			'label' => 'Destination',
		),
		'save'
	),
);