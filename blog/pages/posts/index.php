<?php /* import apache settings (if not imported by main index) */ if((!isset($simpleblog)) && (php_sapi_name() != 'cli-server')) include '../../settings.php'; ?>
<?php
	// import view
	if(!@include $simpleblog['root_php'] . '/skins/' . $simpleblog['skin'] . '/views/viewIndex.php')
		echo 'WTF. Check the skin entry in settings or bad skin.';
?>
<?php if(isset($simpleblog['execTime'])) error_log('Simpleblog execution time: ' . (microtime(true) - $simpleblog['execTime']) . 's, max mem used: ' . memory_get_peak_usage() . 'B', 0); ?>