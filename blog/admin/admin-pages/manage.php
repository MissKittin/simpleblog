<?php
	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/manage.php')
	{
		include '../lib/prevent-index.php'; exit();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Pages</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="<?php echo $adminpanel['root_html']; ?>/skins/<?php echo $adminpanel['skin']; ?>">
	</head>
	<body>
		<div id="header">
			<?php include $adminpanel['root_php'] . '/lib/header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $adminpanel['root_php'] . '/lib/menu/' . $adminpanel['menu_module'] . '/menu.php'; ?>
		</div>
		<div id="content_header">
			<h3><?php echo $_GET['manage']; ?></h3>
		</div>
		<div id="content">
			<table>
				<?php
					foreach(new DirectoryIterator($adminpanel['path']['pages'] . '/' . $_GET['manage']) as $file)
						if(($file != '.') && ($file != '..'))
							echo '<tr><td><a href="' . $adminpanel['path']['pages_html'] . '/' . $_GET['manage'] . '/' . $file . '" target="_blank">' . $file . '</a></td><td style="text-align: center;">' . $file->getSize() . 'B</td><td><a href="?manage=' . $_GET['manage'] . '&edit=' . $file . '">Edit</a></td><td><a href="?manage=' . $_GET['manage'] . '&delete=' . $file . '">Delete</a></td></tr>';
				?>
			</table>
			<div style="float: left;" class="button"><a href="?">Back</a></div> <?php if(ini_get('file_uploads') == 1) { ?><div style="float: left;" class="button"><a href="?manage=<?php echo $_GET['manage']; ?>&upload">Upload</a></div><?php } ?> <div style="float: left;" class="button"><a href="?manage=<?php echo $_GET['manage']; ?>&create">Create</a></div>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>