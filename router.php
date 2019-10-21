<?php
	// If you want to run this app on the php built-in server, pass path to this script in cmdline

	// hide script - fake 404
	if(strtok(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1), '?') === 'router.php')
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
	$routerscan['filename']='.router.php'; // set file name here
	if(strtok(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1), '?') === $routerscan['filename'])
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
	if(strtok(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1), '?') === 'index.php')
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
	if(!file_exists(strtok($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'], '?')))
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
	if(is_dir(strtok($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'], '?')))
		if((file_exists(strtok($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'], '?') . '/index.php')) || (file_exists(strtok($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'], '?') . '/index.html')))
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

	// abort script - load destination file
	return false;
?>