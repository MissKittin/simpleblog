<?php
	// Simpleblog v2.2 - main page
	// 04.04.2020

	// import apache settings
	if(php_sapi_name() != 'cli-server') include 'settings.php';

	// jump to startup page
	if(!@include $simpleblog['root_php'] . '/pages/' . $simpleblog['startup_page'] . '/index.php')
		echo 'WTF. Check the startup page entry in settings.';
?>