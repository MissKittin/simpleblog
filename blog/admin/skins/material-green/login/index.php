<?php
	// include settings
	include '../../../admin-settings.php';

	// check if disable.php exists
	if(file_exists($adminpanel['root_php'] . '/disabled.php'))
	{
		include $adminpanel['root_php'] . '/lib/prevent-index.php'; exit();
	}
?>
<?php header("Content-Type: text/css; X-Content-Type-Options: nosniff;"); ?>

/* material design theme for simpleblog admin panel - login page */
/* 13.11.2019 */

/* Misc */
body {
	font-family: Roboto,Helvetica,Arial,sans-serif;
	margin: 0;
	padding: 0;
}

/* Main box */
#login_box {
	background-color: #f5f5f5;
	width: 400px;
	height: 400px;
	border: 1px solid #eee;
	margin: auto;
	position: absolute;
	top: 0; left: 0; bottom: 0; right: 0;
	box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}

/* Main box content */
#content {
	display: inline-block;
	padding-left: 35px;
	padding-top: 20px;
	width: 80%;
	height: 80%;
}

/* Inputs - text */
.input_field {
	position: relative;
	margin: 30px;
}
.input_field input[type=text], .input_field input[type=password] {
	background-color: transparent;
	border: none;
	border-bottom: 1px solid #9e9e9e;
	height: 48px;
	width: 100%;
	margin: 0 0 15px 0;
	padding: 0;
	outline: none;
}
.input_field input[type=text]:focus, .input_field input[type=password]:focus {
	border-bottom: 1px solid #4caf50;
}
.input_field label {
	color: #9e9e9e;
	position: absolute;
	top: 0; left: 0.75rem;
	font-size: 0.8rem;
	cursor: text;
	/* transition: .2s ease-out; */
}

/* Inputs - buttons */
#input_buttons {
	margin-top: 80px;
}
.button {
	width: 100%;
	border: none;
	box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);
	background-color: #4caf50;
	color: #ffffff;
	padding: 20px;
	margin-top: 5px;
}

/* Loading... text */
#loading {
	position: absolute;
	top: 50%; left: 50%;
	transform: translateX(-50%) translateY(-50%);
}