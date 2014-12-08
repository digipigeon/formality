<?php
include('../examples/standalone.php');
?>
<html>
	<head>
		<title>Formality::Form Unit Tests</title>
		<style type="text/css">
			@import "../css/reset.css";
			@import "../css/form.css";
			@import "../css/screen.css";
		</style>
	</head>
	<body>
		<h1>Field Types</h1>
		<?php
			echo Formality_Form::factory('form1', Array(
				'fieldset' => Array(
					'fieldset1' => Array(
						'legend' => 'Fieldset 1',
						'fields' => Array('field1','field2','field3','field4','field5','field6','field7','field10')
					)
				),
				'field' => Array(
					'field1' => Array('type' => 'text','label' => 'Text'),
					'field2' => Array('type' => 'password','label' => 'Password'),
					'field3' => Array('type' => 'select','label' => 'Select', 'list' => Array('Male', 'Female')),
					'field4' => Array('type' => 'textarea','label' => 'Address'),
					'field5' => Array('type' => 'file'),
					'field6' => Array('type' => 'radio', 'list' => Array('Item 1','Item 2','Item 3','Item 4')),
					'field7' => Array('type' => 'checkbox', 'list' => Array('Item 1','Item 2','Item 3','Item 4'), 'value' => 'Item 1'),
					'field10' => Array('type' => 'submit','label' => 'Submit'),
				)
			))->render();
		?>
		<h1>1 Line Form</h1>
		<?php
			echo Formality_Form::factory('login')->render();
		?>
	</body>
</html>
