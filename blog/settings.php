<?php
	// Simpleblog v2
	// 11.11.2019
	// Edit lines 7-23

	// start execution time monitor (uncomment this to enable)
	//$simpleblog['execTime']=microtime(true); 

	// settings - cms
	$simpleblog['root_html']='/blog'; // directory (for html)
	$simpleblog['root_php']=$_SERVER['DOCUMENT_ROOT'] . $simpleblog['root_html']; // directory (for php)
	$simpleblog['title']='Simpleblog'; // <title>
	$simpleblog['short_title']='SimpleblogShortTitle'; // for admin panel
	$simpleblog['entries_per_page']=10;
	$simpleblog['taglinks']=true; // enable/disable tag as link
	$simpleblog['skin']='default'; // skin name
	$simpleblog['fake_notfound']=true; // use http_response_code(404)

	// settings - maintenace break pattern
	$maintenace_break['enabled']=false;
	$maintenace_break['allowed_ip']='127.0.0.1';

	// backward compatibility with v1
	// uncomment three lines below if you have old skins or articles
	//$cms_root=$simpleblog['root_html'];
	//$cms_root_php=$simpleblog['root_php'];
	//$page_title=$simpleblog['title'];
?>
<?php
	// include maintenace break pattern
	if(file_exists($simpleblog['root_php'] . '/lib/maintenace-break.php')) { include $simpleblog['root_php'] . '/lib/maintenace-break.php'; unset($maintenace_break); }

	// execute cron tasks
	if(file_exists($simpleblog['root_php'] . '/lib/cron.php')) include $simpleblog['root_php'] . '/lib/cron.php';
?>