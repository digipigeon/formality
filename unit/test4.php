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
		<h1>Field Validation (min length example)</h1>
		<?php
			$form = Formality_Form::factory('form1', Array(
				'fieldset' => Array(
					'fieldset1' => Array(
						'legend' => 'Fieldset 1',
						'fields' => Array('field1', 'field10')
					)
				),
				'field' => Array(
					'field1' => Array('type' => 'text','label' => 'Text', 'value' => 'Test Text', 'validate' => Array(
						'minlen' => 100,
					)),
					'field10' => Array('type' => 'submit','label' => 'Submit'),
				)
			));
			$form->parse();
			$form->validate();
			echo $form->render();
		?>
		<br />
		<h1>Field Validation (min length with custom error message)</h1>
		<?php
			$form = Formality_Form::factory('form1', Array(
				'fieldset' => Array(
					'fieldset1' => Array(
						'legend' => 'Fieldset 1',
						'fields' => Array('field1', 'field10')
					)
				),
				'field' => Array(
					'field1' => Array('type' => 'text','label' => 'Text', 'value' => 'Test Text', 'validate' => Array(
						'minlen' => 100,
						'err'	=> Array(
							'minlen'	=> 'Minimum Length Required and not met'
						),
//						'myclass' => 'Validate_Class',
//						'myfunction' => function($validate, $arg2){
							
//						}
					)),
					'field10' => Array('type' => 'submit','label' => 'Submit'),
				)
			));
			$form->parse();
			$form->validate();
			echo $form->render();
		?>
		<br />
		<h1>Field Validation (closure validation function)</h1>
		<?php
			$form = Formality_Form::factory('form1', Array(
				'fieldset' => Array(
					'fieldset1' => Array(
						'legend' => 'Fieldset 1',
						'fields' => Array('field1', 'field10')
					)
				),
				'field' => Array(
					'field1' => Array('type' => 'text','label' => 'Text', 'value' => 'Test Text', 'validate' => Array(
						'myfunction' => function($input){
							if ($input == 'Yes') return true;
							return 'The input was not Yes';
						}
					)),
					'field10' => Array('type' => 'submit','label' => 'Submit'),
				)
			));
			$form->parse();
			$form->validate();
			echo $form->render();
		?>

	</body>
</html>
