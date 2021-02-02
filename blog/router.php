<?php
	// Simpleblog - router script
	// Denied: *.php [in uri], articles/*, cron/* and tmp/*

	// include settings.php (if you are using softlinks, you may need to tweak this)
	include __DIR__ . '/settings.php';

	// new explode function
	$simpleblog_router_cache['explode']=function($a, $b, $offset)
	{
		$array=explode($a, $b);
		if(isset($array[$offset]))
			return $array[$offset];
		return false;
	};

	// router cache
	$simpleblog_router_cache['strtok']=strtok($_SERVER['REQUEST_URI'], '?');
	$simpleblog_router_cache['substr']=substr($simpleblog_router_cache['strtok'], strrpos($simpleblog_router_cache['strtok'], '/') + 1);
	$simpleblog_router_cache['explode_input']=substr($simpleblog_router_cache['strtok'], strlen($simpleblog['root_html']));
	$simpleblog_router_cache['explode_output']=$simpleblog_router_cache['explode']('/', $simpleblog_router_cache['explode_input'], 1);

	// simpleblog rules
	if($simpleblog_router_cache['explode']('/', $simpleblog_router_cache['explode_input'], 1) === 'articles')	// deny access to /articles
		{ include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit(); }
	if($simpleblog_router_cache['explode_output'] === 'cron')													// deny access to cron
		{ include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit(); }
	if($simpleblog_router_cache['explode_output'] === 'tmp')													// deny access to tmps
		{ include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit(); }

	// rewrite common rules
	if($simpleblog_router_cache['substr'] === '.router.php')													// hide script - fake 404 for this script
		{ include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit(); }
	if(strstr($simpleblog_router_cache['substr'], '.') === '.php')												// hide php
		{ include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit(); }
																												// 404 handle for dirs - rewrite policy don't work, rule removed
	if(is_dir($_SERVER['DOCUMENT_ROOT'] . $simpleblog_router_cache['strtok']))									// 404 handle - for dirs
		if((file_exists($_SERVER['DOCUMENT_ROOT'] . $simpleblog_router_cache['strtok'] . '/index.php')) || (file_exists($_SERVER['DOCUMENT_ROOT'] . $simpleblog_router_cache['strtok'] . '/index.html'))){}else
			{ include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit(); }

	// drop cache
	unset($simpleblog_router_cache);

	// drop settings (not necessary)
	//unset($simpleblog);
?>