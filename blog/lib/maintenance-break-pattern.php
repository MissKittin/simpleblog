<?php
	// deny direct access
	if(!isset($simpleblog))
	{
		include './prevent-index.php'; exit();
	}
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
	<div id="maintenanceBreak">
		<div id="maintenanceBreakContent">
			<h1>Maintenance Break</h1>
		</div>
	</div>
<?php } ?>
<?php include $simpleblog['root_php'] . '/skins/' . $simpleblog['skin'] . '/views/viewMaintenanceBreak.php'; ?>