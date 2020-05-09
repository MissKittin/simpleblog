<?php
	// deny direct access
	if(php_sapi_name() === 'cli-server')
	{
		if(basename(strtok($_SERVER['REQUEST_URI'], '?')) === 'viewPost.php')
		{
			include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
		}
	}
	else
	{
		if(!isset($simpleblog))
		{
			include '../../../lib/prevent-index.php'; exit();
		}
	}
?>
<?php include $simpleblog['root_php'] . '/lib/viewPost.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $simpleblog['html_lang']; ?>">
	<head>
		<title><?php echo $simpleblog['title']; ?></title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="<?php echo $simpleblog['root_html']; ?>/skins/<?php echo $simpleblog['skin']; ?>?root=<?php echo $simpleblog['root_html']; ?>">
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
			<?php simpleblog_viewPostArticles(); ?>
		</div>
		<?php if((isset($_GET['date'])) && (!$simpleblog['page']['emptyDatabase'])) { ?><div id="pages">
			<?php simpleblog_viewPostPages(); ?>
		</div><?php } ?>
		<div id="footer">
			<?php include $simpleblog['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>