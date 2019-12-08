<?php
	// require HTTPS

	// new explode function
	$requireHTTPS['explode']=function($a, $b, $offset)
	{
		$array=explode($a, $b);
		if(isset($array[$offset]))
			return $array[$offset];
		return false;
	};

	// Apache version don't have $simpleblog_router_cache
	if(php_sapi_name() != 'cli-server')
		$simpleblog_router_cache['strtok']=strtok($_SERVER['REQUEST_URI'], '?');

	// cache
	$requireHTTPS['if_cache']=substr($simpleblog_router_cache['strtok'], strlen($simpleblog['root_html']));

	if(
		(!isset($_SERVER['HTTPS'])) &&
		($requireHTTPS['explode']('/', $requireHTTPS['if_cache'], 2) != 'favicon') &&
		($requireHTTPS['explode']('/', $requireHTTPS['if_cache'], 1) != 'skins')
	)
	{
		echo '<!DOCTYPE html>
			<html>
				<head>
					<title>' . $simpleblog['title'] . '</title>
					<meta charset="utf-8">
					'; include $simpleblog['root_php'] . '/lib/htmlheaders.php'; echo '
					<meta http-equiv="refresh" content="0; url=https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '">
				</head>
				<body></body>
			</html>';
		exit();
	}

	// clear
	unset($requireHTTPS);
	if(php_sapi_name() != 'cli-server') unset($simpleblog_router_cache);
?>