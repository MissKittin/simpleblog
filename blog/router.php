<?php
	// simpleblog router script
	// Denied: footer.php, header.php, headlinks.php, articles (whole directory), prevent-index.php, favicon.php, htmlheaders.php
	// Removed (replaced by prevent-index.php): media, pages

	// settings
	$cms_root='/blog'; // directory (for html)
	$cms_root_php=$_SERVER['DOCUMENT_ROOT'] . $cms_root; // directory (for php)
	$page_title='Simpleblog'; // <title>
	$entries_per_page=10;
	$taglinks=true; // enable/disable tag as link
	$skin='default'; // skin name

	// router cache
	$simpleblog_router_cache['strtok']=strtok($_SERVER['REQUEST_URI'], '?');
	$simpleblog_router_cache['substr']=substr($simpleblog_router_cache['strtok'], strrpos($simpleblog_router_cache['strtok'], '/') + 1);

	// simpleblog rules
	if($simpleblog_router_cache['substr'] === 'footer.php')
	{
		http_response_code(404);
		echo '<!DOCTYPE html>
			<html>
				<head>
					<title>' . $page_title . '</title>
					'; include $cms_root_php . '/htmlheaders.php'; echo '
					<meta http-equiv="refresh" content="0; url=.">
				</head>
			</html>
		';
		exit();
	}

	if($simpleblog_router_cache['substr'] === 'header.php')
	{
		http_response_code(404);
		echo '<!DOCTYPE html>
			<html>
				<head>
					<title>' . $page_title . '</title>
					'; include $cms_root_php . '/htmlheaders.php'; echo '
					<meta http-equiv="refresh" content="0; url=.">
				</head>
			</html>
		';
		exit();
	}

	if($simpleblog_router_cache['substr'] === 'headlinks.php')
	{
		http_response_code(404);
		echo '<!DOCTYPE html>
			<html>
				<head>
					<title>' . $page_title . '</title>
					'; include $cms_root_php . '/htmlheaders.php'; echo '
					<meta http-equiv="refresh" content="0; url=.">
				</head>
			</html>
		';
		exit();
	}

	if(explode('/', substr($simpleblog_router_cache['strtok'], strlen($cms_root)))[1] === 'articles')
	{
		http_response_code(404);
		echo '<!DOCTYPE html>
			<html>
				<head>
					<title>' . $page_title . '</title>
					'; include $cms_root_php . '/htmlheaders.php'; echo '
					<meta http-equiv="refresh" content="0; url=.">
				</head>
			</html>
		';
		exit();
	}

	if($simpleblog_router_cache['substr'] === 'prevent-index.php')
	{
		http_response_code(404);
		echo '<!DOCTYPE html>
			<html>
				<head>
					<title>' . $page_title . '</title>
					'; include $cms_root_php . '/htmlheaders.php'; echo '
					<meta http-equiv="refresh" content="0; url=.">
				</head>
			</html>
		';
		exit();
	}

	if($simpleblog_router_cache['substr'] === 'favicon.php')
	{
		http_response_code(404);
		echo '<!DOCTYPE html>
			<html>
				<head>
					<title>' . $page_title . '</title>
					'; include $cms_root_php . '/htmlheaders.php'; echo '
					<meta http-equiv="refresh" content="0; url=.">
				</head>
			</html>
		';
		exit();
	}

	if($simpleblog_router_cache['substr'] === 'htmlheaders.php')
	{
		http_response_code(404);
		echo '<!DOCTYPE html>
			<html>
				<head>
					<title>' . $page_title . '</title>
					'; include $cms_root_php . '/htmlheaders.php'; echo '
					<meta http-equiv="refresh" content="0; url=.">
				</head>
			</html>
		';
		exit();
	}

	// rewrite common rules
	if($simpleblog_router_cache['substr'] === $routerscan['filename']) // hide script - fake 404 for local scripts
	{
		http_response_code(404);
		echo '<!DOCTYPE html>
			<html>
				<head>
					<title>' . $page_title . '</title>
					'; include $cms_root_php . '/htmlheaders.php'; echo '
					<meta http-equiv="refresh" content="0; url=.">
				</head>
			</html>
		';
		exit();
	}

	if($simpleblog_router_cache['substr'] === 'index.php') // hide php
	{
		http_response_code(404);
		echo '<!DOCTYPE html>
			<html>
				<head>
					<title>' . $page_title . '</title>
					'; include $cms_root_php . '/htmlheaders.php'; echo '
					<meta http-equiv="refresh" content="0; url=.">
				</head>
			</html>
		';
		exit();
	}

	if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $simpleblog_router_cache['strtok'])) // 404 handle - for files
	{
		http_response_code(404);
		echo '<!DOCTYPE html>
			<html>
				<head>
					<title>' . $page_title . '</title>
					'; include $cms_root_php . '/htmlheaders.php'; echo '
					<meta http-equiv="refresh" content="0; url=.">
				</head>
			</html>
		';
		exit();
	}

	if(is_dir($_SERVER['DOCUMENT_ROOT'] . $simpleblog_router_cache['strtok'])) // 404 handle - for dirs
		if((file_exists($_SERVER['DOCUMENT_ROOT'] . $simpleblog_router_cache['strtok'] . '/index.php')) || (file_exists($_SERVER['DOCUMENT_ROOT'] . $simpleblog_router_cache['strtok'] . '/index.html')))
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
						<title>' . $page_title . '</title>
						'; include $cms_root_php . '/htmlheaders.php'; echo '
						<meta http-equiv="refresh" content="0; url=' . $url . '">
					</head>
				</html>
			';
			exit();

		}

	// drop cache
	unset($simpleblog_router_cache);
?>