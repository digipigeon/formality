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
		<h1>Form Process with Validation</h1>
		<?php
			$form = Formality_Form::factory('login');
			if (($output = $form->process()) === true){
				var_dump($form->save());
			}else{
				echo $output;
			};
		?>
		<h1>Form Process with Validation &amp; Verification</h1>
		<?php
			$form = Formality_Form::factory('login');
			if (($output = $form->process()) === true){
				var_dump($form->save());
			}else{
				echo $output;
			};
		?>

	</body>
</html>
