<?php header('X-XSS-Protection:0'); ?>
<?php
	// Admin panel for simpleblog - elements section
	// 22.11.2019
	$module['id']='admin-elements';

	// import settings
	include '../admin-settings.php';

	// login subsystem
	include $adminpanel['root_php'] . '/lib/login/login.php';

	// functions
	function adminpanel_editFile($file)
	{ ?>
		<div>
			<form action="?edit=<?php echo $_GET['edit']; ?>" method="post">
				<textarea name="file_content" style="height: 1024px; width: 99%;"><?php echo file_get_contents($file); ?></textarea>
				<input type="submit" class="button" value="Save">
			</form>
		</div>
	<?php }
	function adminpanel_editFaviconFile($file)
	{ ?>
		<div>
			<form action="?edit=favicon&file=<?php echo $_GET['file']; ?>" method="post">
				<textarea name="file_content" style="height: 1024px; width: 99%;"><?php echo file_get_contents($file); ?></textarea>
				<div style="float: left;" class="button"><a href="?edit=favicon">Back</a></div> <input type="submit" class="button" value="Save">
			</form>
		</div>
	<?php }
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Elements</title>
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
			<h3>Page elemens</h3>
		</div>
		<div id="content">
			<div style="overflow: auto;">
				<div style="float: left;" class="button"><a href="?edit=header">Header</a></div>
				<div style="float: left;" class="button"><a href="?edit=headlinks">Headlinks</a></div>
				<div style="float: left;" class="button"><a href="?edit=footer">Footer</a></div>
				<div style="float: left;" class="button"><a href="?edit=htmlheaders">HTML headers</a></div>
				<div style="float: left;" class="button"><a href="?edit=favicon">Favicon</a></div>
			</div>
			<?php
				if(isset($_POST['file_content']))
				{
					if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();
					switch($_GET['edit'])
					{
						case 'header':
							file_put_contents($adminpanel['path']['header'], $_POST['file_content']);
							break;
						case 'headlinks':
							file_put_contents($adminpanel['path']['headlinks'], $_POST['file_content']);
							break;
						case 'footer':
							file_put_contents($adminpanel['path']['footer'], $_POST['file_content']);
							break;
						case 'htmlheaders':
							file_put_contents($adminpanel['path']['htmlheaders'], $_POST['file_content']);
							break;
					}
				}

				if(isset($_GET['edit']))
				{
					switch($_GET['edit'])
					{
						case 'header':
							adminpanel_editFile($adminpanel['path']['header']);
							break;
						case 'headlinks':
							adminpanel_editFile($adminpanel['path']['headlinks']);
							break;
						case 'footer':
							adminpanel_editFile($adminpanel['path']['footer']);
							break;
						case 'htmlheaders':
							adminpanel_editFile($adminpanel['path']['htmlheaders']);
							break;
						case 'favicon':
							// delete file
							if(isset($_GET['delete']))
								if(file_exists($adminpanel['path']['favicon'] . '/' . $_GET['delete']))
								{
									if(isset($_GET['yes']))
										unlink($adminpanel['path']['favicon'] . '/' . $_GET['delete']);
									else
									{
										echo '<div style="overflow: auto;"><h1>' . $_GET['delete'] . ' - Are you sure?</h1><div style="float: left;" class="button"><a href="?edit=favicon">Back</a></div> <div style="float: left;" class="button"><a href="?edit=' . $_GET['edit'] . '&delete=' . $_GET['delete'] . '&yes">Delete</a></div></div>';
										break;
									}
								}

							// upload files
							$adminpanel_uploadButton=true;
							if((ini_get('file_uploads') == 1) && (isset($_GET['upload'])))
							{
								if($_GET['upload'] === 'yes')
								{
									$countfiles=count($_FILES['file']['name']);
									for($i=0; $i<$countfiles; $i++)
										move_uploaded_file($_FILES['file']['tmp_name'][$i], $adminpanel['path']['favicon'] . '/' . $_FILES['file']['name'][$i]);
								}
								else
									$adminpanel_uploadButton=false;
							}

							// save file
							if(isset($_POST['file_content']))
							{
								if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();
								file_put_contents($adminpanel['path']['favicon'] . '/' . $_GET['file'], $_POST['file_content']);
							}

							// edit file
							if(isset($_GET['file']))
							{
								if(file_exists($adminpanel['path']['favicon'] . '/' . $_GET['file']))
									adminpanel_editFaviconFile($adminpanel['path']['favicon'] . '/' . $_GET['file']);
								break;
							}

							// list files
							echo '<div><table>';
							foreach(new DirectoryIterator($adminpanel['path']['favicon']) as $file)
								if(($file != '.') && ($file != '..') && ($file != 'index.php'))
									echo '<tr><td>' . $file . '</td><td>' . round(($file->getSize())/1024) . 'KB</td><td><a href="' . $adminpanel['path']['favicon_html'] . '/' . $file . '" target="_blank">View</a></td><td><a href="?edit=favicon&file=' . $file . '">Edit</a></td><td><a href="?edit=favicon&delete=' . $file . '">Delete</a></td></tr>';
							echo '</table></div>';
							if(ini_get('file_uploads') == 1)
							{
								if($adminpanel_uploadButton)
									echo '<div style="float: left;" class="button"><a href="?edit=favicon&upload">Upload</a></div>'; // upload button
								else
									echo '<form action="?edit=favicon&upload=yes" method="post" enctype="multipart/form-data"><input type="file" name="file[]" id="file" multiple><input class="button" type="submit" value="Upload"></form>'; // upload form
							}

							break;
					}
				}
			?>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>