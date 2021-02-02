<?php
	// deny direct access
	if(!isset($simpleblog))
	{
		include '../../../lib/prevent-index.php'; exit();
	}
?>
<!DOCTYPE html>
<html lang="<?php if(isset($simpleblog_viewPageLang)) echo $simpleblog_viewPageLang; else echo $simpleblog['html_lang']; ?>">
	<head>
		<title><?php if(isset($simpleblog_viewPageTitle)) echo $simpleblog_viewPageTitle; else echo $simpleblog['title']; ?></title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="<?php echo $simpleblog['root_html']; ?>/skins/<?php echo $simpleblog['skin']; ?>?root=<?php echo $simpleblog['root_html']; ?>">
		<?php include $simpleblog['root_php'] . '/lib/htmlheaders.php'; ?>
		<?php if(function_exists('simpleblog_viewPageCustomheaders')) simpleblog_viewPageCustomheaders(); ?>
	</head>
	<body>
		<div id="header">
			<?php include $simpleblog['root_php'] . '/lib/header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $simpleblog['root_php'] . '/lib/headlinks.php'; ?>
		</div>
		<div id="articles">
			<?php simpleblog_viewPageArticles(); ?>
		</div>
		<?php if(function_exists('simpleblog_viewPagePages')) { ?><div id="pages">
			<?php simpleblog_viewPagePages(); ?>
		</div><?php } ?>
		<div id="footer">
			<?php include $simpleblog['root_php'] . '/lib/footer.php'; ?>
		</div>
		<?php if(function_exists('simpleblog_viewPageBodyAppend')) simpleblog_viewPageBodyAppend(); ?>
	</body>
</html>