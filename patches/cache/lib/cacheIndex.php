<?php
	// Simpleblog v2.1 cache patch for index, 15.01.2020
	// exports $simpleblog['cache']['use_cache'] flag and simpleblog_cacheIndex_countPages() function
	// core.php must be imported first
	// run cache generator by $simpleblog['cache']['cacheIndex']['cache_dir'] . '/generate_cache'

	// require index settings:
	//	$simpleblog['page']['current_page']
	// used settings:
	//	$simpleblog['root_php']
	//	$simpleblog['taglinks'], $simpleblog['postlinks'], $simpleblog['datelinks'] /* cache generator only */
	//	$simpleblog['entries_per_page'] /* cache generator only */

	// settings for page switches generator
	$simpleblog['cache']['cacheIndex']['cache_dir']=$simpleblog['root_php'] . '/tmp/posts_cache';

	// deny direct access
	if(php_sapi_name() === 'cli-server')
	{
		if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/cacheIndex.php')
		{
			include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
		}
	}
	else
	{
		if(!isset($simpleblog))
		{
			include 'prevent-index.php'; exit();
		}
	}

	// cache generator
	if(file_exists($simpleblog['cache']['cacheIndex']['cache_dir'] . '/generate_cache'))
	{
		// private variables
		$simpleblog['cache']['cacheIndex']['articles_dir']=$simpleblog['root_php'] . '/articles';

		// clear old cache
		foreach(scandir($simpleblog['cache']['cacheIndex']['cache_dir']) as $cache)
			if(($cache != '.') && ($cache != '..'))
				unlink($simpleblog['cache']['cacheIndex']['cache_dir'] . '/' . $cache);

		// set indicators
		$page=1;
		$loop_ind=1;

		// render articles and pack it into cache
		foreach(array_reverse(scandir($simpleblog['cache']['cacheIndex']['articles_dir'])) as $file)
			if(($file != '.') && ($file != '..') && (strpos($file, 'public_') === 0)) // include only items with public_ prefix
			{
				// catch echo() and append to cache file
				ob_start();
				simpleblog_engineCore($simpleblog['cache']['cacheIndex']['articles_dir'] . '/' . $file, $simpleblog['taglinks'], $simpleblog['postlinks'], $simpleblog['datelinks']);
				file_put_contents($simpleblog['cache']['cacheIndex']['cache_dir'] . '/' . $page . '.php', ob_get_contents(), FILE_APPEND);
				ob_end_clean();

				// limit entries
				if($loop_ind >= $simpleblog['entries_per_page'])
				{
					$page++;
					$loop_ind=1;
				}
				else
					$loop_ind++;
			}
	}

	// use_cache flag
	$simpleblog['cache']['use_cache']=false;
	if(file_exists($simpleblog['cache']['cacheIndex']['cache_dir'] . '/' . $simpleblog['page']['current_page'] . '.php')) $simpleblog['cache']['use_cache']=true;

	// page switches generator (not available if cache is empty)
	if($simpleblog['cache']['use_cache'])
	{
		function simpleblog_cacheIndex_countPages($current_page)
		{
			// count cached pages

			global $simpleblog; // cache_dir is defined above
			$return=''; // output
			$i=1;

			foreach(scandir($simpleblog['cache']['cacheIndex']['cache_dir']) as $cache)
				if(($cache != '.') && ($cache != '..'))
				{
					if($current_page . '.php' === $cache)
						$return=$return . '<div class="page" id="current_page"><a href="?page='. $i .'">' . $i . '</a></div>'; // render current
					else
						$return=$return . '<div class="page"><a href="?page='. $i .'">' . $i . '</a></div>'; // render
					$i++; // iterate indicator
				}

			return $return;
		}
	}
?>