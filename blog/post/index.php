<?php
	// Simpleblog v2.1 - post page
	// 03.12.2019
?>
<?php
	// import apache settings
	if(php_sapi_name() != 'cli-server') include '../settings.php';

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
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $simpleblog['title']; ?></title>
		<meta charset="utf-8">
		<?php include $simpleblog['root_php'] . '/lib/htmlheaders.php'; ?>
	</head>
	<body>
		<div id="header">
			<?php include $simpleblog['root_php'] . '/lib/header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $simpleblog['root_php'] . '/lib/headlinks.php'; ?>
		</div>
		<div id="articles">
			<?php
				$emptyDatabase=true; // for $_GET['date']

				if(isset($_GET['id'])) // select one post
					simpleblog_engineCore($file, $simpleblog['taglinks'], $simpleblog['postlinks'], $simpleblog['datelinks']);
				else if(isset($_GET['date'])) // select posts by date
				{
					foreach(simpleblog_enginePost($simpleblog['root_php'] . '/articles', $_GET['date'], $simpleblog['page']['current_page'], $simpleblog['entries_per_page']) as $article)
					{
						simpleblog_engineCore($article, $simpleblog['taglinks'], $simpleblog['postlinks'], $simpleblog['datelinks']);
						$emptyDatabase=false;
					}
					if($emptyDatabase) echo $simpleblog['emptyLabel'];
				}
			?>
		</div>
		<?php if((isset($_GET['date'])) && (!$emptyDatabase)) { ?><div id="pages">
			<?php echo simpleblog_countPostPages($simpleblog['root_php'] . '/articles', $_GET['date'], $simpleblog['page']['current_page'], $simpleblog['entries_per_page']) . "\n"; ?>
		</div><?php } ?>
		<div id="footer">
			<?php include $simpleblog['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>
<?php if(isset($simpleblog['execTime'])) error_log('Simpleblog execution time in seconds: ' . (microtime(true) - $simpleblog['execTime']), 0); ?>