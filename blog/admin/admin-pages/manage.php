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
		<?php include $adminpanel['root_php'] . '/lib/htmlheaders.php'; ?>
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
						if(($file != '.') && ($file != '..') && ($file != 'disabled.php'))
							echo '<tr>
								<td><a href="' . $adminpanel['path']['pages_html'] . '/' . $_GET['manage'] . '/' . $file . '" target="_blank">' . $file . '</a></td>
								<td style="text-align: center;">' . adminpanel_convertBytes($file->getSize()) . '</td>
								<td><a href="?manage=' . $_GET['manage'] . '&edit=' . $file . '">Edit</a></td>
								<td><a href="?manage=' . $_GET['manage'] . '&delete=' . $file . '">Delete</a></td>
								<td><a href="?manage=' . $_GET['manage'] . '&rename=' . $file . '">Rename</a></td>
							</tr>';
				?>
			</table>
			<div class="button button_in_row"><a href="?">Back</a></div> <?php if(ini_get('file_uploads') == 1) { ?><div class="button button_in_row"><a href="?manage=<?php echo $_GET['manage']; ?>&upload">Upload</a></div><?php } ?> <div class="button button_in_row"><a href="?manage=<?php echo $_GET['manage']; ?>&create">Create</a></div>
			<?php
				if(isset($_GET['rename']))
				{
					echo '<br><br><br>
						<form action="?manage=' . $_GET['manage'] . '&oldname=' . $_GET['rename'] . '" method="post">
							<label for="rename">Rename ' . $_GET['rename'] . ' to</label>
							<input type="text" name="rename" value="' . $_GET['rename'] . '" required>
							<input type="submit" class="button" value="Rename">
							' . adminpanel_csrf_injectToken() . '
						</form>
					';
				}
			?>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>