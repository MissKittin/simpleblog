<?php
	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/admin-settings.php')
	{
		include './lib/prevent-index.php'; exit();
	}
?>
<?php
	// import simpleblog settings
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
	//$adminpanel['nocurl']=true; // for admin-files

	// paths - dirs
	$adminpanel['path']['cms_root']=$simpleblog['root_php'];
	$adminpanel['path']['cms_root_html']=$simpleblog['root_html'];
	$adminpanel['path']['articles']=$simpleblog['root_php'] . '/articles';
	$adminpanel['path']['pages']=$simpleblog['root_php'] . '/pages';
	$adminpanel['path']['pages_html']=$simpleblog['root_html'] . '/pages';
	$adminpanel['path']['media']=$simpleblog['root_php'] . '/media';
	$adminpanel['path']['media_html']=$simpleblog['root_html'] . '/media';
	$adminpanel['path']['cron']=$simpleblog['root_php'] . '/cron';
	$adminpanel['path']['tmp']=$simpleblog['root_php'] . '/tmp';
	$adminpanel['path']['favicon']=$simpleblog['root_php'] . '/lib/favicon';
	$adminpanel['path']['favicon_html']=$simpleblog['root_html'] . '/lib/favicon';
	$adminpanel['path']['skins']=$simpleblog['root_php'] . '/skins';
	$adminpanel['path']['skins_html']=$simpleblog['root_html'] . '/skins';
	$adminpanel['path']['cms_tags']=$simpleblog['root_php'] . '/tag';
	$adminpanel['path']['cms_tags_html']=$simpleblog['root_html'] . '/tag';

	// paths - files
	$adminpanel['path']['header']=$simpleblog['root_php'] . '/lib/header.php';
	$adminpanel['path']['headlinks']=$simpleblog['root_php'] . '/lib/headlinks.php';
	$adminpanel['path']['footer']=$simpleblog['root_php'] . '/lib/footer.php';
	$adminpanel['path']['htmlheaders']=$simpleblog['root_php'] . '/lib/htmlheaders.php';
	$adminpanel['path']['mbp']=$simpleblog['root_php'] . '/lib/maintenance-break-pattern.php';
	$adminpanel['path']['cron_bin']=$simpleblog['root_php'] . '/lib/cron.php';

	// settings for sec_bruteforce.php library
	$adminpanel_loginBan['enabled']=false;
	$adminpanel_loginBan['DBFile']=$adminpanel['path']['tmp'] . '/admin_banned_ips.php';
	$adminpanel_loginBan['DBFile_dynamic']=true;
	$adminpanel_loginBan['attempts']=5;
	$adminpanel_loginBan['time']=3600;
?>