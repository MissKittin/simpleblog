<?php
	// Simpleblog tag view library
	// with cache patch
	// 04.04.2020

	// deny direct access
	if(php_sapi_name() === 'cli-server')
	{
		if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/viewTag.php')
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
?>
<?php
	// import core functions
	include $simpleblog['root_php'] . '/lib/core.php';

	// import coreTag functions
	include $simpleblog['root_php'] . '/lib/coreTag.php';

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

	// import cacheTag library
	include $simpleblog['root_php'] . '/lib/cacheTag.php';
?>
<?php
	// define functions for view
	function simpleblog_viewTagArticles()
	{
		global $simpleblog;

		$simpleblog['page']['emptyDatabase']=true;
		if(isset($_GET['tag']))
		{
			foreach(simpleblog_engineTag($simpleblog['root_php'] . '/articles', 'render', $_GET['tag'], $simpleblog['page']['current_page'], $simpleblog['entries_per_page']) as $simpleblog['page']['current_article'])
			{
				$simpleblog['page']['emptyDatabase']=false;
				echo simpleblog_engineCore($simpleblog['page']['current_article'], $simpleblog['taglinks'], $simpleblog['postlinks'], $simpleblog['datelinks']);
			}
			if($simpleblog['page']['emptyDatabase']) echo $simpleblog['emptyLabel'];
		}
		else
		{
			echo '<div id="taglinks">';
			if($simpleblog['cache']['use_cache']) // cache patch
				include $simpleblog['cache']['cacheTag']['cache_dir'] . '/tags.php';
			else
			{
				foreach(simpleblog_engineTag($simpleblog['root_php'] . '/articles', 'list') as $tag)
				{
					$simpleblog['page']['emptyDatabase']=false;
					echo '<a class="taglink" href="?tag=' . urlencode('#' . $tag) . '">#' . $tag . '</a><br>';
				}
				if($simpleblog['page']['emptyDatabase']) echo $simpleblog['emptyLabel'];
			}
			echo '</div>';
		}
	}
	function simpleblog_viewTagPages()
	{
		global $simpleblog;

		echo simpleblog_countTagPages($simpleblog['root_php'] . '/articles', $_GET['tag'], $simpleblog['page']['current_page'], $simpleblog['entries_per_page']) . "\n";
	}
?>