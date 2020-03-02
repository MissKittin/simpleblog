<?php
	// Simpleblog v2.1 - main page
	// 03.12.2019
?>
<?php
	// import apache settings
	if(php_sapi_name() != 'cli-server') include 'settings.php';

	// import core functions
	include $simpleblog['root_php'] . '/lib/core.php';

	// import coreIndex functions
	include $simpleblog['root_php'] . '/lib/coreIndex.php';

	// set page number
	if(isset($_GET['page']))
	{
		if(is_numeric($_GET['page']))
		{
			$simpleblog['page']['current_page']=$_GET['page'];
			settype($simpleblog['page']['current_page'], 'integer');
		}
	}
	else
		$simpleblog['page']['current_page']=1;

	// import cacheIndex library
	include $simpleblog['root_php'] . '/lib/cacheIndex.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $simpleblog['title']; ?></title>
		<meta charset="utf-8">
		<?php include $simpleblog['root_php'] . '/lib/htmlheaders.php'; ?>
	</head>
	<body>
		<div id="header">
			<?php include $simpleblog['root_php'] . '/lib/header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $simpleblog['root_php'] . '/lib/headlinks.php'; ?>
		</div>
		<div id="articles">
			<?php
				if($simpleblog['cache']['use_cache']) // cache patch
					include $simpleblog['cache']['cacheIndex']['cache_dir'] . '/' . $simpleblog['page']['current_page'] . '.php';
				else
				{
					$simpleblog['page']['emptyDatabase']=true;
					foreach(simpleblog_engineIndex($simpleblog['root_php'] . '/articles', $simpleblog['page']['current_page'], $simpleblog['entries_per_page']) as $simpleblog['page']['current_article'])
					{
						$simpleblog['page']['emptyDatabase']=false;
						simpleblog_engineCore($simpleblog['page']['current_article'], $simpleblog['taglinks'], $simpleblog['postlinks'], $simpleblog['datelinks']);
					}
					if($simpleblog['page']['emptyDatabase']) echo $simpleblog['emptyLabel'];
				}
			?>
		</div>
		<div id="pages">
			<?php
				if($simpleblog['cache']['use_cache']) // cache patch
					echo simpleblog_cacheIndex_countPages($simpleblog['page']['current_page']) . "\n";
				else
				{
					if(!$simpleblog['page']['emptyDatabase']) echo simpleblog_countPages($simpleblog['root_php'] . '/articles', $simpleblog['page']['current_page'], $simpleblog['entries_per_page']) . "\n";
				}
			?>
		</div>
		<div id="footer">
			<?php include $simpleblog['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>
<?php if(isset($simpleblog['execTime'])) error_log('Simpleblog execution time: ' . (microtime(true) - $simpleblog['execTime']) . 's, max mem used: ' . memory_get_peak_usage() . 'B', 0); ?>