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
		<title>Files</title>
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
			<form action="?edit=<?php echo $_GET['edit']; if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; ?>" method="post">
				<textarea name="file_content" style="height: 1024px; width: 99%;"><?php if(isset($_GET['dir'])) echo file_get_contents($simpleblog['root_php'] . '/' . $_GET['dir'] . '/' . $_GET['edit']); else echo file_get_contents($simpleblog['root_php'] . '/' . $_GET['edit']); ?></textarea>
				<div style="float: left;" class="button"><a href="?<?php if(isset($_GET['dir'])) echo 'dir=' . $_GET['dir']; ?>">Back</a></div> <input type="submit" class="button" value="Save">
			</form>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>