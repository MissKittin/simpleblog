<?php
	// Simpleblog v2.2 - main page
	// 04.04.2020

	// import settings
	if(!isset($simpleblog)) include 'settings.php';

	// jump to startup page
	if(!@include $simpleblog['root_php'] . '/pages/' . $simpleblog['startup_page'] . '/index.php')
		echo 'WTF. Check the startup page entry in settings.';
?>