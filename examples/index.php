<?php
include('standalone.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
	<head>
		<title>Formality Form</title>
		<style type="text/css">
			@import "../css/reset.css";
			@import "../css/form.css";
			@import "../css/screen.css";
		</style>
	</head>
	<body>
		<?php
		
			$form = Formality_Form::factory('form3', Array(
				'fieldset' => Array(
					'credentials' => Array(
						'legend' => 'Your Credentials',
						'fields' => Array('username', 'password', 'confirm_password')
					),
					'account-settings' => Array(
						'legend' => 'Account Settings',
						'fields' => Array('password','confirm_password')
					),
					'new' => array(
						'class' => 'no-legend',
						'fields' => array('field3')
					),
					'control' => Array(
						'class' => 'no-legend',
						'fields' => Array('save')
					)
				),
				'field' => Array(
					'save' => Array(),
					'firstname' => Array('label' => 'First Name', 'validation' => 'Email'),
					'lastname' => Array('label' => 'Last Name'),
					'username' => array('label' => 'Username'),
					'password' => Array('label' => 'Password'),
					'confirm_password' => Array('template' => 'password', 'label' => 'Confirm Password'),
					'field3' => Array('type' => 'select','label' => 'Select', 'list' => Array('Male', 'Female')),
					'field4' => Array('type' => 'textarea','label' => 'Address'),
					'field5' => Array('type' => 'file'),
					'field6' => Array('type' => 'radio', 'list' => Array('Item 1','Item 2','Item 3','Item 4')),
					'field7' => Array('type' => 'checkbox', 'list' => Array('Item 1','Item 2','Item 3','Item 4'), 'value' => 'Item 1')
				)
			));

			echo $form->render();
			
		?>
	</body>
</html>
