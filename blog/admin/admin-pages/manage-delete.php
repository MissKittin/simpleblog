<?php
	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/manage-delete.php')
	{
		include '../lib/prevent-index.php'; exit();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Pages</title>
		<meta charset="utf-8">
		<?php include $adminpanel['root_php'] . '/lib/htmlheaders.php'; ?>
	</head>
	<body>
		<div id="content" style="padding-bottom: 30px;">
			<h1><?php echo $_GET['delete']; ?> - Are you sure?</h1>
			<div class="button button_in_row"><a href="?manage=<?php echo $_GET['manage']; ?>">Back</a></div> <div class="button button_in_row"><a href="?manage=<?php echo $_GET['manage']; ?>&delete=<?php echo $_GET['delete']; ?>&yes&<?php echo adminpanel_csrf_printToken('parameter'); ?>=<?php echo adminpanel_csrf_printToken('value'); ?>">Delete</a></div>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>