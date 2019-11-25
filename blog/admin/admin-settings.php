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
	$adminpanel_credentials['login']='simpleblog';
	$adminpanel_credentials['password']='$2y$10$ykZDBBYmmBDBuRtZMkjKuefeqUB2IdOc5B2G/Zr8pLibuibLLkPs2';

	// settings for admin panel
	$adminpanel['root_html']=$simpleblog['root_html'] . '/admin';
	$adminpanel['root_php']=$simpleblog['root_php'] . '/admin';
	$adminpanel['skin']='material-green';
	$adminpanel['menu_module']='material';
	$adminpanel['login_form']='material';

	// paths - dirs
	$adminpanel['path']['articles']=$simpleblog['root_php'] . '/articles';
	$adminpanel['path']['pages']=$simpleblog['root_php'] . '/pages';
	$adminpanel['path']['pages_html']=$simpleblog['root_html'] . '/pages';
	$adminpanel['path']['media']=$simpleblog['root_php'] . '/media';
	$adminpanel['path']['cron']=$simpleblog['root_php'] . '/cron';
	$adminpanel['path']['tmp']=$simpleblog['root_php'] . '/tmp';
	$adminpanel['path']['favicon']=$simpleblog['root_php'] . '/lib/favicon';
	$adminpanel['path']['favicon_html']=$simpleblog['root_html'] . '/lib/favicon';
	$adminpanel['path']['skins']=$simpleblog['root_php'] . '/skins';
	$adminpanel['path']['skins_html']=$simpleblog['root_html'] . '/skins';

	// paths - files
	$adminpanel['path']['header']=$simpleblog['root_php'] . '/lib/header.php';
	$adminpanel['path']['headlinks']=$simpleblog['root_php'] . '/lib/headlinks.php';
	$adminpanel['path']['footer']=$simpleblog['root_php'] . '/lib/footer.php';
	$adminpanel['path']['htmlheaders']=$simpleblog['root_php'] . '/lib/htmlheaders.php';
?>