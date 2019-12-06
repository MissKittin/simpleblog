<?php
	// Simpleblog v1 16.04.2019
	// Simpleblog v2 11.11.2019
	// Simpleblog v2.1 03.12.2019
	// Edit lines 13-35

	// Denied: articles/*, favicon.php, htmlheaders.php, cron/* and tmp/*

	// start execution time monitor (uncomment this to enable)
	//$simpleblog['execTime']=microtime(true); 

	// settings - cms
	$simpleblog['root_html']='/blog'; // directory (for html)
	$simpleblog['root_php']=$_SERVER['DOCUMENT_ROOT'] . $simpleblog['root_html']; // directory (for php)
	$simpleblog['title']='Simpleblog'; // <title>
	$simpleblog['short_title']='SimpleblogShortTitle'; // for admin panel
	$simpleblog['entries_per_page']=10;
	$simpleblog['taglinks']=true; // enable/disable tag as link
	$simpleblog['postlinks']=true; // enable/disable post title as link
	$simpleblog['datelinks']=true; // enable/disable post date as link
	$simpleblog['skin']='default'; // skin name
	$simpleblog['fake_notfound']=true; // use http_response_code(404)

	// settings - one label in whole cms
	$simpleblog['emptyLabel']='<h1 style="text-align: center;">Empty</h1>';

	// settings - maintenance break pattern
	$maintenance_break['enabled']=false;
	$maintenance_break['allowed_ip']='127.0.0.1';

	// settings - backward compatibility with v1
	// uncomment three lines below if you have old skins or articles
	//$cms_root=$simpleblog['root_html'];
	//$cms_root_php=$simpleblog['root_php'];
	//$page_title=$simpleblog['title'];

	// settings end - don't edit code below

	// router cache
	$simpleblog_router_cache['strtok']=strtok($_SERVER['REQUEST_URI'], '?');
	$simpleblog_router_cache['substr']=substr($simpleblog_router_cache['strtok'], strrpos($simpleblog_router_cache['strtok'], '/') + 1);
	$simpleblog_router_cache['explode_input']=substr($simpleblog_router_cache['strtok'], strlen($simpleblog['root_html']));

	// new explode function
	$simpleblog_router_cache['explode']=function($a, $b, $offset)
	{
		$array=explode($a, $b);
		if(isset($array[$offset]))
			return $array[$offset];
		return false;
	};

	// simpleblog rules
	if($simpleblog_router_cache['explode']('/', $simpleblog_router_cache['explode_input'], 1) === 'articles')
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
	}
	if($simpleblog_router_cache['substr'] === 'favicon.php')
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
	}
	if($simpleblog_router_cache['substr'] === 'htmlheaders.php')
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
	}
	if($simpleblog_router_cache['explode']('/', $simpleblog_router_cache['explode_input'], 1) === 'cron')
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
	}
	if($simpleblog_router_cache['explode']('/', $simpleblog_router_cache['explode_input'], 1) === 'tmp')
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
	}

	// rewrite common rules
	if($simpleblog_router_cache['substr'] === '.router.php') // hide script - fake 404 for this script
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
	}
	if($simpleblog_router_cache['substr'] === 'index.php') // hide php
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
	}
	// 404 handle for dirs - rewrite policy don't work, rule removed
	if(is_dir($_SERVER['DOCUMENT_ROOT'] . $simpleblog_router_cache['strtok'])) // 404 handle - for dirs
		if((file_exists($_SERVER['DOCUMENT_ROOT'] . $simpleblog_router_cache['strtok'] . '/index.php')) || (file_exists($_SERVER['DOCUMENT_ROOT'] . $simpleblog_router_cache['strtok'] . '/index.html'))){}else
		{
			include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
		}

	// include maintenance break pattern
	if(file_exists($simpleblog['root_php'] . '/lib/maintenance-break.php')) { include $simpleblog['root_php'] . '/lib/maintenance-break.php'; unset($maintenance_break); }

	// execute cron tasks
	if(file_exists($simpleblog['root_php'] . '/lib/cron.php')) include $simpleblog['root_php'] . '/lib/cron.php';

	// drop cache
	unset($simpleblog_router_cache);
?>