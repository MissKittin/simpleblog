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
	$cms_fake_notfound=true; // use http_response_code(404)

	// router cache
	$simpleblog_router_cache['strtok']=strtok($_SERVER['REQUEST_URI'], '?');
	$simpleblog_router_cache['substr']=substr($simpleblog_router_cache['strtok'], strrpos($simpleblog_router_cache['strtok'], '/') + 1);

	// simpleblog rules
	if($simpleblog_router_cache['substr'] === 'footer.php')
	{
		include $cms_root_php . '/prevent-index.php';
		exit();
	}

	if($simpleblog_router_cache['substr'] === 'header.php')
	{
		include $cms_root_php . '/prevent-index.php';
		exit();
	}

	if($simpleblog_router_cache['substr'] === 'headlinks.php')
	{
		include $cms_root_php . '/prevent-index.php';
		exit();
	}

	if(explode('/', substr($simpleblog_router_cache['strtok'], strlen($cms_root)))[1] === 'articles')
	{
		include $cms_root_php . '/prevent-index.php';
		exit();
	}

	if($simpleblog_router_cache['substr'] === 'prevent-index.php')
	{
		include $cms_root_php . '/prevent-index.php';
		exit();
	}

	if($simpleblog_router_cache['substr'] === 'favicon.php')
	{
		include $cms_root_php . '/prevent-index.php';
		exit();
	}

	if($simpleblog_router_cache['substr'] === 'htmlheaders.php')
	{
		include $cms_root_php . '/prevent-index.php';
		exit();
	}

	if(explode('/', substr($simpleblog_router_cache['strtok'], strlen($cms_root)))[1] === 'cron')
	{
		include $cms_root_php . '/prevent-index.php';
		exit();
	}

	// rewrite common rules
	if($simpleblog_router_cache['substr'] === $routerscan['filename']) // hide script - fake 404 for local scripts
	{
		include $cms_root_php . '/prevent-index.php';
		exit();
	}

	if($simpleblog_router_cache['substr'] === 'index.php') // hide php
	{
		include $cms_root_php . '/prevent-index.php';
		exit();
	}

	/* if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $simpleblog_router_cache['strtok'])) // 404 handle - for files
	{
		include $cms_root_php . '/prevent-index.php';
		exit();
	} */

	if(is_dir($_SERVER['DOCUMENT_ROOT'] . $simpleblog_router_cache['strtok'])) // 404 handle - for dirs
		if((file_exists($_SERVER['DOCUMENT_ROOT'] . $simpleblog_router_cache['strtok'] . '/index.php')) || (file_exists($_SERVER['DOCUMENT_ROOT'] . $simpleblog_router_cache['strtok'] . '/index.html')))
		{ /* everything is ok */ }
		else
		{
			include $cms_root_php . '/prevent-index.php';
			exit();

		}

	// include maintenace break pattern
	include $cms_root_php . '/maintenace-break.php';

	// execute cron tasks
	include $cms_root_php . '/cron.php';

	// drop cache
	unset($simpleblog_router_cache);
?>