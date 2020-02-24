<?php
	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/edit.php')
	{
		include '../lib/prevent-index.php'; exit();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Cron</title>
		<meta charset="utf-8">
		<?php include $adminpanel['root_php'] . '/lib/htmlheaders.php'; ?>
		<?php include $adminpanel['root_php'] . '/lib/opt_htmlheaders/TabManager.php'; ?>
	</head>
	<body>
		<div id="header">
			<?php include $adminpanel['root_php'] . '/lib/header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $adminpanel['root_php'] . '/lib/menu/' . $adminpanel['menu_module'] . '/menu.php'; ?>
		</div>
		<div id="content_header">
			<h3>Edit <?php echo $_GET['edit']; ?></h3>
		</div>
		<div id="content">
			<form action="?task=<?php echo $_GET['task']; ?>&edit" method="post">
				<textarea name="file_content" style="height: 1024px; width: 99%;"><?php echo file_get_contents($adminpanel['path']['cron'] . '/' . $_GET['task']); ?></textarea>
				<div class="button button_in_row"><a href="?">Back</a></div> <input type="submit" class="button" value="Save">
				<?php echo adminpanel_csrf_injectToken(); ?>
			</form>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>
