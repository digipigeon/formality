<?php

return Array(
	'lib' => Array(
		'field'		=> Array(
			'_default'	=> Array(
				'attributes' => Array('id','class','style','value','type','maxlength','readonly','disabled','placeholder','autocomplete'),
				// %1$s represents id
				// %2$s represents name
				// %3$s represents attributes
				'html' => '<input id="[:id]" name="[:id]" value="[:value]"[:attr]/>',
				'type' => 'text',
				'label' => 'Default Label',
				'value' => '',
			),
			'_hidden'	=> Array(
				'attributes' => Array('id','value','type'),
				// %1$s represents id
				// %2$s represents name
				// %3$s represents attributes
				'html' => '<input id="[:id]" name="[:id]" value="[:value]"[:attr]/>',
				'label' => '',
				'value' => '',
			),
			'_select'	=> Array(
				'attributes' => Array('id','class','style','value','type','readonly','disabled','multiple'),
				// %1$s represents id
				// %2$s represents name
				// %3$s represents attributes
				'html' => '<select id="[:id]" name="[:name]"[:attr]>[:value]</select>',
				'label' => 'Default Label',
				'value' => '',
			),
			'_radio'	=> Array(
				'attributes' => Array('id','class','style','value','type'),
				// %1$s represents id
				// %2$s represents name
				// %3$s represents attributes
				'html' => '[:value]',
				'label' => 'Radio Buttons',
				'value' => '',
			),
			'_checkbox'	=> Array(
				'attributes' => Array('id','class','style','value','type'),
				// %1$s represents id
				// %2$s represents name
				// %3$s represents attributes
				'html' => '[:value]',
				'label' => 'Checkboxes',
				'value' => '',
			),
			'_textarea'	=> Array(
				'attributes' => Array('id','class','style','type','maxlength','readonly','disabled','placeholder'),
				'html' => '<textarea id="[:id]" name="[:id]"[:attr]/>[:value]</textarea>',
				'type' => 'textarea',
				'label' => 'Default Label',
				'value' => '',
			),
			'_file'	=> Array(
				'attributes' => Array('id','class','style','accept','type'),
				'html' => '<input id="[:id]" name="[:id]" value="[:value]"[:attr]/>',
				'type' => 'file',
				'label' => 'File Upload',
				'value' => '',
			),
			'_codemirror'	=> Array(
				'attributes' => Array('id','class','style','type','maxlength','readonly','disabled','placeholder'),
				'html' => '<textarea id="[:id]" name="[:id]"[:attr]/>[:value]</textarea>',
				'type' => 'textarea',
				'label' => 'Default Label',
				'value' => '',
			),
			'username'	=> Array(
				'label' => 'Username',
				'validate' => Array(
					'minlen' => 5,
				)
			),
			'email'	=> Array(
				'label' => 'Email',
				'validate' => Array(
					'regex' => '/^(?:(?#local-part)(?#quoted)"[^\"]*"|(?#non-quoted)[a-z0-9&+_-](?:\.?[a-z0-9&+_-]+)*)@(?:(?#domain)(?#domain-name)[a-z0-9](?:[a-z0-9-]*[a-z0-9])*(?:\.[a-z0-9](?:[a-z0-9-]*[a-z0-9])*)*|(?#ip)(\[)?(?:[01]?\d?\d|2[0-4]\d|25[0-5])(?:\.(?:[01]?\d?\d|2[0-4]\d|25[0-5])){3}(?(1)\]|))$/',
				)
			),
			'date'	=> Array(
				'label'	=> 'Date',
				'validate' => Array(
					'regex' => '/^(\d{2}|\d{4})(?:\-)([0]\d|1[0-2])(?:\-)([0-2]\d|[3][0-1])((?:\s)?([0-1]\d|[2][0-3])(?::)([0-5]\d)((?::)([0-5]\d))?)?$/',
				)				
			),
			'password'	 => Array(
				'label' => 'Password',
				'type' => 'password'
			),
			'confirm_password'	 => Array(
				'label' => 'confirm_password',
				'type' => 'password'
			),
			'amount'	 => Array(
				'label' => 'Payment Amount',
			),
			'save'	=> Array(
				'type' => 'submit',
				'label' => 'Save',
				'class'	=> 'btn btn-primary btn-large'
			),
			'delete'	=> Array(
				'class'	=> ' btn btn-danger btn-large',
				'type' => 'submit',
				'label' => 'Delete',
			),
		),
		'fieldset'	=> Array(
			'_default'	=> Array(
				'attributes' => Array('id','class','style'),
				'html'		=> '<fieldset[:attr]><legend>[:legend]</legend>[:content]</fieldset>',
				'fields'	=> Array(),
				'legend'	=> '',
			),
		),
		'form'	=> Array(
			'_default'	=> Array(
				'attributes' => Array('id', 'class', 'style', 'method', 'enctype','action'),
				'method' => 'post',
				'html'	=> Array(
					// %1$s represents id
					// %2$s represents attributes
					'start'	=> '<form[:attr]><div style="display:none"><input name="form_[:id]" type="hidden" value="1" /></div>[:errorbox]',
					'end'	=> '</form>',
				),
				'class'	=> 'form-horizontal'
			),
		),
		'container'	=> Array(
			'_default'	=> Array(
				'attributes' => Array('id', 'class', 'style'),
				'idmask' => '[:id]_div',
				'html' => '<div[:attr]>[:field][:err]</div>',
				'class'	=> 'control-group'
			),
		),
		'label'	=> Array(
			'_default'	=> Array(
				'attributes' => Array('id', 'class', 'style'),
				'idmask' => '[:id]_label',
				'html' => '<label for="[:id]"[:attr]>[:label]:</label>[:field]',
				'class'	=> 'control-label'
			),
		),
		'validate'		=> Array(
			'_default'	=> Array(
				'err'	=> Array(
					'_default'	=> '[:label] failed validation'
				),
				'errpos'	=> Array('top','inline'),
				'mask'		=> Array(
					'top'		=> '<span class="help-inline"><i class="icon-exclamation-sign"></i> [:err]</span>',
					'inline'	=> '<span class="help-inline"><i class="icon-exclamation-sign"></i> [:err]</span>',
				)
			),
		),
		'verify' => Array(
			'_default' => Array(
				'err' => Array(
					'Failed Validation'
				),
				'errpos'	=> Array('top'),
				'mask'		=> Array(
					'top'		=> '<div class="error">[:err]</div>',
				),
			),
		),
	),
	'form' => Array(),
	'field' => Array(),
);
