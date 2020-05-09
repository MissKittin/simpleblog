<?php
	// Simpleblog post view library
	// 04.04.2020

	// deny direct access
	if(php_sapi_name() === 'cli-server')
	{
		if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/viewPost.php')
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

	// import corePost functions
	include $simpleblog['root_php'] . '/lib/corePost.php';

	// what do you want?
	if(isset($_GET['id']))
	{
		if(!$simpleblog['postlinks'])
		{
			include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
		}

		$file=simpleblog_postId2filename($simpleblog['root_php'] . '/articles', $_GET['id']);
		if($file === false)
		{
			include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
		}
	}
	else if(isset($_GET['date']))
	{
		if(!$simpleblog['datelinks'])
		{
			include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
		}

		if($_GET['date'] == '')
		{
			include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
		}

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
	}
	else
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
	}
?>
<?php
	// define functions for view
	function simpleblog_viewPostArticles()
	{
		global $simpleblog;
		global $file;

		$simpleblog['page']['emptyDatabase']=true; // for $_GET['date']

		if(isset($_GET['id'])) // select one post
			echo simpleblog_engineCore($file, $simpleblog['taglinks'], $simpleblog['postlinks'], $simpleblog['datelinks']);
		else if(isset($_GET['date'])) // select posts by date
		{
			foreach(simpleblog_enginePost($simpleblog['root_php'] . '/articles', $_GET['date'], $simpleblog['page']['current_page'], $simpleblog['entries_per_page']) as $article)
			{
				$simpleblog['page']['emptyDatabase']=false;
				echo simpleblog_engineCore($article, $simpleblog['taglinks'], $simpleblog['postlinks'], $simpleblog['datelinks']);
			}
			if($simpleblog['page']['emptyDatabase']) echo $simpleblog['emptyLabel'];
		}
	}
	function simpleblog_viewPostPages()
	{
		global $simpleblog;

		echo simpleblog_countPostPages($simpleblog['root_php'] . '/articles', $_GET['date'], $simpleblog['page']['current_page'], $simpleblog['entries_per_page']) . "\n";
	}
?>