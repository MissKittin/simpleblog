<?php
	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/admin-settings.php')
	{
		include 'lib/prevent-index.php'; exit();
	}
?>
<?php
	// import simpleblog settings
	if(php_sapi_name() != 'cli-server')
		if(!isset($simpleblog))
		{
			$search_settings='settings.php';
			$search_prevent_index='lib/prevent-index.php';
			while((!file_exists($search_settings)) || (!file_exists($search_prevent_index)))
			{
				$search_settings='../' . $search_settings;
				$search_prevent_index='../' . $search_prevent_index;
			}
			include $search_settings;
		}
?>
<?php
	// credentials
	$adminpanel_credentials['login']='YOUR_USERNAME';
	$adminpanel_credentials['password']='YOUR_PASSWORD';

	// settings for admin panel
	$adminpanel['root_html']=$simpleblog['root_html'] . '/admin';
	$adminpanel['root_php']=$simpleblog['root_php'] . '/admin';
	$adminpanel['skin']='material-green';

	// paths
	$adminpanel['path']['articles']=$simpleblog['root_php'] . '/articles';
	$adminpanel['path']['pages']=$simpleblog['root_php'] . '/pages';
	$adminpanel['path']['media']=$simpleblog['root_php'] . '/media';
	$adminpanel['path']['cron']=$simpleblog['root_php'] . '/cron';
	$adminpanel['path']['tmp']=$simpleblog['root_php'] . '/tmp';
?>