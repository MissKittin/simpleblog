<?php
	// deny direct access
	if(php_sapi_name() === 'cli-server')
	{
		if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/maintenance-break-pattern.php')
		{
			include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
		}
	}
	else
	{
		if(!isset($simpleblog))
		{
			include 'prevent-index.php'; exit();
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $simpleblog['title']; ?></title>
		<meta charset="utf-8">
		<?php include $simpleblog['root_php'] . '/lib/htmlheaders.php'; ?>
		<style>
			#maintenanceBreak {
				width: 300px;
				margin-left: auto; margin-right: auto;
				text-align: center;
			}
			#maintenanceBreakContent {
				position: absolute;
				top: 30%;
			}
		</style>
	</head>
	<body>
		<div id="header">
			<?php include $simpleblog['root_php'] . '/lib/header.php'; ?>
		</div>
		<div id="maintenanceBreak">
			<div id="maintenanceBreakContent">
				<h1>Maintenance Break</h1>
			</div>
		</div>
	</body>
</html>
