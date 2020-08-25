<?php
	// Simpleblog index view library
	// 04.04.2020
	// $simpleblog['coreIndex_forceEcho'] variable 19.08.2020

	// deny direct access
	if(php_sapi_name() === 'cli-server')
	{
		if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/viewIndex.php')
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
?>
<?php
	// define functions for view
	function simpleblog_viewIndexArticles()
	{
		global $simpleblog;

		$simpleblog['page']['emptyDatabase']=true;
		foreach(simpleblog_engineIndex($simpleblog['root_php'] . '/articles', $simpleblog['page']['current_page'], $simpleblog['entries_per_page']) as $simpleblog['page']['current_article'])
		{
			$simpleblog['page']['emptyDatabase']=false;
			echo simpleblog_engineCore($simpleblog['page']['current_article'], $simpleblog['taglinks'], $simpleblog['postlinks'], $simpleblog['datelinks']);
		}
		if($simpleblog['page']['emptyDatabase']) echo $simpleblog['emptyLabel'];
	}
	function simpleblog_viewIndexPages()
	{
		global $simpleblog;

		if(!isset($simpleblog['coreIndex_forceEcho'])) $simpleblog['coreIndex_forceEcho']=false; // if not defined in settings
		if(!$simpleblog['page']['emptyDatabase']) echo simpleblog_countIndexPages($simpleblog['root_php'] . '/articles', $simpleblog['page']['current_page'], $simpleblog['entries_per_page'], $simpleblog['coreIndex_forceEcho']) . "\n";
	}
?>