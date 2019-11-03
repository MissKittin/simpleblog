<?php
	// If you want to run this app on the php built-in server, pass path to this script in cmdline

	// router cache
	$router_cache['strtok']=strtok($_SERVER['REQUEST_URI'], '?');
	$router_cache['strrpos']=strrpos($router_cache['strtok'], '/');
	$router_cache['substr']=substr($router_cache['strtok'], $router_cache['strrpos'] + 1);

	// hide script - fake 404
	if($router_cache['substr'] === 'router.php')
	{
		http_response_code(404);
		echo '<!DOCTYPE html>
			<html>
				<head>
					<meta http-equiv="refresh" content="0; url=.">
				</head>
			</html>
		';
		exit();
	}

	// hide script - fake 404 for local scripts
	$routerscan['filename']='router.php'; // set file name here
	if($router_cache['substr'] === $routerscan['filename'])
	{
		http_response_code(404);
		echo '<!DOCTYPE html>
			<html>
				<head>
					<meta http-equiv="refresh" content="0; url=.">
				</head>
			</html>
		';
		exit();
	}

	// dont allow index.php in uri
	if($router_cache['substr'] === 'index.php')
	{
		http_response_code(404);
		echo '<!DOCTYPE html>
			<html>
				<head>
					<meta http-equiv="refresh" content="0; url=.">
				</head>
			</html>
		';
		exit();
	}

	// 404 handle - for files
	if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $router_cache['strtok']))
	{
		http_response_code(404);
		if(substr(strtok($_SERVER['REQUEST_URI'], '?'), -1) === '/')
			$url='..';
		else
			$url='.';

		echo '<!DOCTYPE html>
			<html>
				<head>
					<meta http-equiv="refresh" content="0; url=' . $url . '">
				</head>
			</html>
		';
		exit();
	}

	// 404 handle - for dirs
	if(is_dir($_SERVER['DOCUMENT_ROOT'] . $router_cache['strtok']))
		if((file_exists($_SERVER['DOCUMENT_ROOT'] . $router_cache['strtok'] . '/index.php')) || (file_exists($_SERVER['DOCUMENT_ROOT'] . $router_cache['strtok'] . '/index.html')))
		{ /* everything is ok */ }
		else
		{
			http_response_code(404);
			if(substr(strtok($_SERVER['REQUEST_URI'], '?'), -1) === '/')
				$url='..';
			else
				$url='.';

			echo '<!DOCTYPE html>
				<html>
					<head>
						<meta http-equiv="refresh" content="0; url=' . $url . '">
					</head>
				</html>
			';
			exit();
		}

	// import custom router.php
	$routerscan['data']=explode('/', substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($_SERVER['SCRIPT_FILENAME'], '/')));
	$routerscan['elements']=count($routerscan['data'])-1;
	$routerscan['rendered']='/';
	foreach($routerscan['data'] as $routerscan['i'])
	{
		// render path
		for($routerscan['x']=$routerscan['elements']; $routerscan['x']>=0; $routerscan['x']--)
			if($routerscan['data'][$routerscan['x']] != '')
				$routerscan['rendered']='/' . $routerscan['data'][$routerscan['x']] . $routerscan['rendered'];

		// do not enter to the outside of jail and dont scan root dir
		if($routerscan['rendered'] === $_SERVER['DOCUMENT_ROOT'] . '/')
			break;

		// break if router script exists
		if(file_exists($routerscan['rendered'] . $routerscan['filename']))
		{
			include $routerscan['rendered'] . $routerscan['filename'];
			break;
		}

		// reset rendered and go to the upper dir
		$routerscan['elements']=$routerscan['elements']-1;
		$routerscan['rendered']='/';
	}
	unset($routerscan); // clean

	// drop cache
	unset($router_cache);

	// abort script - load destination file
	return false;
?>