<?php
	// hide script - fake 404
	// Denied: settings.php, footer.php, header.php, headlinks.php, articles (whole directory), prevent-index.php
	// Commented (replaced by prevent-index.php): media, pages

	if(strtok(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1), '?') === 'settings.php')
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
	if(strtok(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1), '?') === 'footer.php')
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
	if(strtok(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1), '?') === 'header.php')
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
	if(strtok(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1), '?') === 'headlinks.php')
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

	/*
		if(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1) === 'media')
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
	*/
	if(strtok(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1), '?') === 'articles')
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
	$articles=explode("/", $_SERVER['REQUEST_URI'], 4); if($articles[2] === 'articles')
	{
		echo '<!DOCTYPE html>
			<html>
				<head>
					<meta http-equiv="refresh" content="0; url=..">
				</head>
			</html>
		';
		exit();
	} unset($articles);
	/*
		if(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1) === 'pages')
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
	*/
	if(strtok(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1), '?') === 'prevent-index.php')
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
?>