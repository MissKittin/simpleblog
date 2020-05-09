<?php
	// Simpleblog v2.1 core functions
	// for tag subsystem
	// modified simpleblog_engineTag() and added simpleblog_countTagPages()
	// 04.12.2019
	// optimizations 06.04.2020

	// deny direct access
	if(php_sapi_name() === 'cli-server')
	{
		if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/coreTag.php')
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

	// count articles for tag
	function simpleblog_engineTag($dir, $action, $selected_tag=false, $current_page=false, $entries_per_page=false)
	{
		// Usage: simpleblog_engineTag($simpleblog['root_php'] . '/articles', 'render', $_GET['tag'])
		// or
		// simpleblog_engineTag($simpleblog['root_php'] . '/articles', 'list')

		// import variables
		global $simpleblog;
		global $cms_root;
		global $cms_root_php;

		$returnArray=array(); // output

		// cache - scandir
		global $simpleblog;
		if(empty($simpleblog['cache']['core_files']))
			$simpleblog['cache']['core_files']=scandir($dir, 1); // dump filelist, sort descending
		$files=$simpleblog['cache']['core_files'];

		switch($action)
		{
			case 'list':
				$tags=array(); $i=0; // bypass if tag is already listed

				foreach($files as $file)
					if(substr($file, 0, 6) === 'public')
					{
						include $dir . '/' . $file;
						foreach(explode('#', $art_tags) as $tag)
						{
							$tag=trim($tag); // remove space at the end
							if(($tag != '') && (!in_array($tag, $tags))) // omit empty value
							{
								array_push($returnArray, $tag);
								$tags[$i]=$tag; ++$i;
								$no_tags=false; // dont print 'Empty'
							}
						}
					}

				break;

			case 'render':
				$loop_ind=1; // second if in second foreach
				foreach($files as $file)
					if(substr($file, 0, 6) === 'public') // include only items with public_ prefix
					{
						include $dir . '/' . $file;
						foreach(explode('#', $art_tags) as $tag)
						{
							$tag=trim($tag); // remove space at the end
							if('#' . $tag === $selected_tag)
							{
								if($loop_ind >= ($current_page*$entries_per_page)-($entries_per_page-1)) // omit unwanted entries and start from to eg 1-10, 11-20 etc
									array_push($returnArray, $dir . '/' . $file);

								if($loop_ind === $current_page*$entries_per_page) // break if the maximum number of entries has been reached
									break 2;
								else
									++$loop_ind;
							}
						}
					}

				break;
		}

		return $returnArray;
	}

	// count pages - for tag
	function simpleblog_countTagPages($dir, $selected_tag, $current_page, $entries_per_page)
	{
		// Usage: echo simpleblog_countTagPages($simpleblog['root_php'] . '/articles', $_GET['tag'], $simpleblog['page']['current_page'], $simpleblog['entries_per_page']) . "\n"

		// import variables
		global $simpleblog;
		global $cms_root;
		global $cms_root_php;

		$return=''; // output

		// cache - scandir
		global $simpleblog;
		if(empty($simpleblog['cache']['core_files']))
			$simpleblog['cache']['core_files']=scandir($dir, 1); // dump filelist, sort descending
		$files=$simpleblog['cache']['core_files'];

		// select articles with tag
		$selected_articles=array();
		foreach($files as $file)
			if(substr($file, 0, 6) === 'public') // include only items with public_ prefix
			{
				include $dir . '/' . $file;
				foreach(explode('#', $art_tags) as $tag)
				{
					$tag=trim($tag); // remove space at the end
					if('#' . $tag === $selected_tag)
						array_push($selected_articles, $dir . '/' . $file);
				}
			}

		// count pages
		$pages_count=ceil(count($selected_articles)/$entries_per_page);

		// render buttons
		$getTag=urlencode($selected_tag); // must be encoded
		$i=1; // loop indicator
		while($i <= $pages_count)
		{
			if($i == $current_page)
				$return=$return . '<div class="page" id="current_page"><a href="?tag=' . $getTag . '&page='. $i .'">' . $i . '</a></div>';// render current
			else
				$return=$return . '<div class="page"><a href="?tag=' . $getTag . '&page='. $i .'">' . $i . '</a></div>'; // render

			++$i;
		}

		return $return;
	}
?>