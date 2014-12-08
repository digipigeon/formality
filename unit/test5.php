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
					'field1' => Array('type' => 'text','label' => 'Text'),
					'field10' => Array('type' => 'submit','label' => 'Submit'),
				)
			))->parse();

			?><h2>Render field1 without label, without container</h2><?php			
			echo $form->field['field1']->render(false,false);

			?><h2>Render field1 without label, with container</h2><?php			
			echo $form->field['field1']->render(false);

			?><h2>Render field1 with label, without container</h2><?php
			echo $form->field['field1']->render(true, false);

			?><h2>Render field1 with label, with container</h2><?php			
			echo $form->field['field1']->render();

		?>
	</body>
</html>
