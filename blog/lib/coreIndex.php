<?php
	// Simpleblog v2 core functions
	// for main page
	// 11.11.2019
	// optimizations 06.04.2020
	// simpleblog_countIndexPages() forceEcho parameter 19.08.2020

	// deny direct access
	if(!isset($simpleblog))
	{
		include './prevent-index.php'; exit();
	}

	// count articles for main page
	function simpleblog_engineIndex($dir, $current_page, $entries_per_page)
	{
		// Usage: simpleblog_engineIndex($simpleblog['root_php'] . '/articles', $current_page, $simpleblog['entries_per_page'])

		$returnArray=array(); // output

		// cache - scandir
		global $simpleblog;
		if(empty($simpleblog['cache']['core_files'])) // dump filelist, sort descending
			$simpleblog['cache']['core_files']=array_filter(scandir($dir, 1), function($file){
				if(strpos($file, 'public_') === 0)
					return true;
				return false;
			});
		$files=$simpleblog['cache']['core_files'];

		$loop_ind=1; // first if in foreach
		foreach($files as $file)
		{
			if($loop_ind >= ($current_page*$entries_per_page)-($entries_per_page-1)) // omit unwanted entries and start from to eg 1-10, 11-20 etc
				array_push($returnArray, $dir . '/' . $file);

			if($loop_ind === $current_page*$entries_per_page) // break if the maximum number of entries has been reached
				break;
			else
				++$loop_ind;
		}

		return $returnArray;
	}

	// count pages - for main page
	function simpleblog_countIndexPages($dir, $current_page, $entries_per_page, $forceEcho)
	{
		// Usage: echo simpleblog_countPages($simpleblog['root_php'] . '/articles', $current_page, $simpleblog['entries_per_page']) . "\n"

		$return=''; // output

		// cache - scandir
		global $simpleblog;
		if(empty($simpleblog['cache']['core_files'])) // dump filelist, sort descending
			$simpleblog['cache']['core_files']=array_filter(scandir($dir, 1), function($file){
				if(strpos($file, 'public_') === 0)
					return true;
				return false;
			});
		$files=$simpleblog['cache']['core_files'];

		$pages_count=ceil(count($files)/$entries_per_page);

		$i=1; // loop indicator
		if($forceEcho)
			while($i <= $pages_count)
			{
				if($i == $current_page)
					echo '<div class="page" id="current_page"><a href="?page='. $i .'">' . $i . '</a></div>'; // render current
				else
					echo '<div class="page"><a href="?page='. $i .'">' . $i . '</a></div>'; // render
	
				++$i;
			}
		else
			while($i <= $pages_count)
			{
				if($i == $current_page)
					$return=$return . '<div class="page" id="current_page"><a href="?page='. $i .'">' . $i . '</a></div>'; // render current
				else
					$return=$return . '<div class="page"><a href="?page='. $i .'">' . $i . '</a></div>'; // render
	
				++$i;
			}

		return $return;
	}
?>