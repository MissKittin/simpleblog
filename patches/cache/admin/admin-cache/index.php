<?php header('X-Frame-Options: DENY'); ?>
<?php
	// Admin panel for simpleblog - cache patch section
	// 17.01.2020
	$module['id']='admin-cache';

	// import settings
	include '../admin-settings.php';

	// login subsystem
	include $adminpanel['root_php'] . '/lib/login/login.php';

	// import core library (for cacheIndex)
	include $adminpanel['root_php'] . '/lib/core.php';

	// import coreTag library (for cacheTag) [$simpleblog array used]
	if(file_exists($adminpanel['path']['cms_tags'] . '/index.php'))
		include $simpleblog['root_php'] . '/lib/coreTag.php';

	// import cacheIndex library
	include $adminpanel['root_php'] . '/lib/cacheIndex.php';
	$cacheIndexPath=$simpleblog['cache']['cacheIndex']['cache_dir'];

	// generate cache
	if(isset($_GET['generate']))
		if(($_GET['generate'] === 'index') && (adminpanel_csrf_checkToken('get')))
		{
			if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();
			if(!file_exists($simpleblog['cache']['cacheIndex']['cache_dir']))
				mkdir($simpleblog['cache']['cacheIndex']['cache_dir'], 0755, true);
			file_put_contents($simpleblog['cache']['cacheIndex']['cache_dir'] . '/generate_cache', '');
			include $adminpanel['root_php'] . '/lib/cacheIndex.php'; // reinclude
		}

	if(file_exists($adminpanel['path']['cms_tags'] . '/index.php'))
	{
		// import cacheTag library
		include $adminpanel['root_php'] . '/lib/cacheTag.php';

		// generate cache
		if(isset($_GET['generate']))
			if(($_GET['generate'] === 'tag') && (adminpanel_csrf_checkToken('get')))
			{
				if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();
				if(!file_exists($simpleblog['cache']['cacheTag']['cache_dir']))
					mkdir($simpleblog['cache']['cacheTag']['cache_dir'], 0755, true);
				file_put_contents($simpleblog['cache']['cacheTag']['cache_dir'] . '/generate_cache', '');
				include $adminpanel['root_php'] . '/lib/cacheTag.php'; // reinclude
			}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Cache</title>
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
			<h3>Cache</h3>
		</div>
		<div id="content">
			<div style="overflow: auto;">
				<div class="button button_in_row"><a href="?generate=index&<?php echo adminpanel_csrf_printToken('parameter'); ?>=<?php echo adminpanel_csrf_printToken('value'); ?>">Generate index cache</a></div>
				<?php if(file_exists($adminpanel['path']['cms_tags'] . '/index.php')) { ?>
					<div class="button button_in_row"><a href="?generate=tag&<?php echo adminpanel_csrf_printToken('parameter'); ?>=<?php echo adminpanel_csrf_printToken('value'); ?>">Generate tags cache</a></div>
				<?php } ?>
			</div>
			<?php if(file_exists($cacheIndexPath)) { ?>
				<hr>
				<h3>Posts cache</h3>
				<?php
					$cacheIndexEmpty=true;
					foreach(new directoryIterator($cacheIndexPath) as $postCache)
						if(!$postCache->isDot())
						{
							echo $postCache . ' ' . date('d.m.Y H:i:s', $postCache->getMTime()) . '<br>';
							$cacheIndexEmpty=false;
						}
					if($cacheIndexEmpty) echo 'Cache empty';
				?>
			<?php } ?>
			<?php if(file_exists($simpleblog['cache']['cacheTag']['cache_dir'] . '/tags.php')) { ?>
				<h3>Tag cache</h3>
				<?php echo date('d.m.Y H:i:s', filemtime($simpleblog['cache']['cacheTag']['cache_dir'] . '/tags.php')); ?>
			<?php } ?>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>