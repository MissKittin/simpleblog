<?php
	// Maintenance break pattern
	// 09.11.2019
	// admin update 23.11.2019
	// Apache mod and cache 25.11.2019
	// Pattern file 26.11.2019

	// settings (now defined in settings)
	//$maintenance_break['enabled']=false;
	//$maintenance_break['allowed_ip']='127.0.0.1';
	$maintenance_break['bin']=$simpleblog['root_html'] . '/lib/maintenance-break.php';
	$maintenance_break['pattern']=$simpleblog['root_php'] . '/lib/maintenance-break-pattern.php';

	// deny direct access
	if(php_sapi_name() != 'cli-server')
	{
		if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/maintenance-break.php')
		{
			include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
		}
	}
	else
	{
		if(!isset($simpleblog))
		{
			include 'prevent-index.php'; exit();
		}
	}

	// Apache version don't have $simpleblog_router_cache
	if(php_sapi_name() != 'cli-server')
		$simpleblog_router_cache['strtok']=strtok($_SERVER['REQUEST_URI'], '?');

	// Create cache for better performance
	$maintenance_break['if_cache']=substr($simpleblog_router_cache['strtok'], strlen($simpleblog['root_html']));

	// set client_ip variable
	if(!isset($maintenance_break['client_ip'])) $maintenance_break['client_ip']=$_SERVER['REMOTE_ADDR'];

	// new explode function
	$maintenance_break['explode']=function($a, $b, $offset)
	{
		$array=explode($a, $b);
		if(isset($array[$offset]))
			return $array[$offset];
		return false;
	};

	// check if maintenance pattern is enabled
	if(($maintenance_break['enabled']) && ($maintenance_break['client_ip'] != $maintenance_break['allowed_ip']))
	{
		if(
			($maintenance_break['explode']('/', $maintenance_break['if_cache'], 2) != 'favicon') &&
			($maintenance_break['explode']('/', $maintenance_break['if_cache'], 1) != 'media') &&
			($maintenance_break['explode']('/', $maintenance_break['if_cache'], 1) != 'skins') &&
			($maintenance_break['explode']('/', $maintenance_break['if_cache'], 1) != 'admin')
		)
		{
			include($maintenance_break['pattern']);
			exit();
		}
	}

	// clean
	unset($maintenance_break);
	if(php_sapi_name() != 'cli-server') unset($simpleblog_router_cache);
?>