<?php
	// Admin panel for simpleblog - media section
	// 19.11.2019
	$module['id']='admin-media';

	// import settings
	include '../admin-settings.php';

	// login subsystem
	include $adminpanel['root_php'] . '/lib/login/login.php';
?>
<?php
	// upload button
	if(ini_get('file_uploads') == 1)
		if(isset($_GET['upload']))
		{
			if(isset($_GET['yes']))
			{
				$countfiles=count($_FILES['file']['name']);
				for($i=0; $i<$countfiles; $i++)
					move_uploaded_file($_FILES['file']['tmp_name'][$i], $adminpanel['path']['media'] . '/' . $_FILES['file']['name'][$i]);
			}
			else
			{ ?>
				<!DOCTYPE html>
				<html>
					<head>
						<title>Media</title>
						<meta charset="utf-8">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						<link rel="stylesheet" type="text/css" href="<?php echo $adminpanel['root_html']; ?>/skins/<?php echo $adminpanel['skin']; ?>">
					</head>
					<body>
						<div id="header">
							<?php include $adminpanel['root_php'] . '/lib/header.php'; ?>
						</div>
						<div id="headlinks">
							<?php include $adminpanel['root_php'] . '/lib/menu/menu.php'; ?>
						</div>
						<div id="content_header">
							<h3>Upload</h3>
						</div>
						<div id="content">
							<form action="?upload&yes" method="post" enctype="multipart/form-data">
								<input type="file" name="file[]" id="file" multiple>
								<input class="button" type="submit" value="Upload">
							</form>
						</div>
						<div id="footer">
							<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
						</div>
					</body>
				</html>
				<?php exit();
			}
		}

	// 'delete' link
	if(isset($_GET['delete']))
		if((file_exists($adminpanel['path']['media'] . '/' . $_GET['delete'])) && (!preg_match('/\//i', $_GET['delete'])))
		{
			if(isset($_GET['yes']))
				unlink($adminpanel['path']['media'] . '/' . $_GET['delete']);
			else
			{
				echo '<!DOCTYPE html><html><head>
						<title>Articles</title>
						<meta charset="utf-8">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						<link rel="stylesheet" type="text/css" href="' . $adminpanel['root_html'] . '/skins/' . $adminpanel['skin'] . '">
					</head><body>
						<div id="content" style="padding-bottom: 30px;">
							<h1>' . $_GET['delete'] . '<br>Are you sure?</h1>
							<div style="float: left; width: 50px;" class="button"><a href="admin-media">Back</a></div> <div style="float: left; width: 60px;" class="button"><a href="?delete=' . $_GET['delete'] . '&yes">Delete</a></div>
						</div>
					</body></html>';
				exit();
			}
		}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Media</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="<?php echo $adminpanel['root_html']; ?>/skins/<?php echo $adminpanel['skin']; ?>">
	</head>
	<body>
		<div id="header">
			<?php include $adminpanel['root_php'] . '/lib/header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $adminpanel['root_php'] . '/lib/menu/menu.php'; ?>
		</div>
		<div id="content_header">
			<h3>Media</h3>
		</div>
		<div id="content">
			<table>
				<?php
					foreach(new DirectoryIterator($adminpanel['path']['media']) as $file)
						if(($file != '.') && ($file != '..') && ($file != 'index.php'))
							echo '<tr><td>' . $file . '</td><td>' . round(($file->getSize())/1024) . 'KB</td><td><a style="color: #0000ff; text-decoration: none;" href="' . $simpleblog['root_html'] . '/media/' . $file . '" target="_blank">View</a></td><td><a style="color: #0000ff; text-decoration: none;" href="?delete=' . urlencode($file) . '">Delete</a></td></tr>';
				?>
			</table>
			<?php if(ini_get('file_uploads') == 1) { ?><div style="float: left; width: 58px;" class="button"><a href="?upload">Upload</a></div><?php } ?>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>