<?php
	// include settings
	include '../../admin-settings.php';

	// check if disable.php exists
	if(file_exists($adminpanel['root_php'] . '/disabled.php'))
	{
		include $adminpanel['root_php'] . '/lib/prevent-index.php'; exit();
	}
?>
<?php header("Content-Type: text/css; X-Content-Type-Options: nosniff;"); ?>

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