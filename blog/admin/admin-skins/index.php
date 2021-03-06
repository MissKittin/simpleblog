<?php header('X-Frame-Options: DENY'); ?>
<?php header('X-XSS-Protection:0'); ?>
<?php
	// Admin panel for simpleblog - skins section
	// 20.11.2019
	$module['id']='admin-skins';

	// import settings
	include '../admin-settings.php';

	// login subsystem
	include $adminpanel['root_php'] . '/lib/login/login.php';

	// functions
	function adminpanel_removeSkin($dir, $first)
	{
		foreach(scandir($dir) as $file)
			if(($file != '.') && ($file != '..'))
			{
				if(is_dir($dir . '/' . $file))
				{
					adminpanel_removeSkin($dir . '/' . $file, false);
					rmdir($dir . '/' . $file);
				}
				else
					unlink($dir . '/' . $file);
			}
		if($first)
			rmdir($dir);
	}
	function adminpanel_applySkin($oldskin, $newskin, $configfile)
	{
		global $adminpanel;
		if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();

		file_put_contents($configfile, str_replace('$simpleblog[\'skin\']=\'' . $oldskin . '\'', '$simpleblog[\'skin\']=\'' . $newskin . '\'', file_get_contents($configfile)));
		echo '<!DOCTYPE html><html><head><title>Skins</title><meta charset="utf-8">'; include $adminpanel['root_php'] . '/lib/htmlheaders.php'; echo '<meta http-equiv="refresh" content="0; url=admin-skins"></head></html>';
		exit();
	}
	function adminpanel_browseSkin($dir, $htmldir)
	{
		echo '<tr><th>Name</th><th>Type</th></tr>';
		foreach(scandir($dir) as $file)
			if(($file != '.') && ($file != '..'))
			{
				if(isset($_GET['dir']))
					$currentDir=$_GET['dir'] . '/';
				else
					$currentDir='';

				if(is_dir($dir . '/' . $file))
					$filelink='<a href="?edit=' . $_GET['edit'] . '&dir=' . $currentDir . $file . '">' . $file . '</a>';
				else
					$filelink='<a href="' . $htmldir . '/' . $file . '" target="_blank">' . $file . '</a>';
				echo '<tr><td>' . $filelink . '</td><td style="text-align: center;">'; if(is_dir($dir . '/' . $file)) echo '&lt;DIR&gt;'; else echo '&lt;FILE&gt;'; echo '</td><td>'; if(!is_dir($dir . '/' . $file)) { echo '<a href="?edit=' . $_GET['edit']; if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; echo '&fileEdit=' . $currentDir . $file . '">Edit</a>'; } echo '</td><td><a href="?edit=' . $_GET['edit']; if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; echo '&deleteFile=' . $file . '">Delete</a></td></tr>';
			}
	}
?>
<?php
	// edit file
	if(isset($_GET['fileEdit']))
		if((file_exists($adminpanel['path']['skins'] . '/' . $_GET['edit'] . '/' . $_GET['fileEdit'])) && (!in_array('..', explode('/', $_GET['edit'] . '/' . $_GET['fileEdit']))))
		{
			if((isset($_POST['file_content'])) && (adminpanel_csrf_checkToken('post')))
			{
				if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();
				file_put_contents($adminpanel['path']['skins'] . '/' . $_GET['edit'] . '/' . $_GET['fileEdit'], $_POST['file_content']);
				include './edit.php'; exit();
			}
			else
			{
				include './edit.php'; exit();
			}
		}

	// delete file
	if(isset($_GET['deleteFile']))
	{
		$currentDir='';
		if(isset($_GET['dir']))
			if(!preg_match('/\//i', $_GET['dir']))
				$currentDir=$_GET['dir'] . '/';

		if((file_exists($adminpanel['path']['skins'] . '/' . $_GET['edit'] . '/' . $currentDir . $_GET['deleteFile']))  && (!preg_match('/\//i', $_GET['edit'])) && (!preg_match('/\//i', $_GET['deleteFile'])))
		{
			if((isset($_GET['yes'])) && (adminpanel_csrf_checkToken('get')))
			{
				if(is_dir($adminpanel['path']['skins'] . '/' . $_GET['edit'] . '/' . $currentDir . $_GET['deleteFile']))
					adminpanel_removeSkin($adminpanel['path']['skins'] . '/' . $_GET['edit'] . '/' . $currentDir . $_GET['deleteFile'], true);
				else
					unlink($adminpanel['path']['skins'] . '/' . $_GET['edit'] . '/' . $currentDir . $_GET['deleteFile']);
			}
			else
			{
				// ask
				echo '<!DOCTYPE html><html><head>
						<title>Skins</title>
						<meta charset="utf-8">
						'; include $adminpanel['root_php'] . '/lib/htmlheaders.php'; echo '
					</head><body>
						<div id="content" style="padding-bottom: 30px;">
							<h1>' . $_GET['deleteFile'] . ' - Are you sure?</h1>
							<div class="button button_in_row"><a href="?edit=' . $_GET['edit']; if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; echo '">Back</a></div> <div class="button button_in_row"><a href="?edit=' . $_GET['edit']; if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; echo '&deleteFile=' . $_GET['deleteFile'] . '&yes&' . adminpanel_csrf_printToken('parameter') . '=' . adminpanel_csrf_printToken('value') . '">Delete</a></div>
						</div>
					</body></html>';
				exit();
			}
		}
	}

	// apply skin
	if(isset($_GET['apply']))
		if((file_exists($adminpanel['path']['skins'] . '/' . $_GET['apply'])) && (adminpanel_csrf_checkToken('get')))
			adminpanel_applySkin($simpleblog['skin'], $_GET['apply'], $simpleblog['root_php'] . '/settings.php');

	// delete skin
	if(isset($_GET['delete']))
		if((file_exists($adminpanel['path']['skins'] . '/' . $_GET['delete'])) && (!preg_match('/\//i', $_GET['delete'])))
		{
			if((isset($_GET['yes'])) && (adminpanel_csrf_checkToken('get')))
				adminpanel_removeSkin($adminpanel['path']['skins'] . '/' . $_GET['delete'], true);
			else
			{
				// ask
				echo '<!DOCTYPE html><html><head>
						<title>Skins</title>
						<meta charset="utf-8">
						'; include $adminpanel['root_php'] . '/lib/htmlheaders.php'; echo '
					</head><body>
						<div id="content" style="padding-bottom: 30px;">
							<h1>' . $_GET['delete'] . ' - Are you sure?</h1>
							<div class="button button_in_row"><a href="?">Back</a></div> <div class="button button_in_row"><a href="?delete=' . $_GET['delete'] . '&yes&' . adminpanel_csrf_printToken('parameter') . '=' . adminpanel_csrf_printToken('value') . '">Delete</a></div>
						</div>
					</body></html>';
				exit();
			}
		}

	// install skin
	if((isset($_GET['installSkin'])) && (isset($_GET['yes'])) && (ini_get('file_uploads') == 1) && (adminpanel_csrf_checkToken('get')))
	{
		$skinpack=new ZipArchive;
		$skinpack->open($_FILES['file']['tmp_name'][0]);
		$skinpack->extractTo($adminpanel['path']['skins']);
		$skinpack->close();
		unlink($_FILES['file']['tmp_name'][0]);
	}

	// upload file
	if((isset($_GET['upload'])) && (isset($_GET['yes'])) && (ini_get('file_uploads') == 1) && (adminpanel_csrf_checkToken('get')))
	{
		$countfiles=count($_FILES['file']['name']);
		if(isset($_GET['dir']))
			for($i=0; $i<$countfiles; ++$i)
				move_uploaded_file($_FILES['file']['tmp_name'][$i], $adminpanel['path']['skins'] . '/' . $_GET['edit'] . '/' . $_GET['dir'] . '/' . $_FILES['file']['name'][$i]);
		else
			for($i=0; $i<$countfiles; ++$i)
				move_uploaded_file($_FILES['file']['tmp_name'][$i], $adminpanel['path']['skins'] . '/' . $_GET['edit'] . '/' . $_FILES['file']['name'][$i]);
	}

	// dump skin
	if((isset($_GET['dumpSkin'])) && (file_exists($adminpanel['path']['skins'] . '/' . $_GET['dumpSkin'])) && (adminpanel_csrf_checkToken('get')))
		if(file_exists($adminpanel['root_php'] . '/lib/zip.lib.php'))
		{
			// zip library
			include $adminpanel['root_php'] . '/lib/zip.lib.php';

			// fileSearchRecursive library
			include $adminpanel['root_php'] . '/lib/fileSearchRecursive.php';

			$zip=new zipfile();
			foreach(adminpanel_fileSearchRecursive($adminpanel['path']['skins'] . '/' . $_GET['dumpSkin'], '') as $dumpSkinFile)
				$zip->addFile(file_get_contents($adminpanel['path']['skins'] . '/' . $_GET['dumpSkin'] . '/' . $dumpSkinFile), $_GET['dumpSkin'] . '/' . $dumpSkinFile);
			header('Content-type: application/octet-stream'); header('Content-Disposition: attachment; filename=simpleblog_' . $_GET['dumpSkin'] . '_skin_dump_' . date('d-m-Y') . '.zip'); header('Content-Description: Simpleblog skin dump');
			echo $zip->file();
		}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Skins</title>
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
			<?php
				if(isset($_GET['edit']))
					echo '<h3>Browse skin</h3>';
				else
					echo '<h3>Skins</h3>';
			?>
		</div>
		<div id="content">
			<table>
				<?php
					if(isset($_GET['edit']))
					{
						// browse skin
						if(isset($_GET['dir']))
							$edit_dothack=$_GET['dir'];
						else
							$edit_dothack='';

						if((file_exists($adminpanel['path']['skins'] . '/' . $_GET['edit'])) && (!in_array('..', explode('/', $edit_dothack))))
						{
							if(isset($_GET['dir']))
								adminpanel_browseSkin($adminpanel['path']['skins'] . '/' . $_GET['edit'] . '/' . $_GET['dir'], $adminpanel['path']['skins_html'] . '/' . $_GET['edit'] . '/' . $_GET['dir']);
							else
								adminpanel_browseSkin($adminpanel['path']['skins'] . '/' . $_GET['edit'], $adminpanel['path']['skins_html'] . '/' . $_GET['edit']);
						}
						else // '..' hack detected
							adminpanel_browseSkin($adminpanel['path']['skins'] . '/' . $_GET['edit'], $adminpanel['path']['skins_html'] . '/' . $_GET['edit']);
					}
					else
					{
						// list skins
						foreach(scandir($adminpanel['path']['skins']) as $file)
							if(($file != '.') && ($file != '..') && ($file != 'index.php'))
							{
								echo '<tr><td>' . $file . '</td><td>';
								if($file !== $simpleblog['skin']) echo '<a href="?apply=' . $file . '&' . adminpanel_csrf_printToken('parameter') . '=' . adminpanel_csrf_printToken('value') . '">Apply</a>';
								echo '</td><td><a href="?edit=' . $file . '">Browse</a></td><td>';
								if($file !== $simpleblog['skin']) echo '<a href="?delete=' . $file . '">Delete</a></td><td>';
								if($file === $simpleblog['skin']) echo '</td><td>'; echo '<a href="?dumpSkin=' . $file . '&' . adminpanel_csrf_printToken('parameter') . '=' . adminpanel_csrf_printToken('value') . '" target="_blank">Dump</a>';
								echo '</td></tr>';
							}
					}
				?>
			</table>
			<?php
				/* for browse skin  - back button */
				if(isset($_GET['edit']))
				{
					if(isset($_GET['dir']))
					{
						// back button with $_GET['dir']
						$editBackButton['display']=false;
						$editBackButton['explode']=explode('/', $_GET['dir']);
						$editBackButton['count']=count($editBackButton['explode'])-1;
						$editBackButton['link']='';

						for($i=0; $i<$editBackButton['count']; ++$i)
						{
							$editBackButton['display']=true;
							if($i == $editBackButton['count']-1)
								$editBackButton['link'].=$editBackButton['explode'][$i];
							else
								$editBackButton['link'].=$editBackButton['explode'][$i] . '/';
						}

						if($editBackButton['display'])
							echo '<div class="button button_in_row"><a href="?edit=' . $_GET['edit'] . '&dir=' . $editBackButton['link'] . '">Back</a></div>';
						else
							echo '<div class="button button_in_row"><a href="?edit=' . $_GET['edit'] . '">Back</a></div>';

						// upload button
						if(ini_get('file_uploads') == 1)
						{
							if(isset($_GET['upload']))
								echo '<br><br><br><br>
									<form action="?edit=' . $_GET['edit'] . '&dir=' . $_GET['dir'] . '&upload&yes&' . adminpanel_csrf_printToken('parameter') . '=' . adminpanel_csrf_printToken('value') . '" method="post" enctype="multipart/form-data">
										<input type="file" name="file[]" id="file" multiple>
										<input class="button" type="submit" value="Upload">
									</form>
								';
							else
								echo '<div class="button button_in_row"><a href="?edit=' . $_GET['edit'] . '&dir=' . $_GET['dir'] . '&upload">Upload</a></div>';
						}
					}
					else
						if(ini_get('file_uploads') == 1) // only upload button
						{
							if(isset($_GET['upload']))
								echo '
									<form action="?edit=' . $_GET['edit'] . '&upload&yes&' . adminpanel_csrf_printToken('parameter') . '=' . adminpanel_csrf_printToken('value') . '" method="post" enctype="multipart/form-data">
										<input type="file" name="file[]" id="file" multiple>
										<input class="button" type="submit" value="Upload">
									</form>
								';
							else
								echo '<div class="button button_in_row"><a href="?edit=' . $_GET['edit'] . '&upload">Upload</a></div>';
						}
				}
			?>
			<?php
				/* for list skins - current skin info and install button */
				if(!isset($_GET['edit']))
				{
					echo '<h3>Current skin: ' . $simpleblog['skin'] . '</h3>';
					if(ini_get('file_uploads') == 1)
					{
				 		if(isset($_GET['installSkin']))
							echo '
								<form action="?installSkin&yes&' . adminpanel_csrf_printToken('parameter') . '=' . adminpanel_csrf_printToken('value') . '" method="post" enctype="multipart/form-data">
									<input type="file" name="file[]" id="file" accept=".zip">
									<input class="button" type="submit" value="Install">
								</form>
							';
						else
							echo '<div class="button button_in_row"><a href="?installSkin">Install</a></div>';
					}
				}
			?>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>