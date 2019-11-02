<?php if(php_sapi_name() != 'cli-server') include '../settings.php'; ?>
<?php
	if(php_sapi_name() === 'cli-server')
		if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strlen(strtok($_SERVER['REQUEST_URI'], '?')) - 1) === '/')
		{
			echo '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="0; url=' . substr(strtok($_SERVER['REQUEST_URI'], '?'), 0, -1) . '"></head></html>';
			exit();
		}

	if(!isset($_GET['root']))
	{
		echo '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="0; url=."></head></html>';
		exit();
	}

	header("Content-Type: text/css; X-Content-Type-Options: nosniff;");
	echo '@import "' . $cms_root . '/skins/' . $skin . '/style.css"';
?>