<?php
	// Simpleblog v2 - tag subsystem
	// 11.11.2019
?>
<?php
	// import apache settings
	if(php_sapi_name() != 'cli-server') include '../settings.php';

	// import core functions
	include $simpleblog['root_php'] . '/lib/core.php';
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
				if(isset($_GET['tag']))
					foreach(simpleblog_engineTag($simpleblog['root_php'] . '/articles', 'render', $_GET['tag']) as $simpleblog['page']['current_article'])
						simpleblog_engineCore($simpleblog['page']['current_article'], $simpleblog['taglinks']);
				else
					simpleblog_engineTag($simpleblog['root_php'] . '/articles', 'list', 'tag-not-defined-yet_not-needed');
			?>
		</div>
		<div id="footer">
			<?php include $simpleblog['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>