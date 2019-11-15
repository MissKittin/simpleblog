<?php
	// deny access if settings not imported
	if(php_sapi_name() != 'cli-server')
		if(!isset($simpleblog))
			include 'prevent-index.php';

	// deny direct access
	if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/footer.php')
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
	}
?>
Sample footer
