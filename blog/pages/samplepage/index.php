<?php if(php_sapi_name() != 'cli-server') include '../../settings.php'; ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo "$page_title"; ?></title>
		<meta charset="utf-8">
		<?php include $cms_root_php . '/htmlheaders.php'; ?>
	</head>
	<body>
		<div id="header">
			<?php include $cms_root_php . '/header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $cms_root_php . '/headlinks.php'; ?>
		</div>
		<div id="articles">
			<h1 style="text-align: center;">Sample page</h1>
			<div style="text-align: center;">Sample text on this page</div>
		</div>
		<div id="footer">
			<?php include $cms_root_php . '/footer.php'; ?>
		</div>
	</body>
</html>