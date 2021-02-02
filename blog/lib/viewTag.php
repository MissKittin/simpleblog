<?php
	// Simpleblog tag view library
	// 04.04.2020

	// deny direct access
	if(!isset($simpleblog))
	{
		include './prevent-index.php'; exit();
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
			foreach(simpleblog_engineTag($simpleblog['root_php'] . '/articles', 'list') as $tag)
			{
				$simpleblog['page']['emptyDatabase']=false;
				echo '<a class="taglink" href="?tag=' . urlencode('#' . $tag) . '">#' . $tag . '</a><br>';
			}
			if($simpleblog['page']['emptyDatabase']) echo $simpleblog['emptyLabel'];
			echo '</div>';
		}
	}
	function simpleblog_viewTagPages()
	{
		global $simpleblog;

		echo simpleblog_countTagPages($simpleblog['root_php'] . '/articles', $_GET['tag'], $simpleblog['page']['current_page'], $simpleblog['entries_per_page']) . "\n";
	}
?>