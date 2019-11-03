<?php if(php_sapi_name() != 'cli-server') include '../../settings.php'; ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo "$page_title"; ?></title>
		<meta charset="utf-8">
		<?php include $_SERVER['DOCUMENT_ROOT'] . $cms_root . '/htmlheaders.php'; ?>
	</head>
	<body>
		<div id="header">
			<?php include $_SERVER['DOCUMENT_ROOT'] . $cms_root . '/header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $_SERVER['DOCUMENT_ROOT'] . $cms_root . '/headlinks.php'; ?>
		</div>
		<div id="articles">
			<h1 style="text-align: center;">Sample page</h1>
			<div style="text-align: center;">Sample text on this page</div>
		</div>
		<div id="footer">
			<?php include $_SERVER['DOCUMENT_ROOT'] . $cms_root . '/footer.php'; ?>
		</div>
	</body>
</html>