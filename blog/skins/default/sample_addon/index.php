<?php if(!isset($simpleblog)) include '../../settings.php'; ?>
<?php
	if(!isset($_GET['root']))
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php';
		exit();
	}

	header('Content-Type: text/css; X-Content-Type-Options: nosniff;');
?>


/* empty addon */