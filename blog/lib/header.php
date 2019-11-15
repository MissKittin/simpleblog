<?php
	// deny access if settings not imported
	if(php_sapi_name() != 'cli-server')
		if(!isset($simpleblog))
			include 'prevent-index.php';

	// deny direct access
	if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/header.php')
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
	}
?>
<h1 style="text-align: center; margin-bottom: 0;">Sample header</h1>
<div style="text-align: center; margin-bottom: 5px;">Sample description</div>
