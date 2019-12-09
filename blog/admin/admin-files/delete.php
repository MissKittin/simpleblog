<?php
	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/delete.php')
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
	</head>
	<body>
		<div id="content" style="padding-bottom: 30px;">
			<h1><?php echo $_GET['delete']; ?> - Are you sure?</h1>
			<div style="float: left;" class="button"><a href="?<?php if(isset($_GET['dir'])) echo 'dir=' . $_GET['dir']; ?>">Back</a></div>
			<div style="float: left;" class="button"><a href="?delete=<?php echo $_GET['delete']; ?>&yes<?php if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; ?>">Delete</a></div>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>