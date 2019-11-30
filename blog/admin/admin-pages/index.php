<?php
	// Admin panel for simpleblog - pages section
	// 20.11.2019
	$module['id']='admin-pages';

	// import settings
	include '../admin-settings.php';

	// login subsystem
	include $adminpanel['root_php'] . '/lib/login/login.php';
?>
<?php
	// manage link - section
	if(isset($_GET['manage']))
		if(file_exists($adminpanel['path']['pages'] . '/' . $_GET['manage']))
		{
			if(isset($_GET['delete']))
			{
				// delete file link
				if((file_exists($adminpanel['path']['pages'] . '/' . $_GET['manage'] . '/' . $_GET['delete']))  && (!preg_match('/\//i', $_GET['manage'])) && (!preg_match('/\//i', $_GET['delete'])))
				{
					if(isset($_GET['yes']))
					{
						unlink($adminpanel['path']['pages'] . '/' . $_GET['manage'] . '/' . $_GET['delete']);
						include('manage.php'); exit();
					}
					else
					{
						include('manage-delete.php'); exit();
					}
				}
				else
				{
					include('manage.php'); exit();
				}
			}
			else if(isset($_GET['edit']))
			{
				// file editor
				if((file_exists($adminpanel['path']['pages'] . '/' . $_GET['manage'] . '/' . $_GET['edit'])) && (!preg_match('/\//i', $_GET['manage'])) && (!preg_match('/\//i', $_GET['edit'])))
				{
					if(isset($_POST['file_content']))
					{
						if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();
						file_put_contents($adminpanel['path']['pages'] . '/' . $_GET['manage'] . '/' . $_GET['edit'], $_POST['file_content']);
						include('manage-edit.php'); exit();
					}
					else
					{
						include('manage-edit.php'); exit();
					}
				}
				else
				{
					include('manage.php'); exit();
				}
			}
			else if(isset($_GET['upload']))
			{
				// upload files button
				if(ini_get('file_uploads') == 1)
				{
					if(isset($_GET['yes']))
					{
						$countfiles=count($_FILES['file']['name']);
						for($i=0; $i<$countfiles; $i++)
							move_uploaded_file($_FILES['file']['tmp_name'][$i], $adminpanel['path']['pages'] . '/' . $_GET['manage'] . '/' . $_FILES['file']['name'][$i]);
						include('manage.php'); exit();
					}
					else
					{
						include('manage-upload.php'); exit();
					}
				}
				else
				{
					include('manage.php'); exit();
				}
				
			}
			else if(isset($_GET['create']))
			{
				// create file button
				if(isset($_POST['create']))
				{
					if((!file_exists($adminpanel['path']['pages'] . '/' . $_GET['manage'] . '/' . $_POST['create'])) && (!preg_match('/\//i', $_GET['manage'])) && (!preg_match('/\//i', $_GET['create'])))
						file_put_contents($adminpanel['path']['pages'] . '/' . $_GET['manage'] . '/' . $_POST['create'], '');
					include('manage.php'); exit();
				}
				else
				{
					include('manage-create.php'); exit();
				}
			}
			else
			{
				// manage page
				if((!preg_match('/\//i', $_GET['manage'])) && (!preg_match('/\//i', $_GET['edit'])))
				{
					include('manage.php'); exit();
				}
			}
		}

	// delete link
	if(isset($_GET['delete']))
		if((file_exists($adminpanel['path']['pages'] . '/' . $_GET['delete'])) && (!preg_match('/\//i', $_GET['delete'])))
		{
			if(isset($_GET['yes']))
			{
				foreach(scandir($adminpanel['path']['pages'] . '/' . $_GET['delete']) as $file)
					if(($file != '.') && ($file != '..'))
						unlink($adminpanel['path']['pages'] . '/' . $_GET['delete'] . '/' . $file);
				rmdir($adminpanel['path']['pages'] . '/' . $_GET['delete']);
			}
			else
			{
				include('delete.php');
				exit();
			}
		}

	// create button
	if(isset($_GET['create']))
	{
		if($_GET['create'] === '')
		{
			include('create.php');
			exit();
		}
		else
			if((!file_exists($adminpanel['path']['pages'] . '/' . $_GET['create'])) && (!preg_match('/\//i', $_GET['create'])))
			{
				mkdir($adminpanel['path']['pages'] . '/' . $_GET['create']);
				file_put_contents($adminpanel['path']['pages'] . '/' . $_GET['create'] . '/index.php', '<?php if(php_sapi_name() != \'cli-server\') include \'../../settings.php\'; ?>'."\n".'<!DOCTYPE html>'."\n".'<html>'."\n\t".'<head>'."\n\t\t".'<title><?php echo $simpleblog[\'title\']; ?></title>'."\n\t\t".'<meta charset="utf-8">'."\n\t\t".'<?php include $simpleblog[\'root_php\'] . \'/lib/htmlheaders.php\'; ?>'."\n\t".'</head>'."\n\t".'<body>'."\n\t\t".'<div id="header">'."\n\t\t\t".'<?php include $simpleblog[\'root_php\'] . \'/lib/header.php\'; ?>'."\n\t\t".'</div>'."\n\t\t".'<div id="headlinks">'."\n\t\t\t".'<?php include $simpleblog[\'root_php\'] . \'/lib/headlinks.php\'; ?>'."\n\t\t".'</div>'."\n\t\t".'<div id="articles">'."\n\t\t\t\n\t\t".'</div>'."\n\t\t".'<div id="footer">'."\n\t\t\t".'<?php include $simpleblog[\'root_php\'] . \'/lib/footer.php\'; ?>'."\n\t\t".'</div>'."\n\t".'</body>'."\n".'</html>'."\n".'<?php if(isset($simpleblog[\'execTime\'])) error_log(\'Simpleblog execution time in seconds: \' . (microtime(true) - $simpleblog[\'execTime\']), 0); ?>');
			}
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
			<h3>Pages</h3>
		</div>
		<div id="content">
			<table>
				<tr><th>URL</th><th>Files</th><th>Size</th></tr>
				<?php
					foreach(scandir($adminpanel['path']['pages']) as $file)
						if(($file != '.') && ($file != '..') && ($file != 'index.php'))
						{
							$countfiles=0;
							$size=0;
							foreach(new DirectoryIterator($adminpanel['path']['pages'] . '/' . $file) as $fil)
								if(($fil != '.') && ($fil != '..'))
								{
									$size+=$fil->getSize();
									$countfiles++;
								}
							echo '<tr><td>' . $file . '</td><td style="text-align: center">' . $countfiles . '</td><td style="text-align: center">' . round($size/1024) . 'KB</td><td><a href="?manage=' . $file . '">Manage</a></td><td><a href="?delete=' . $file . '">Delete</a></td><td><a href="?link=' . $file . '">Link</a></td></tr>';
						}
				?>
			</table>
			<div style="float: left;" class="button"><a href="?create">Create</a></div>
			<?php
				if(isset($_GET['link']))
				{
					echo '<br><br><h3>Generated link</h3>';
					echo '&lt;a class="headlink" href="&lt;?php echo $simpleblog[\'root_html\']; ?&gt;/pages/' . $_GET['link'] . '"&gt;Page title&lt;/a&gt;<br>';
				}
			?>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>