<?php
	// admin panel - menu module for material-green theme
	// 13.11.2019
?>
<?php
	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/menu.php')
	{
		include '../../prevent-index.php'; exit();
	}
?>
<?php
	// search modules
	$menu['files']=scandir($adminpanel['root_php']);
	$menu['modules']=array();
	foreach($menu['files'] as $menu['file'])
		if(($menu['file'] != '.') && ($menu['file'] != '..') && (file_exists($adminpanel['root_php'] . '/' . $menu['file'] . '/description.php')))
			array_push($menu['modules'], $menu['file']);

	// main page
	if($module['id'] === 'main')
		echo '<div class="headlink"><a href="' . $adminpanel['root_html'] . '">Status</a><div id="headlink_active"></div></div>'."\n";
	else
		echo '<div class="headlink"><a href="' . $adminpanel['root_html'] . '">Status</a></div>'."\n";

	// modules
	foreach($menu['modules'] as $menu['module'])
	{
		include $adminpanel['root_php'] . '/' . $menu['module'] . '/description.php';
		if($menu['module'] === $module['id'])
			echo '<div class="headlink"><a href="' . $adminpanel['root_html'] . '/' . $menu['module'] . '">' . $module['description'] . '</a><div id="headlink_active"></div></div>'."\n";
		else
			echo '<div class="headlink"><a href="' . $adminpanel['root_html'] . '/' . $menu['module'] . '">' . $module['description'] . '</a></div>'."\n";
	}

	// clean
	unset($menu);
	unset($module);
?>