<?php
	// Simpleblog v2.2 - post subsystem
	// 04.04.2020

	// import settings
	if(!isset($simpleblog)) include '../settings.php';

	// import view
	if(!@include $simpleblog['root_php'] . '/skins/' . $simpleblog['skin'] . '/views/viewPost.php')
		echo 'WTF. Check the skin entry in settings or bad skin.';
?>
<?php if(isset($simpleblog['execTime'])) error_log('Simpleblog execution time: ' . (microtime(true) - $simpleblog['execTime']) . 's, max mem used: ' . memory_get_peak_usage() . 'B', 0); ?>