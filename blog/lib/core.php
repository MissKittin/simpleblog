<?php
	// Simpleblog v2 core functions
	// for main page and tag subsystem
	// 11.11.2019

	// deny direct access - for apache
	if(php_sapi_name() != 'cli-server')
		if(!isset($simpleblog))
		{
			include 'prevent-index.php'; exit();
		}

	// deny direct access - for php-cli server
	if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/core.php')
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
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

	// count pages for tag
	function simpleblog_engineTag($dir, $action, $selected_tag)
	{
		// Usage: simpleblog_engineTag($simpleblog['root_php'] . '/articles', 'render', $_GET['tag'])
		// or
		// simpleblog_engineTag($simpleblog['root_php'] . '/articles', 'list', 'tag-not-defined-yet_not-needed')

		// import variables
		global $simpleblog;
		global $cms_root;
		global $cms_root_php;

		$returnArray=array(); // output

		// cache
		global $simpleblog;
		if(empty($simpleblog['cache']['core_files']))
			$simpleblog['cache']['core_files']=scandir($dir); // dump filelist
		$files=$simpleblog['cache']['core_files'];

		switch($action)
		{
			case 'list':
				$no_tags=true; // if there are no tags in database
				$tags=array(); $i=0; // bypass if tag is already listed

				echo '<div id="taglinks">';

				foreach(array_reverse($files) as $file)
					if(substr($file, 0, 6) === 'public')
					{
						include $dir . '/' . $file;
						foreach(explode('#', $art_tags) as $tag)
						{
							$tag=trim($tag); // remove space at the end
							if(($tag != '') && (!in_array($tag, $tags))) // omit empty value
							{
								echo '<a class="taglink" href="?tag=' . urlencode('#' . $tag) . '">#' . $tag . '</a><br>';
								$tags[$i]=$tag; $i++;
								$no_tags=false; // dont print 'Empty'
							}
						}
					}

				if($no_tags)
					echo '<h1 style="text-align: center;">Empty</h1>';

				echo '</div>';

				break;
			case 'render':
				$empty_tag=true;
				foreach(array_reverse($files) as $file)
					if(substr($file, 0, 6) === 'public') // include only items with public_ prefix
					{
						include $dir . '/' . $file;
						foreach(explode('#', $art_tags) as $tag)
						{
							$tag=trim($tag); // remove space at the end
							if('#' . $tag === $selected_tag)
							{
								array_push($returnArray, $dir . '/' . $file);
								$empty_tag=false;
							}
						}
					}

				if($empty_tag)
					echo '<h1 style="text-align: center;">Empty</h1>';

				break;
		}

		return $returnArray;
	}

	// render articles
	function simpleblog_engineCore($article, $taglinks)
	{
		// Usage: simpleblog_engineCore($article, $simpleblog['taglinks'])
		// where $simpleblog['taglinks'] is boolean

		// import variables
		global $simpleblog;
		global $cms_root;
		global $cms_root_php;

		// read article source
		include $article;

		// render tags if enabled
		if($taglinks)
		{
			$tags='';
			foreach(explode('#', $art_tags) as $tag)
				if($tag != '')
				{
					$tag=trim($tag);
					if(isset($art_style['taglink']))
						$tags=$tags . ' <a href="' . $simpleblog['root_html'] . '/tag?tag=' . urlencode('#' . $tag) . '" style="' . $art_style['taglink'] . '">#' . $tag . '</a>';
					else
						$tags=$tags . ' <a href="' . $simpleblog['root_html'] . '/tag?tag=' . urlencode('#' . $tag) . '">#' . $tag . '</a>';
				}
			$art_tags=$tags;
		}

		// render article
		if(isset($art_style['article'])) echo '<div class="article" style="' . $art_style['article'] . '">'."\n"; else echo '<div class="article">'."\n";
			if(isset($art_style['tags'])) echo '<div class="art-tags" style="' . $art_style['tags'] . '">'.$art_tags.'</div>'; else echo '<div class="art-tags">'.$art_tags.'</div>';
			if(isset($art_style['date'])) echo '<div class="art-date" style="' . $art_style['date'] . '">'.$art_date.'</div>'."\n"; else echo '<div class="art-date">'.$art_date.'</div>'."\n";
			if(isset($art_style['title'])) echo '<div class="art-title" style="' . $art_style['title'] . '">'; else echo '<div class="art-title">';
				if(isset($art_style['title-header'])) { if(($art_style['title-header'] === '') || ($art_style['title-header'])) echo '<h2>'.$art_title.'</h2>'; else echo $art_title; } else echo '<h2>'.$art_title.'</h2>';
			echo '</div>'."\n";
			echo $art_content;
		echo '</div>'."\n";
	}

	// count pages
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
		{
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