<?php
include('../examples/standalone.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
	<head>
		<title>Formality::Field Unit Tests</title>	
		<style type="text/css">
			@import "../css/reset.css";
			@import "../css/form.css";
			@import "../css/screen.css";
		</style>
	</head>
	<body>
		<h1>Simple Field Generation</h1>
		<code>
			echo Formality_Field::factory('field1')->render();
		</code>
		<?php
			echo Formality_Field::factory('field1')->render();
		?>

		<h1>Modified Configuration Field Generation</h1>
		<code>
			echo Formality_Field::factory('field2')->value('New Value')->render();
		</code>
		<?php
			echo Formality_Field::factory('field2')->value('New Value')->render();
		?>

		<h1>Modified Label Configuration Field Generation</h1>
		<code>
			echo Formality_Field::factory('field3')->label('New Label')->label_style('color:#FF0000;')->render();
		</code>
		<?php
			echo Formality_Field::factory('field3')->label('New Label')->label_style('color:#FF0000;')->render();
		?>

		<h1>Field from Library</h1>
		<code>
			echo Formality_Field::factory('password')->render();
		</code>
		<?php
			echo Formality_Field::factory('password')->render();
		?>

		<h1>Field from Library with Modification</h1>
		<code>
			echo Formality_Field::factory('password')->label('My Password')->render();
		</code>
		<?php
			echo Formality_Field::factory('password')->label('My Password')->render();
		?>


		<h1>Field from Library with Template</h1>
		<code>
			echo Formality_Field::factory('field5')->template('password')->render();
		</code>
		<?php
			echo Formality_Field::factory('field5',Array('template' => 'password'))->render();
		?>

		<h1>Field from Library with Template Label Set</h1>
		<code>
			echo Formality_Field::factory('field6',Array('template' => 'password','label' => 'New Password'))->render();
		</code>
		<?php
			echo Formality_Field::factory('field6',Array('template' => 'password','label' => 'New Password'))->render();
		?>

		<h1>Field from Library with Template Label Set Chain Override</h1>
		<code>
			echo Formality_Field::factory('field6',Array('template' => 'password','label' => 'New Password'))->label('Confirm New Password')->render();
		</code>
		<?php
			echo Formality_Field::factory('field6',Array('template' => 'password','label' => 'New Password'))->label('Confirm New Password')->render();
		?>

	</body>
</html>