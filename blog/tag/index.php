<?php
	// Simpleblog v2.1 - tag subsystem
	// 03.12.2019
?>
<?php
	// import apache settings
	if(php_sapi_name() != 'cli-server') include '../settings.php';

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
				$emptyDatabase=true;
				if(isset($_GET['tag']))
				{
					foreach(simpleblog_engineTag($simpleblog['root_php'] . '/articles', 'render', $_GET['tag'], $simpleblog['page']['current_page'], $simpleblog['entries_per_page']) as $simpleblog['page']['current_article'])
					{
						simpleblog_engineCore($simpleblog['page']['current_article'], $simpleblog['taglinks'], $simpleblog['postlinks'], $simpleblog['datelinks']);
						$emptyDatabase=false;
					}
					if($emptyDatabase) echo $simpleblog['emptyLabel'];
				}
				else
				{
					echo '<div id="taglinks">';
					foreach(simpleblog_engineTag($simpleblog['root_php'] . '/articles', 'list') as $tag)
					{
						echo '<a class="taglink" href="?tag=' . urlencode('#' . $tag) . '">#' . $tag . '</a><br>';
						$emptyDatabase=false;
					}
					if($emptyDatabase) echo $simpleblog['emptyLabel'];
					echo '</div>';
				}
			?>
		</div>
		<?php if((isset($_GET['tag'])) && (!$emptyDatabase)) { ?><div id="pages">
			<?php echo simpleblog_countTagPages($simpleblog['root_php'] . '/articles', $_GET['tag'], $simpleblog['page']['current_page'], $simpleblog['entries_per_page']) . "\n"; ?>
		</div><?php } ?>
		<div id="footer">
			<?php include $simpleblog['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>