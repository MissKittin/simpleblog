<?php
	// Maintenace break pattern
	// 09.11.2019
	// admin update 23.11.2019

	// deny direct access - for apache
	if(php_sapi_name() != 'cli-server')
		if(!isset($simpleblog))
		{
			include 'prevent-index.php'; exit();
		}

	// deny direct access - for php-cli server
	if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/core.php')
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
	}

	// settings (now defined in settings)
	//$maintenace_break['enabled']=false;
	//$maintenace_break['allowed_ip']='127.0.0.1';
	$maintenace_break['bin']=$simpleblog['root_html'] . '/lib/maintenace-break.php';

	// set client_ip variable
	if(!isset($maintenace_break['client_ip'])) $maintenace_break['client_ip']=$_SERVER['REMOTE_ADDR'];

	// check if maintenace pattern is enabled
	if(($maintenace_break['enabled']) && ($maintenace_break['client_ip'] != $maintenace_break['allowed_ip']))
	{
		if(
			(explode('/', substr($simpleblog_router_cache['strtok'], strlen($simpleblog['root_html'])))[2] != 'favicon') &&
			(explode('/', substr($simpleblog_router_cache['strtok'], strlen($simpleblog['root_html'])))[1] != 'media') &&
			(explode('/', substr($simpleblog_router_cache['strtok'], strlen($simpleblog['root_html'])))[1] != 'skins') &&
			(explode('/', substr($simpleblog_router_cache['strtok'], strlen($simpleblog['root_html'])))[1] != 'admin')
		)
		{
/* pattern start */ ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo "$page_title"; ?></title>
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
<?php /* pattern end */
			exit();
		}
	}
	unset($maintenace_break); // clean
?>