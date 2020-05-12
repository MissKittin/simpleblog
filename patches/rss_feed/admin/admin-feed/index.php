<?php header('X-Frame-Options: DENY'); ?>
<?php
	// Admin panel for simpleblog - feed patch section
	// 15.04.2020
	$module['id']='admin-feed';

	// import settings
	include '../admin-settings.php';

	// login subsystem
	include $adminpanel['root_php'] . '/lib/login/login.php';

	// import main feed ($simpleblog array used)
	$feed_defineOnly=true;
	include $simpleblog['root_php'] . '/feed/index.php';

	// generate cache
	if((isset($_GET['generate'])) && (adminpanel_csrf_checkToken('get')))
	{
		if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();
		if(!file_exists($simpleblog['cache']['cacheFeed']['cache_dir']))
				mkdir($simpleblog['cache']['cacheFeed']['cache_dir'], 0755, true);
		file_put_contents($simpleblog['cache']['cacheFeed']['cache_dir'] . '/generate_cache', '');
		include $simpleblog['root_php'] . '/feed/index.php';
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>RSS feed cache</title>
		<meta charset="utf-8">
		<?php include $adminpanel['root_php'] . '/lib/htmlheaders.php'; ?>
	</head>
	<body>
		<div id="header">
			<?php include $adminpanel['root_php'] . '/lib/header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $adminpanel['root_php'] . '/lib/menu/' . $adminpanel['menu_module'] . '/menu.php'; ?>
		</div>
		<div id="content_header">
			<h3>RSS feed cache</h3>
		</div>
		<div id="content">
			<div style="overflow: auto;">
				<div class="button button_in_row"><a href="?generate&<?php echo adminpanel_csrf_printToken('parameter'); ?>=<?php echo adminpanel_csrf_printToken('value'); ?>">Generate cache</a></div>
			</div>
			<h3>Cache:</h3>
			<?php
				if(file_exists($simpleblog['cache']['cacheFeed']['cache_dir'] . '/feed.php'))
					echo 'feed.php ' . date('d.m.Y H:i:s', filemtime($simpleblog['cache']['cacheFeed']['cache_dir'] . '/feed.php'));
				else
					echo 'not cached';
			?>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>