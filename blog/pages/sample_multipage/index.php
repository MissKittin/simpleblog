<?php
	// Pages - edit the variable value below
	$pages=3;

	// set page number
	$page=1;
	if(isset($_GET['page']))
	{
		if((is_numeric($_GET['page'])) && ($_GET['page'] <= $pages))
		{
			$page=$_GET['page'];
			settype($page, 'integer');
		}
	}
?>
<?php if(php_sapi_name() != 'cli-server') include '../../settings.php'; ?>
<?php if(file_exists('disabled.php')) { include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit(); } ?>
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
			<?php if($page === 1) { ?>
				<h1 style="text-align: center;">This is the first page</h1>
				<div style="text-align: center;">Sample text</div>
			<?php } ?>

			<?php if($page === 2) { ?>
				<h1 style="text-align: center;">This is the second page</h1>
				<div style="text-align: center;">Click page 3</div>
			<?php } ?>

			<?php if($page === 3) { ?>
				<h1 style="text-align: center;">This is the last page</h1>
				<h3 style="text-align: right;">The end</h3>
			<?php } ?>
		</div>
		<div id="pages">
			<?php
				for($i=1; $i<=$pages; $i++)
					if($i === $page)
						echo '<div class="page" id="current_page"><a href="?page='. $i .'">' . $i . '</a></div>';
					else
						echo '<div class="page"><a href="?page='. $i .'">' . $i . '</a></div>';
			?>
		</div>
		<div id="footer">
			<?php include $simpleblog['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>
<?php if(isset($simpleblog['execTime'])) error_log('Simpleblog execution time in seconds: ' . (microtime(true) - $simpleblog['execTime']), 0); ?>