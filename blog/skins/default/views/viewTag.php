<?php
	// deny direct access
	if(!isset($simpleblog))
	{
		include '../../../lib/prevent-index.php'; exit();
	}
?>
<?php include $simpleblog['root_php'] . '/lib/viewTag.php'; ?>
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
			<?php simpleblog_viewTagArticles(); ?>
		</div>
		<?php if((isset($_GET['tag'])) && (!$simpleblog['page']['emptyDatabase'])) { ?><div id="pages">
			<?php simpleblog_viewTagPages(); ?>
		</div><?php } ?>
		<div id="footer">
			<?php include $simpleblog['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>