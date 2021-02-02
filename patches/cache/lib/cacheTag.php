<?php
	// Simpleblog v2.1 cache patch for tag, 16.01.2020
	// exports $simpleblog['cache']['use_cache'] flag
	// coreTag.php must be imported first
	// run cache generator by $simpleblog['cache']['cacheTag']['cache_dir'] . '/generate_cache'

	// used settings:
	//	$simpleblog['root_php']

	// settings for page switches generator
	$simpleblog['cache']['cacheTag']['cache_dir']=$simpleblog['root_php'] . '/tmp/tag_cache';

	// deny direct access
	if(!isset($simpleblog))
	{
		include './prevent-index.php'; exit();
	}

	// cache generator
	if(file_exists($simpleblog['cache']['cacheTag']['cache_dir'] . '/generate_cache'))
	{
		// private variables
		$simpleblog['cache']['cacheTag']['articles_dir']=$simpleblog['root_php'] . '/articles';

		// clear old cache
		unlink($simpleblog['cache']['cacheTag']['cache_dir'] . '/generate_cache');
		@unlink($simpleblog['cache']['cacheTag']['cache_dir'] . '/tags.php');

		// render articles and pack it into cache
		foreach(simpleblog_engineTag($simpleblog['cache']['cacheTag']['articles_dir'], 'list') as $tag)
			file_put_contents($simpleblog['cache']['cacheTag']['cache_dir'] . '/tags.php', '<a class="taglink" href="?tag=' . urlencode('#' . $tag) . '">#' . $tag . '</a><br>', FILE_APPEND);
	}

	// use_cache flag
	$simpleblog['cache']['use_cache']=false;
	if(file_exists($simpleblog['cache']['cacheTag']['cache_dir'] . '/tags.php')) $simpleblog['cache']['use_cache']=true;
?>