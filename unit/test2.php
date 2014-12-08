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
		<h1>Simple Form Generation</h1>
		<pre>
			echo Formality_Form::factory('form1', Array(
				'fieldset' => Array(
					'fieldset1' => Array(
						'legend' => 'Fieldset 1',
						'fields' => Array('field1')
					)
				),
				'field' => Array(
					'field1' => Array('type' => 'text','label' => 'Label 1')
				)
			))->render();
		</pre>
		<?php
			echo Formality_Form::factory('form1', Array(
				'fieldset' => Array(
					'fieldset1' => Array(
						'legend' => 'Fieldset 1',
						'fields' => Array('field1')
					)
				),
				'field' => Array(
					'field1' => Array('type' => 'text','label' => 'Label 1')
				)
			))->render();
		?>

		<h1>Form, Library field &amp; custom post</h1>
		<pre>
			$form = Formality_Form::factory('form2', Array(
				'fieldset' => Array(
					'fieldset2' => Array(
						'legend' => 'Fieldset 2',
						'fields' => Array('password')
					)
				),
				'field' => Array(
					'password' => Array('label' => 'My Password')
				)
			), '/my/custom/post/location');
			echo $form->render();
		</pre>

		<?php
			$form = Formality_Form::factory('form2', Array(
				'fieldset' => Array(
					'fieldset2' => Array(
						'legend' => 'Fieldset 2',
						'fields' => Array('password')
					)
				),
				'field' => Array(
					'password' => Array('label' => 'My Password')
				)
			), '/my/custom/post/location');
			echo $form->render();
		?>
		<h1>Form, Library field Template</h1>
		<pre>
			$form = Formality_Form::factory('form3', Array(
				'fieldset' => Array(
					'fieldset3' => Array(
						'legend' => 'Fieldset 3',
						'fields' => Array('password','confirm_password')
					)
				),
				'field' => Array(
					'password' => Array('label' => 'My Password'),
					'confirm_password' => Array('template' => 'password', 'label' => 'Confirm My Password')
				)
			));
			echo $form->render();
		</pre>
		<?php
			$form = Formality_Form::factory('form3', Array(
				'fieldset' => Array(
					'fieldset3' => Array(
						'legend' => 'Fieldset 3',
						'fields' => Array('password','confirm_password')
					)
				),
				'field' => Array(
					'password' => Array('label' => 'My Password'),
					'confirm_password' => Array('template' => 'password', 'label' => 'Confirm My Password')
				)
			));
			echo $form->render();
		?>
		<h1>Form</h1>
		<?php
		
			$form = Formality_Form::factory('form3', Array(
				'fieldset' => Array(
					'fieldset4' => Array(
						'legend' => 'Fieldset 4',
						'fields' => Array('firstname', 'lastname')
					),
					'fieldset5' => Array(
						'legend' => 'Fieldset 5',
						'fields' => Array('password','confirm_password')
					),
					'fieldset6' => Array(
						'legend' => 'Fieldset 6',
						'fields' => Array('save')
					)
				),
				'field' => Array(
					'save' => Array(),
					'firstname' => Array('label' => 'First Name'),
					'lastname' => Array('label' => 'Last Name'),
					'password' => Array('label' => 'My Password'),
					'confirm_password' => Array('template' => 'password', 'label' => 'Confirm My Password')
				)
			));

			echo $form->render();
			
		?>



	</body>
</html>
