<?php
	// settings
	$cms_root='/blog'; // directory (for html)
	$cms_root_php=$_SERVER['DOCUMENT_ROOT'] . $cms_root; // directory (for php)
	$page_title='Simpleblog'; // <title>
	$entries_per_page=10;
	$taglinks=true; // enable/disable tag as link
	$skin='default'; // skin name
	$cms_fake_notfound=true; // use http_response_code(404)
?>
<?php
	// run cron
	include $cms_root_php . '/cron.php';
?>