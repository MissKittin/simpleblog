<?php
	// Maintenace break pattern
	// 09.11.2019

	// settings
	$maintenace_break['enabled']=false;
	$maintenace_break['allowed_ip']='127.0.0.1';

	// deny direct access
	if(strtok($_SERVER['REQUEST_URI'], '?') === $cms_root . '/maintenace-break.php')
	{
		include $cms_root_php . '/prevent-index.php'; exit();
	}

	// set client_ip variable
	if(!isset($maintenace_break['client_ip'])) $maintenace_break['client_ip']=$_SERVER['REMOTE_ADDR'];

	// check if maintenace pattern is enabled
	if(($maintenace_break['enabled']) && ($maintenace_break['client_ip'] != $maintenace_break['allowed_ip']))
	{
		if((explode('/', substr($simpleblog_router_cache['strtok'], strlen($cms_root)))[1] != 'favicon') && (explode('/', substr($simpleblog_router_cache['strtok'], strlen($cms_root)))[1] != 'media') && (explode('/', substr($simpleblog_router_cache['strtok'], strlen($cms_root)))[1] != 'skins'))
		{
/* pattern start */ ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo "$page_title"; ?></title>
		<meta charset="utf-8">
		<?php include $cms_root_php . '/htmlheaders.php'; ?>
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
			<?php include $cms_root_php . '/header.php'; ?>
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
?>