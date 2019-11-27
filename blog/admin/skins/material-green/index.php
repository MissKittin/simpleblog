<?php
	// include settings
	include '../../admin-settings.php';

	// check if disable.php exists
	if(file_exists($adminpanel['root_php'] . '/disabled.php'))
	{
		include $adminpanel['root_php'] . '/lib/prevent-index.php'; exit();
	}
?>
<?php header('Content-Type: text/css; X-Content-Type-Options: nosniff;'); ?>

/* material design theme for simpleblog admin panel - pages */
/* 12.11.2019 */

/* Commons */
body {
	background-color: #e5e5e5;
	font-size: 16px;
	font-family: Roboto,Helvetica,Arial,sans-serif;
	margin: 0;
	padding: 0;
}

/* Buttons */
.button {
	color: #ffffff;
	background-color: #26a69a;
	letter-spacing: .5px;
	font-size: 14px;
	text-align: center;
	text-transform: uppercase;
	border: none;
	border-radius: 2px;
	height: 36px;
	line-height: 36px;
	margin: 5px;
	padding: 0 16px;
	box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);
}
.button a, .button a:hover, .button a:visited {
	color: #ffffff;
	text-decoration: none;
}

/* Text inputs */
textarea {
	border: none;
	resize: none;
	outline: none;
	box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
	white-space: nowrap;
}
input[type=text], input[type=password] {
	background-color: transparent;
	border: none;
	border-bottom: 1px solid #9e9e9e;
	height: 48px;
	width: 100%;
	margin: 0 0 15px 0;
	padding: 0;
	outline: none;
}
input[type=text]:focus, input[type=password]:focus {
	border-bottom: 1px solid #4caf50;
}
label {
	color: #9e9e9e;
	position: relative;
	top: 0; left: 0.75rem;
	font-size: 0.8rem;
}

/* tables */
table a, table a:hover, table a:visited {
	text-decoration: none;
	color: #0000ff;
}
table {
	border-collapse: collapse;
}
table tr:nth-child(even) {
	background-color: #f2f2f2;
}
table td {
	white-space: nowrap;
}

/* switches (checkboxes) */
label input[type=checkbox] {
	opacity: 0;
	width: 0;
	height: 0;
}

label input[type=checkbox]:checked + .lever {
	background-color: #84c7c1;
}

label input[type=checkbox]:checked + .lever:before, label input[type=checkbox]:checked + .lever:after {
	left: 18px;
}

label input[type=checkbox]:checked + .lever:after {
	background-color: #26a69a;
}

label .lever {
	content: "";
	display: inline-block;
	position: relative;
	width: 36px;
	height: 14px;
	background-color: rgba(0, 0, 0, 0.38);
	border-radius: 15px;
	margin-right: 10px;
	transition: background 0.3s ease;
	vertical-align: middle;
	margin-bottom: 2px;
}

label .lever:before, label .lever:after {
	content: "";
	position: absolute;
	display: inline-block;
	width: 20px;
	height: 20px;
	border-radius: 50%;
	left: 0;
	top: -3px;
	transition: left 0.3s ease, background .3s ease, box-shadow 0.1s ease, transform .1s ease;
}

label .lever:before {
	background-color: rgba(38, 166, 154, 0.15);
}

label .lever:after {
	background-color: #F1F1F1;
	box-shadow: 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12);
}

input[type=checkbox]:checked:not(:disabled) ~ .lever:active::before, input[type=checkbox]:checked:not(:disabled).tabbed:focus ~ .lever::before {
	transform: scale(2.4);
	background-color: rgba(38, 166, 154, 0.15);
}

input[type=checkbox]:not(:disabled) ~ .lever:active:before, input[type=checkbox]:not(:disabled).tabbed:focus ~ .lever::before {
	transform: scale(2.4);
	background-color: rgba(0, 0, 0, 0.08);
}

/* header */
#header {
	color: #ffffff;
	background-color: #4caf50;
	overflow: auto;
	position: relative;
	margin: 0;
	padding: 0; padding-left: 10px;
	box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}
#logout_button {
	position: absolute;
	top: 5px; right: 5px;
}

/* menu */
#headlinks {
	background-color: #f6f6f6;
	overflow: auto;
	margin-top: 2px; margin-left: 5px; margin-right: 5px;
}
.headlink {
	color: #7fbf8f; /* for disabled elements */
	text-align: center;
	text-transform: uppercase;
	position: relative;
	padding: 13px 0;
	float: left;
	width: 100px;
	height: 22px;
}
.headlink a, .headlink a:hover, .headlink a:visited { /* enabled elements */
	color: #197b10;
	text-decoration: none;
	text-transform: uppercase;
}
#headlink_active { /* bar under the text */
	background-color: #4caf50;
	position: absolute;
	bottom: 0;
	width: 100px;
	height: 2px;
}

/* content */
#content_header {
	color: #777777;
	background-color: #f5f5f5;
	font-size: 2rem;
	margin: 10px; margin-bottom: 1px;
	padding: 5px;
	box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}
#content {
	background-color: #ffffff;
	margin: 10px; margin-top: 1px;
	padding: 5px;
	box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
	margin-bottom: 50px; /* for footer */
	overflow: auto;
}

/* footer */
#footer {
	color: #ffffff;
	background-color: #4caf50;
	padding: 5px;
	position: fixed;
	bottom: 0;
	width: 100%;
	height: 30px;
	box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}
#footerText {
	float: right;
	margin-right: 20px;
	padding-top: 5px;
}