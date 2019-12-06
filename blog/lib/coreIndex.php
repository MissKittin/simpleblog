<?php
	// Simpleblog v2 core functions
	// for main page
	// 11.11.2019

	// deny direct access
	if(php_sapi_name() === 'cli-server')
	{
		if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/coreIndex.php')
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

	// count articles for main page
	function simpleblog_engineIndex($dir, $current_page, $entries_per_page)
	{
		// Usage: simpleblog_engineIndex($simpleblog['root_php'] . '/articles', $current_page, $simpleblog['entries_per_page'])

		$returnArray=array(); // output

		// cache
		global $simpleblog;
		if(empty($simpleblog['cache']['core_files']))
			$simpleblog['cache']['core_files']=scandir($dir); // dump filelist
		$files=$simpleblog['cache']['core_files'];

		$loop_ind=1; // first if in foreach
		foreach(array_reverse($files) as $file)
		{
			if($loop_ind >= ($current_page*$entries_per_page)-($entries_per_page-1)) // omit unwanted entries and start from to eg 1-10, 11-20 etc
				if(($file != '.') && ($file != '..') && (strpos($file, 'public_') === 0)) // include only items with public_ prefix
					array_push($returnArray, $dir . '/' . $file);

			if($loop_ind === $current_page*$entries_per_page) // break if the maximum number of entries has been reached
				break;
			else
				$loop_ind++;
		}

		return $returnArray;
	}

	// count pages - for main page
	function simpleblog_countPages($dir, $current_page, $entries_per_page)
	{
		// Usage: echo simpleblog_countPages($simpleblog['root_php'] . '/articles', $current_page, $simpleblog['entries_per_page']) . "\n"

		// initialize indicators
		$pages_count=1;
		$pages_ind=0;

		$return=''; // output

		// cache
		global $simpleblog;
		if(empty($simpleblog['cache']['core_files']))
			$simpleblog['cache']['core_files']=scandir($dir); // dump filelist
		$files=$simpleblog['cache']['core_files'];

		foreach(array_reverse($files) as $file)
			if(($file != '.') && ($file != '..') && (strpos($file, 'public_') === 0))
			{
				// count how many pages are available
				if($pages_ind === $entries_per_page)
				{
					$pages_count++;
					$pages_ind=1; // must reset this
				}
				else
					$pages_ind++;
			}

		$i=1; // loop indicator
		while($i <= $pages_count)
		{
			if($i == $current_page)
				$return=$return . '<div class="page" id="current_page"><a href="?page='. $i .'">' . $i . '</a></div>';// render current
			else
				$return=$return . '<div class="page"><a href="?page='. $i .'">' . $i . '</a></div>'; // render

			$i++;
		}

		return $return;
	}
?>