<?php
	// deny access if settings not imported
	if(php_sapi_name() != 'cli-server')
		if(!isset($simpleblog))
			include 'prevent-index.php';

	// deny direct access
	if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/maintenace-break-pattern.php')
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $simpleblog['title']; ?></title>
		<meta charset="utf-8">
		<?php include $simpleblog['root_php'] . '/lib/htmlheaders.php'; ?>
		<style>
			#maintenaceBreak {
				width: 300px;
				margin-left: auto; margin-right: auto;
				text-align: center;
			}
			#maintenaceBreakContent {
				position: absolute;
				top: 40%;
			}
		</style>
	</head>
	<body>
		<div id="header">
			<?php include $simpleblog['root_php'] . '/lib/header.php'; ?>
		</div>
		<div id="maintenaceBreak">
			<div id="maintenaceBreakContent">
				<h1>Maintenace Break</h1>
			</div>
		</div>
	</body>
</html>
