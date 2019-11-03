<?php
	// simpleblog router script
	// Denied: footer.php, header.php, headlinks.php, articles (whole directory), prevent-index.php, favicon.php, htmlheaders.php
	// Removed (replaced by prevent-index.php): media, pages

	// settings
	$cms_root='/blog'; // directory
	$page_title='Simpleblog'; // <title>
	$entries_per_page=10;
	$taglinks=true; // enable/disable tag as link
	$skin='default'; // skin name

	// router cache
	$simpleblog_router_cache['strtok']=strtok($_SERVER['REQUEST_URI'], '?');
	$simpleblog_router_cache['substr']=substr($simpleblog_router_cache['strtok'], strrpos($simpleblog_router_cache['strtok'], '/') + 1);

	if($simpleblog_router_cache['substr'] === 'footer.php')
	{
		http_response_code(404);
		echo '<!DOCTYPE html>
			<html>
				<head>
					<title>' . $page_title . '</title>
					<link rel="stylesheet" type="text/css" href="' . $cms_root . '/style?root=' . $cms_root . '">
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
					<link rel="stylesheet" type="text/css" href="' . $cms_root . '/style?root=' . $cms_root . '">
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
					<link rel="stylesheet" type="text/css" href="' . $cms_root . '/style?root=' . $cms_root . '">
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
					<link rel="stylesheet" type="text/css" href="' . $cms_root . '/style?root=' . $cms_root . '">
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
					<link rel="stylesheet" type="text/css" href="' . $cms_root . '/style?root=' . $cms_root . '">
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
					<link rel="stylesheet" type="text/css" href="' . $cms_root . '/style?root=' . $cms_root . '">
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
					<link rel="stylesheet" type="text/css" href="' . $cms_root . '/style?root=' . $cms_root . '">
					<meta http-equiv="refresh" content="0; url=.">
				</head>
			</html>
		';
		exit();
	}

	// drop cache
	unset($simpleblog_router_cache);
?>