<?php
	// deny directory listing
	if(php_sapi_name() != 'cli-server') include '../../../settings.php';
	include $simpleblog['root_php'] . '/lib/prevent-index.php';
	exit();
?>