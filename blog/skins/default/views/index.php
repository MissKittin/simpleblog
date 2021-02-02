<?php
	// deny directory listing
	if(!isset($simpleblog)) include '../../../settings.php';
	include $simpleblog['root_php'] . '/lib/prevent-index.php';
	exit();
?>