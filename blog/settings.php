<?php
	// Simpleblog v1 16.04.2019
	// Simpleblog v2 11.11.2019
	// Simpleblog v2.1 03.12.2019
	// Simpleblog v2.2 06.04.2020
	// Edit lines 9-36

	// start execution time monitor (uncomment this to enable)
	//$simpleblog['execTime']=microtime(true); 

	// settings - cms
	$simpleblog['root_html']='/blog'; // directory (for html)
	$simpleblog['root_php']=$_SERVER['DOCUMENT_ROOT'] . $simpleblog['root_html']; // directory (for php)
	$simpleblog['startup_page']='posts'; // for $simpleblog['root_php']/index.php
	$simpleblog['title']='Simpleblog'; // <title>
	$simpleblog['html_lang']='en'; // <html lang="value">
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

	// backward compatibility with v1
	// uncomment three lines below if you have old skins or articles
	//$cms_root=$simpleblog['root_html'];
	//$cms_root_php=$simpleblog['root_php'];
	//$page_title=$simpleblog['title'];
?>
<?php
	// include maintenance break pattern
	if(file_exists($simpleblog['root_php'] . '/lib/maintenance-break.php')) { include $simpleblog['root_php'] . '/lib/maintenance-break.php'; unset($maintenance_break); }

	// execute cron tasks
	if(file_exists($simpleblog['root_php'] . '/lib/cron.php')) include $simpleblog['root_php'] . '/lib/cron.php';
?>