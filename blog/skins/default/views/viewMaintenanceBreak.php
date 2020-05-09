<?php
	// deny direct access
	if(php_sapi_name() === 'cli-server')
	{
		if(basename(strtok($_SERVER['REQUEST_URI'], '?')) === 'viewviewMaintenanceBreak.php')
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
<!DOCTYPE html>
<html lang="<?php echo $simpleblog['html_lang']; ?>">
	<head>
		<title><?php echo $simpleblog['title']; ?></title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="<?php echo $simpleblog['root_html']; ?>/skins/<?php echo $simpleblog['skin']; ?>?root=<?php echo $simpleblog['root_html']; ?>">
		<?php include $simpleblog['root_php'] . '/lib/htmlheaders.php'; ?>
		<?php simpleblog_viewMaintenanceCustomheaders(); ?>
	</head>
	<body>
		<div id="header">
			<?php include $simpleblog['root_php'] . '/lib/header.php'; ?>
		</div>
		<?php simpleblog_viewMaintenanceContent(); ?>
	</body>
</html>