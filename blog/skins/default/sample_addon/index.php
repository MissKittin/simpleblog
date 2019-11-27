<?php if(php_sapi_name() != 'cli-server') include '../../../settings.php'; ?>
<?php
	if(php_sapi_name() === 'cli-server')
		if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strlen(strtok($_SERVER['REQUEST_URI'], '?')) - 1) === '/')
		{
			include $simpleblog['root_php'] . '/lib/prevent-index.php';
			exit();
		}

	if(!isset($_GET['root']))
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php';
		exit();
	}

	header('Content-Type: text/css; X-Content-Type-Options: nosniff;');
?>


/* empty addon */