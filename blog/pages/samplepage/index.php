<?php
	include '../../settings.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo "$page_title"; ?></title>
		<meta charset="utf-8">
		<link rel="shortcut icon" type="image/icon" href="<?php echo "$cms_root"; ?>favicon.ico">
		<link rel="stylesheet" type="text/css" href="<?php echo "$cms_root"; ?>style?root=<?php echo $cms_root; ?>">
	</head>
	<body>
		<div id="header">
			<?php include $_SERVER['DOCUMENT_ROOT'] . $cms_root . 'header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $_SERVER['DOCUMENT_ROOT'] . $cms_root . 'headlinks.php'; ?>
		</div>
		<div id="articles">
			<h1 style="text-align: center;">Sample page</h1>
			<div style="text-align: center;">Sample text on this page</div>
		</div>
		<div id="footer">
			<?php include $_SERVER['DOCUMENT_ROOT'] . $cms_root . 'footer.php'; ?>
		</div>
	</body>
</html>
