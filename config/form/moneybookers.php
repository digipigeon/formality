<?php

return Array(
	'action'	=> 'https://www.moneybookers.com/app/pay.pl',
	'fieldset' => Array(
		'details'	=> Array(
			'legend'	=> 'Details',
			'fields'	=> Array(
				'pay_to_email',
				'transaction_id',
				'return_url',
				'cancel_url',
				'status_url',
				'language',
				'amount',
				'currency',
				'detail1_description',
				'detail1_text',
				'confirmation_note',
				'submit'
			),
		),
	),
	'field' => Array(
		'pay_to_email' => Array(
			'label'		=> 'Email Address',
			'template'	=> 'email'
		),
		'transaction_id'	=> Array(
			'type'	=> 'hidden'
		),
		'return_url'	=> Array(
			'type'	=> 'hidden'
		),
		'cancel_url'	=> Array(
			'type'	=> 'hidden'
		),
		'status_url'	=> Array(
			'type'	=> 'hidden'
		),
		'language'	=> Array(
			'type'	=> 'hidden'
		),
		'amount'	=> Array(
			'type'	=> 'text',
			'validate'	=> Array(
				'numeric'	=> true,
			)
		),
		'currency'	=> Array(
			'type'	=> 'hidden'
		),
		'detail1_description'	=> Array(
			'type'	=> 'hidden'
		),
		'detail1_text'	=> Array(
			'type'	=> 'hidden'
		),
		'confirmation_note'	=> Array(
			'type'	=> 'hidden'
		),
		'submit'	=> Array(
			'label'	=> 'Pay!',
			'type'	=> 'submit'
		)

	),
);