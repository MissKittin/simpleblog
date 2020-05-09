<?php
	// Simpleblog v2.1 core functions
	// for post subsystem
	// 04.12.2019
	// optimizations 06.04.2020

	// deny direct access
	if(php_sapi_name() === 'cli-server')
	{
		if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/corePost.php')
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

	// convert ID to filename
	function simpleblog_postId2filename($dir, $id)
	{
		// Usage: simpleblog_postId2filename($simpleblog['root_php'] . '/articles', $_GET['id'])

		if(is_numeric($id))
		{
			// cache - scandir
			global $simpleblog;
			if(empty($simpleblog['cache']['core_files'])) // dump filelist, sort descending
				$simpleblog['cache']['core_files']=array_filter(scandir($dir, 1), function($file){
					if(strpos($file, 'public_') === 0)
						return true;
					return false;
				});
			$files=$simpleblog['cache']['core_files'];

			// get addressing
			foreach($files as $file)
				if(strpos($file, 'public_') === 0)
					break;

			// calculate file name
			$file=strlen(str_replace(['public_', '.php'], '', $file))-strlen($id);
			$filePrefix=''; // declare
			for($i=0; $i<$file; ++$i)
				$filePrefix.=0;

			// check if file exists
			if(!file_exists($dir . '/public_' . $filePrefix . $id . '.php'))
				return false;

			// return file name
			return $dir . '/public_' . $filePrefix . $id . '.php';
		}
		return false;
	}

	// count articles by date
	function simpleblog_enginePost($dir, $selected_date, $current_page=false, $entries_per_page=false)
	{
		// Usage: simpleblog_enginePost($simpleblog['root_php'] . '/articles', $_GET['date'], $simpleblog['page']['current_page'], $simpleblog['entries_per_page'])

		// globals
		global $simpleblog;
		global $cms_root;
		global $cms_root_php;

		$returnArray=array(); // prepare return array

		// cache - scandir
		global $simpleblog;
		if(empty($simpleblog['cache']['core_files'])) // dump filelist, sort descending
			$simpleblog['cache']['core_files']=array_filter(scandir($dir, 1), function($file){
				if(strpos($file, 'public_') === 0)
					return true;
				return false;
			});
		$files=$simpleblog['cache']['core_files'];

		// cache - date - convert to readable format
		if(empty($simpleblog['cache']['core_date']))
			$simpleblog['cache']['core_date']=explode('.', $selected_date);
		$date=$simpleblog['cache']['core_date'];

		// select mode
		if(isset($date[2])) $mode='dmy';
		else if(isset($date[1])) $mode='my';
		else $mode='y';

		// switch mode
		$loop_ind=1; // second if in second foreach
		switch($mode)
		{
			case 'dmy':
				foreach($files as $file)
				{
					include $dir . '/' . $file;
					if($art_date === $date[0] . '.' . $date[1] . '.' . $date[2])
					{
						if($loop_ind >= ($current_page*$entries_per_page)-($entries_per_page-1)) // omit unwanted entries and start from to eg 1-10, 11-20 etc
							array_push($returnArray, $dir . '/' . $file);

						if($loop_ind === $current_page*$entries_per_page) // break if the maximum number of entries has been reached
							break;
						else
							++$loop_ind;
					}
				}
				break;

			case 'my':
				foreach($files as $file)
				{
					include $dir . '/' . $file;
					$exploded_art_date=explode('.', $art_date);
					if($exploded_art_date[1] . '.' . $exploded_art_date[2] === $date[0] . '.' . $date[1])
					{
						if($loop_ind >= ($current_page*$entries_per_page)-($entries_per_page-1)) // omit unwanted entries and start from to eg 1-10, 11-20 etc
							array_push($returnArray, $dir . '/' . $file);

						if($loop_ind === $current_page*$entries_per_page) // break if the maximum number of entries has been reached
							break;
						else
							++$loop_ind;
					}
				}
				break;

			case 'y':
				foreach($files as $file)
				{
					include $dir . '/' . $file;
					$exploded_art_date=explode('.', $art_date);
					if($exploded_art_date[2] === $date[0])
					{
						if($loop_ind >= ($current_page*$entries_per_page)-($entries_per_page-1)) // omit unwanted entries and start from to eg 1-10, 11-20 etc
							array_push($returnArray, $dir . '/' . $file);

						if($loop_ind === $current_page*$entries_per_page) // break if the maximum number of entries has been reached
							break;
						else
							++$loop_ind;
					}
				}
				break;
		}

		return $returnArray;
	}

	// count pages - for post
	function simpleblog_countPostPages($dir, $selected_date, $current_page, $entries_per_page)
	{
		// Usage: echo simpleblog_countPostPages($simpleblog['root_php'] . '/articles', $_GET['date'], $simpleblog['page']['current_page'], $simpleblog['entries_per_page']) . "\n"

		// import variables
		global $simpleblog;
		global $cms_root;
		global $cms_root_php;

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

		// cache - date - convert to readable format
		if(empty($simpleblog['cache']['core_date']))
			$simpleblog['cache']['core_date']=explode('.', $selected_date);
		$date=$simpleblog['cache']['core_date'];

		$selected_articles=array(); // decalre temp array

		// select mode
		if(isset($date[2])) $mode='dmy';
		else if(isset($date[1])) $mode='my';
		else $mode='y';

		// switch mode
		switch($mode)
		{
			case 'dmy':
				foreach($files as $file)
				{
					include $dir . '/' . $file;
					if($art_date === $date[0] . '.' . $date[1] . '.' . $date[2])
						array_push($selected_articles, $dir . '/' . $file);
				}
				break;

			case 'my':
				foreach($files as $file)
				{
					include $dir . '/' . $file;
					$exploded_art_date=explode('.', $art_date);
					if($exploded_art_date[1] . '.' . $exploded_art_date[2] === $date[0] . '.' . $date[1])
						array_push($selected_articles, $dir . '/' . $file);
				}
				break;

			case 'y':
				foreach($files as $file)
				{
					include $dir . '/' . $file;
					$exploded_art_date=explode('.', $art_date);
					if($exploded_art_date[2] === $date[0])
						array_push($selected_articles, $dir . '/' . $file);
				}
				break;
		}

		// count pages
		$pages_count=ceil(count($selected_articles)/$entries_per_page);

		// render buttons
		$i=1; // loop indicator
		while($i <= $pages_count)
		{
			if($i == $current_page)
				$return=$return . '<div class="page" id="current_page"><a href="?date=' . $selected_date . '&page='. $i .'">' . $i . '</a></div>';// render current
			else
				$return=$return . '<div class="page"><a href="?date=' . $selected_date . '&page='. $i .'">' . $i . '</a></div>'; // render

			++$i;
		}

		return $return;
	}
?>