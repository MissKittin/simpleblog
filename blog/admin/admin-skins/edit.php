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
		<title>Skins</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="<?php echo $adminpanel['root_html']; ?>/skins/<?php echo $adminpanel['skin']; ?>">
		<script type="text/javascript" src="<?php echo $adminpanel['root_html']; ?>/lib/TabManager.js"></script>
	</head>
	<body>
		<div id="header">
			<?php include $adminpanel['root_php'] . '/lib/header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $adminpanel['root_php'] . '/lib/menu/menu.php'; ?>
		</div>
		<div id="content_header">
			<h3>Edit<br><?php echo $_GET['edit'] . '/' . $_GET['fileEdit']; ?></h3>
		</div>
		<div id="content">
			<form action="?edit=<?php echo $_GET['edit']; ?>&fileEdit=<?php echo $_GET['fileEdit']; ?>" method="post">
				<textarea name="file_content" style="height: 1024px; width: 99%;"><?php echo file_get_contents($simpleblog['root_php'] . '/skins/' . $_GET['edit'] . '/' . $_GET['fileEdit']); ?></textarea>
				<div style="float: left;" class="button"><a href="?edit=<?php echo $_GET['edit']; if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; ?>">Back</a></div> <input type="submit" class="button" value="Save">
			</form>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>
