<?php
	// Maintenace break pattern
	// 09.11.2019
	// admin update 23.11.2019
	// Apache mod and cache 25.11.2019
	// Pattern file 26.11.2019

	// settings (now defined in settings)
	//$maintenace_break['enabled']=false;
	//$maintenace_break['allowed_ip']='127.0.0.1';
	$maintenace_break['bin']=$simpleblog['root_html'] . '/lib/maintenace-break.php';
	$maintenace_break['pattern']=$simpleblog['root_php'] . '/lib/maintenace-break-pattern.php';

	// deny direct access - for apache
	if(php_sapi_name() != 'cli-server')
		if(!isset($simpleblog))
		{
			include 'prevent-index.php'; exit();
		}

	// deny direct access - for php-cli server
	if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/core.php')
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
	}

	// Apache version don't have $simpleblog_router_cache
	if(php_sapi_name() != 'cli-server')
		$simpleblog_router_cache['strtok']=strtok($_SERVER['REQUEST_URI'], '?');

	// Create cache for better performance
	$maintenace_break['if_cache']=substr($simpleblog_router_cache['strtok'], strlen($simpleblog['root_html']));

	// set client_ip variable
	if(!isset($maintenace_break['client_ip'])) $maintenace_break['client_ip']=$_SERVER['REMOTE_ADDR'];

	// new explode function
	$maintenace_break['explode']=function($a, $b, $offset)
	{
		$array=explode($a, $b);
		if(isset($array[$offset]))
			return $array[$offset];
		return false;
	};

	// check if maintenace pattern is enabled
	if(($maintenace_break['enabled']) && ($maintenace_break['client_ip'] != $maintenace_break['allowed_ip']))
	{
		if(
			($maintenace_break['explode']('/', $maintenace_break['if_cache'], 2) != 'favicon') &&
			($maintenace_break['explode']('/', $maintenace_break['if_cache'], 1) != 'media') &&
			($maintenace_break['explode']('/', $maintenace_break['if_cache'], 1) != 'skins') &&
			($maintenace_break['explode']('/', $maintenace_break['if_cache'], 1) != 'admin')
		)
		{
			include($maintenace_break['pattern']);
			exit();
		}
	}

	// clean
	unset($maintenace_break);
	if(php_sapi_name() != 'cli-server') unset($simpleblog_router_cache);
?>
