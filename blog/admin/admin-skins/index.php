<?php header('X-XSS-Protection:0'); ?>
<?php
	// Admin panel for simpleblog - pages section
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
		echo '<!DOCTYPE html><html><head><title>Skins</title><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" type="text/css" href="' . $adminpanel['root_html'] . '/skins/' . $adminpanel['skin'] . '"><meta http-equiv="refresh" content="0; url=admin-skins"></head></html>';
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
		if(file_exists($adminpanel['path']['skins'] . '/' . $_GET['edit'] . '/' . $_GET['fileEdit']))
		{
			if(isset($_POST['file_content']))
			{
				if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();
				file_put_contents($adminpanel['path']['skins'] . '/' . $_GET['edit'] . '/' . $_GET['fileEdit'], $_POST['file_content']);
				include('edit.php'); exit();
			}
			else
			{
				include('edit.php'); exit();
			}
		}

	// delete file
	if(isset($_GET['deleteFile']))
	{
		if(isset($_GET['dir']))
		   $currentDir=$_GET['dir'] . '/';
		else
			$currentDir='';

		if(file_exists($adminpanel['path']['skins'] . '/' . $_GET['edit'] . '/' . $currentDir . $_GET['deleteFile']))
		{
			if(isset($_GET['yes']))
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
						<meta name="viewport" content="width=device-width, initial-scale=1">
						<link rel="stylesheet" type="text/css" href="' . $adminpanel['root_html'] . '/skins/' . $adminpanel['skin'] . '">
					</head><body>
						<div id="content" style="padding-bottom: 30px;">
							<h1>' . $_GET['deleteFile'] . ' - Are you sure?</h1>
							<div style="float: left; width: 50px;" class="button"><a href="?edit=' . $_GET['edit']; if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; echo '">Back</a></div> <div style="float: left; width: 60px;" class="button"><a href="?edit=' . $_GET['edit']; if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; echo '&deleteFile=' . $_GET['deleteFile'] . '&yes">Delete</a></div>
						</div>
					</body></html>';
				exit();
			}
		}
	}

	// apply skin
	if(isset($_GET['apply']))
		if(file_exists($adminpanel['path']['skins'] . '/' . $_GET['apply']))
		{
			if(php_sapi_name() === 'cli-server')
				adminpanel_applySkin($simpleblog['skin'], $_GET['apply'], $simpleblog['root_php'] . '/router.php');
			else
				adminpanel_applySkin($simpleblog['skin'], $_GET['apply'], $simpleblog['root_php'] . '/settings.php');
		}

	// delete skin
	if(isset($_GET['delete']))
		if(file_exists($adminpanel['path']['skins'] . '/' . $_GET['delete']))
		{
			if(isset($_GET['yes']))
				adminpanel_removeSkin($adminpanel['path']['skins'] . '/' . $_GET['delete'], true);
			else
			{
				// ask
				echo '<!DOCTYPE html><html><head>
						<title>Skins</title>
						<meta charset="utf-8">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						<link rel="stylesheet" type="text/css" href="' . $adminpanel['root_html'] . '/skins/' . $adminpanel['skin'] . '">
					</head><body>
						<div id="content" style="padding-bottom: 30px;">
							<h1>' . $_GET['delete'] . ' - Are you sure?</h1>
							<div style="float: left; width: 50px;" class="button"><a href="admin-skins">Back</a></div> <div style="float: left; width: 60px;" class="button"><a href="?delete=' . $_GET['delete'] . '&yes">Delete</a></div>
						</div>
					</body></html>';
				exit();
			}
		}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Skins</title>
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
			<h3>Skins</h3>
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
								echo '<tr><td>' . $file . '</td><td>'; if($file != $simpleblog['skin']) echo '<a href="?apply=' . $file . '">Apply</a>'; echo '</td><td><a href="?edit=' . $file . '">Browse</a></td><td>'; if($file != $simpleblog['skin']) echo '<a href="?delete=' . $file . '">Delete</a>'; echo '</td></tr>';
							}
					}
				?>
			</table>
			<?php
				/* for browse skin  - back button */
				if(isset($_GET['edit']))
					if(isset($_GET['dir']))
					{
						$editBackButton['display']=false;
						$editBackButton['explode']=explode('/', $_GET['dir']);
						$editBackButton['count']=count($editBackButton['explode'])-1;
						$editBackButton['link']='';

						for($i=0; $i<$editBackButton['count']; $i++)
						{
							$editBackButton['display']=true;
							if($i == $editBackButton['count']-1)
								$editBackButton['link'].=$editBackButton['explode'][$i];
							else
								$editBackButton['link'].=$editBackButton['explode'][$i] . '/';
						}

						if($editBackButton['display'])
							echo '<div style="float: left;" class="button"><a href="?edit=' . $_GET['edit'] . '&dir=' . $editBackButton['link'] . '">Back</a></div>';
						else
							echo '<div style="float: left;" class="button"><a href="?edit=' . $_GET['edit'] . '">Back</a></div>';
					}
			?>
			<?php /* for list skins - current skin info */ if(!isset($_GET['edit'])) { ?><h3>Current skin: <?php echo $simpleblog['skin']; ?></h3><?php } ?>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>