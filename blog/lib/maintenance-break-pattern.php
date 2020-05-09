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
<?php
	// losuj obrazek
	$images['sources']=['przerwa_techniczna.gif', 'wypierdalaj.gif'];
	$images['rand']=rand(0, 1);
?>
<?php function simpleblog_viewMaintenanceCustomheaders() { ?>
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
<?php } ?>
<?php function simpleblog_viewMaintenanceContent() { ?>
	<?php global $images; ?>
	<?php global $simpleblog; ?>
	<div id="maintenanceBreak">
		<div id="maintenanceBreakContent">
			<h1>Przerwa techniczna</h1>
			<img src="<?php echo $simpleblog['root_html']; ?>/media/<?php echo $images['sources'][$images['rand']]; ?>" alt="przerwa">
		</div>
	</div>
<?php } ?>
<?php include $simpleblog['root_php'] . '/skins/' . $simpleblog['skin'] . '/views/viewMaintenanceBreak.php'; ?>