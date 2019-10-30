<?php
	// hide script - fake 404
	// Denied: settings.php, footer.php, header.php, headlinks.php, articles (whole directory), prevent-index.php
	// Removed (replaced by prevent-index.php): media, pages

	// router cache
	$simpleblog_router_cache['strtok']=strtok($_SERVER['REQUEST_URI'], '?');
	$simpleblog_router_cache['substr']=substr($simpleblog_router_cache['strtok'], strrpos($simpleblog_router_cache['strtok'], '/') + 1);

	if($simpleblog_router_cache['substr'] === 'settings.php')
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
	if($simpleblog_router_cache['substr'] === 'footer.php')
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
	if($simpleblog_router_cache['substr'] === 'header.php')
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
	if(strtok(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1), '?') === 'headlinks.php')
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
	if($simpleblog_router_cache['substr'] === 'articles')
	{
		echo '<!DOCTYPE html>
			<html>
				<head>
					<meta http-equiv="refresh" content="0; url=.">
				</head>
			</html>
		';
		exit();
	}
	$articles=explode("/", $simpleblog_router_cache['strtok'], 4); if($articles[2] === 'articles')
	{
		http_response_code(404);
		echo '<!DOCTYPE html>
			<html>
				<head>
					<meta http-equiv="refresh" content="0; url=..">
				</head>
			</html>
		';
		exit();
	} unset($articles);

	if($simpleblog_router_cache['substr'] === 'prevent-index.php')
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

	// drop cache
	unset($simpleblog_router_cache);
?>