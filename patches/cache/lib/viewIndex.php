<?php
	// Simpleblog index view library
	// with cache patch
	// 04.04.2020
	// $simpleblog['coreIndex_forceEcho'] variable 19.08.2020

	// deny direct access
	if(!isset($simpleblog))
	{
		include './prevent-index.php'; exit();
	}
?>
<?php
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
<?php
	// define functions for view
	function simpleblog_viewIndexArticles()
	{
		global $simpleblog;
		global $cms_root; // engineCore() has this, when cache file is used, engineCore() is not called - without this global, $cms_root is not available for cache

		if($simpleblog['cache']['use_cache']) // cache patch
			include $simpleblog['cache']['cacheIndex']['cache_dir'] . '/' . $simpleblog['page']['current_page'] . '.php';
		else
		{
			$simpleblog['page']['emptyDatabase']=true;
			foreach(simpleblog_engineIndex($simpleblog['root_php'] . '/articles', $simpleblog['page']['current_page'], $simpleblog['entries_per_page']) as $simpleblog['page']['current_article'])
			{
				$simpleblog['page']['emptyDatabase']=false;
				echo simpleblog_engineCore($simpleblog['page']['current_article'], $simpleblog['taglinks'], $simpleblog['postlinks'], $simpleblog['datelinks']);
			}
			if($simpleblog['page']['emptyDatabase']) echo $simpleblog['emptyLabel'];
		}
	}
	function simpleblog_viewIndexPages()
	{
		global $simpleblog;

		if($simpleblog['cache']['use_cache']) // cache patch
			echo simpleblog_cacheIndex_countPages($simpleblog['page']['current_page']) . "\n";
		else
		{
			if(!isset($simpleblog['coreIndex_forceEcho'])) $simpleblog['coreIndex_forceEcho']=false; // if not defined in settings
			if(!$simpleblog['page']['emptyDatabase']) echo simpleblog_countIndexPages($simpleblog['root_php'] . '/articles', $simpleblog['page']['current_page'], $simpleblog['entries_per_page'], $simpleblog['coreIndex_forceEcho']) . "\n";
		}
	}
?>