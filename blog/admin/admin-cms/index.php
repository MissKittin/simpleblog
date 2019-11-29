<?php
	// Admin panel for simpleblog - cms section
	// 25.11.2019
	// uses $simpleblog array
	$module['id']='admin-cms';

	// import settings
	include '../admin-settings.php';

	// login subsystem
	include $adminpanel['root_php'] . '/lib/login/login.php';

	// select settings file
	if(php_sapi_name() === 'cli-server')
		$settings_file=$simpleblog['root_php'] . '/.router.php';
	else
		$settings_file=$simpleblog['root_php'] . '/settings.php';

	// restore $maintenace_break array
	foreach(explode(PHP_EOL, file_get_contents($settings_file)) as $mbLine)
	{
		$mbLine=trim($mbLine);
		$mbLineVarname=strstr($mbLine, '=', true);
		if(($mbLineVarname == '$maintenace_break[\'enabled\']') || ($mbLineVarname == '$maintenace_break[\'allowed_ip\']'))
			eval($mbLine);
	}

	// functions
	function reload()
	{
		global $adminpanel;
		echo '<!DOCTYPE html><html><head><title>CMS</title><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" type="text/css" href="' . $adminpanel['root_html'] . '/skins/' . $adminpanel['skin'] . '"><meta http-equiv="refresh" content="0; url=?"></head></html>';
		exit();
	}
	function adminpanel_backupSearchRecursive($dir, $prefix)
	{
		$returnArray=array();
		foreach(scandir($dir) as $file)
			if(($file != '.') && ($file != '..'))
			{
				if(is_dir($dir . '/' . $file))
					foreach(adminpanel_backupSearchRecursive($dir . '/' . $file, $file . '/') as $addToArray)
						array_push($returnArray, $addToArray);
				else
					array_push($returnArray, $prefix . $file);
			}
		return $returnArray;
	}
?>
<?php
	// apply settings
	if(isset($_GET['apply']))
	{
		if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();

		// cms
		if(isset($_POST['title']))
			if($_POST['title'] !== $simpleblog['title'])
				file_put_contents($settings_file, str_replace('$simpleblog[\'title\']=\'' . $simpleblog['title'] . '\'', '$simpleblog[\'title\']=\'' . $_POST['title'] . '\'', file_get_contents($settings_file)));

		if(isset($_POST['shorttitle']))
			if($_POST['shorttitle'] !== $simpleblog['short_title'])
				file_put_contents($settings_file, str_replace('$simpleblog[\'short_title\']=\'' . $simpleblog['short_title'] . '\'', '$simpleblog[\'short_title\']=\'' . $_POST['shorttitle'] . '\'', file_get_contents($settings_file)));

		if(isset($_POST['entriesperpage']))
			if(($_POST['entriesperpage'] !== $simpleblog['entries_per_page']) && ($_POST['entriesperpage'] != 0))
				if(filter_var($_POST['entriesperpage'], FILTER_VALIDATE_INT) !== false)
					file_put_contents($settings_file, str_replace('$simpleblog[\'entries_per_page\']=' . $simpleblog['entries_per_page'], '$simpleblog[\'entries_per_page\']=' . $_POST['entriesperpage'], file_get_contents($settings_file)));

		if(isset($_POST['taglinks']))
		{
			if(!$simpleblog['taglinks'])
				file_put_contents($settings_file, str_replace('$simpleblog[\'taglinks\']=false', '$simpleblog[\'taglinks\']=true', file_get_contents($settings_file)));
		}
		else
		{
			if($simpleblog['taglinks'])
				file_put_contents($settings_file, str_replace('$simpleblog[\'taglinks\']=true', '$simpleblog[\'taglinks\']=false', file_get_contents($settings_file)));				
		}

		reload();
	}

	// maintenace break pattern
	if(isset($_GET['applymbp']))
	{
		if(isset($_POST['mbenabled']))
		{
			if(!$maintenace_break['enabled'])
				file_put_contents($settings_file, str_replace('$maintenace_break[\'enabled\']=false', '$maintenace_break[\'enabled\']=true', file_get_contents($settings_file)));
		}
		else
		{
			if($maintenace_break['enabled'])
				file_put_contents($settings_file, str_replace('$maintenace_break[\'enabled\']=true', '$maintenace_break[\'enabled\']=false', file_get_contents($settings_file)));		
		}

		if(isset($_POST['mballowedip']))
			if($_POST['mballowedip'] !== $maintenace_break['allowed_ip'])
				file_put_contents($settings_file, str_replace('$maintenace_break[\'allowed_ip\']=\'' . $maintenace_break['allowed_ip'] . '\'', '$maintenace_break[\'allowed_ip\']=\'' . $_POST['mballowedip'] . '\'', file_get_contents($settings_file)));

		reload();
	}

	if(isset($_GET['mbpedit']))
	{
		if(isset($_POST['file_content']))
		{
			if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();
			file_put_contents($adminpanel['path']['mbp'], $_POST['file_content']);
			include 'mbpedit.php'; exit();
		}
		else
		{
			include 'mbpedit.php'; exit();
		}
	}

	// reset credentials
	if(isset($_GET['resetCredentials']))
	{
		if(isset($_POST['resetCredentialsConfirm']))
			copy($adminpanel['root_php'] . '/lib/prevent-index.php', $adminpanel['root_php'] . '/passwordChangeRequired.php');
		reload();
	}

	// create backup
	if(isset($_GET['doBackup']))
		if(file_exists($adminpanel['root_php'] . '/lib/zip.lib.php'))
		{
			// create new zip
			include $adminpanel['root_php'] . '/lib/zip.lib.php';
			$zip=new zipfile();

			// dump all articles
			foreach(scandir($adminpanel['path']['articles']) as $article)
				if(($article != '.') && ($article != '..'))
					$zip->addFile(file_get_contents($adminpanel['path']['articles'] . '/' . $article), 'articles/' . $article);

			// dump cron tasks
			if(file_exists($adminpanel['path']['cron']))
				foreach(scandir($adminpanel['path']['cron']) as $cronTask)
					if(($cronTask != '.') && ($cronTask != '..'))
						$zip->addFile(file_get_contents($adminpanel['path']['cron'] . '/' . $cronTask), 'cron/' . $cronTask);

			// dump all media
			foreach(scandir($adminpanel['path']['media']) as $file)
				if(($file != '.') && ($file != '..') && ($file != 'index.php'))
					$zip->addFile(file_get_contents($adminpanel['path']['media'] . '/' . $file), 'media/' . $file);

			// dump pages
			foreach(adminpanel_backupSearchRecursive($adminpanel['path']['pages'], '') as $pageFile)
				$zip->addFile(file_get_contents($adminpanel['path']['pages'] . '/' . $pageFile), 'pages/' . $pageFile);

			// dump skins
			foreach(adminpanel_backupSearchRecursive($adminpanel['path']['skins'], '') as $skinFile)
				$zip->addFile(file_get_contents($adminpanel['path']['skins'] . '/' . $skinFile), 'skins/' . $skinFile);

			// dump settings
			if(php_sapi_name() === 'cli-server')
				$zip->addFile(file_get_contents($simpleblog['root_php'] . '/router.php'), 'router.php');
			else
				$zip->addFile(file_get_contents($simpleblog['root_php'] . '/settings.php'), 'settings.php');

			// dump maintenace break pattern
			if(file_exists($simpleblog['root_php'] . '/lib/maintenace-break.php'))
				$zip->addFile(file_get_contents($simpleblog['root_php'] . '/lib/maintenace-break.php'), 'lib/maintenace-break.php');

			// dump favicons
			foreach(scandir($adminpanel['path']['favicon']) as $faviconFile)
				if(($faviconFile != '.') && ($faviconFile != '..') && ($faviconFile != 'index.php'))
					$zip->addFile(file_get_contents($adminpanel['path']['favicon'] . '/' . $faviconFile), 'lib/favicon/' . $faviconFile);

			// more files
			$zip->addFile(file_get_contents($simpleblog['root_php'] . '/lib/footer.php'), 'lib/footer.php');
			$zip->addFile(file_get_contents($simpleblog['root_php'] . '/lib/header.php'), 'lib/header.php');
			$zip->addFile(file_get_contents($simpleblog['root_php'] . '/lib/headlinks.php'), 'lib/headlinks.php');
			$zip->addFile(file_get_contents($simpleblog['root_php'] . '/lib/htmlheaders.php'), 'lib/htmlheaders.php');

			// send
			header("Content-type: application/octet-stream"); header("Content-Disposition: attachment; filename=simpleblog_backup_" . date('d-m-Y') . ".zip"); header("Content-Description: Simpleblog backup");
			echo $zip->file();

			exit();
		}

	// lockdown button
	/* if(isset($_GET['lockdown']))
	{
		if(is_link($adminpanel['root_php'] . '/lib/prevent-index.php'))
			link($simpleblog['root_php'] . '/lib/prevent-index.php', $adminpanel['root_php'] . '/disabled.php');
		else
			copy($simpleblog['root_php'] . '/lib/prevent-index.php', $adminpanel['root_php'] . '/disabled.php');
		echo 'The admin panel is locked down';
		exit();
	} */
?>
<!DOCTYPE html>
<html>
	<head>
		<title>CMS</title>
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
			<h3>CMS</h3>
		</div>
		<div id="content">
			<h3>Settings</h3>
			<form action="?apply" method="post">
				<label for="title">&lt;title&gt;</label>
				<input type="text" name="title" value="<?php echo $simpleblog['title']; ?>">

				<label for="shorttitle">Short title</label>
				<input type="text" name="shorttitle" value="<?php echo $simpleblog['short_title']; ?>">

				<label for="entriesperpage">Entries per page</label>
				<input type="text" name="entriesperpage" pattern="\d+" required value="<?php echo $simpleblog['entries_per_page']; ?>">

				Taglinks
				<label>
					<?php
						if($simpleblog['taglinks'])
							echo '<input type="checkbox" name="taglinks" value="true" checked>';
						else
							echo '<input type="checkbox" name="taglinks" value="true">';
					?>
					<span class="lever"></span>
				</label>
				<br><br>

				<input type="submit" class="button" value="Apply">
			</form>

			<h3>Login credentials</h3>
			<!-- <form action="?apply" method="post">
				<label for="newusername">New login</label>
				<input type="text" name="newusername" required disabled>

				<label for="oldpassword">Old Password</label>
				<input type="password" name="oldpassword" required disabled>

				<label for="newpassword">New Password</label>
				<input type="password" name="newpassword" required disabled>

				<input type="submit" class="button" value="Apply" disabled>
			</form> -->
			<form action="?resetCredentials" method="post">
				<input type="submit" class="button" value="Reset credentials">
				<label>
					<input type="checkbox" name="resetCredentialsConfirm" value="true">
					<span class="lever"></span>
				</label>
			</form>

			<?php if(file_exists($simpleblog['root_php'] . '/lib/maintenace-break.php')) { ?>
			<h3>Maintenace break pattern</h3>
			<form action="?applymbp" method="post">
				Enabled
				<label class="checkbox">
					<?php
						if($maintenace_break['enabled'])
							echo '<input type="checkbox" name="mbenabled" value="true" checked>';
						else
							echo '<input type="checkbox" name="mbenabled" value="true">';
					?>
					<span class="lever"></span>
				</label>
				<br><br>

				<label for="mballowedip">Allowed IP</label>
				<input type="text" name="mballowedip" value="<?php echo $maintenace_break['allowed_ip']; ?>">

				<div class="button" style="float: left;"><a href="?mbpedit">Edit pattern</a></div> <input type="submit" class="button" value="Apply">
			</form>
			<?php } ?>

			<?php
				if(file_exists($adminpanel['root_php'] . '/lib/zip.lib.php'))
				{
					echo '<h3>Backup</h3>';
					echo '<div class="button" style="float: left;"><a href="?doBackup" target="_blank">Dump content</a></div>';
					echo '<br><br>';
				}
			?>

			<!-- <h3>Panic button</h3>
			This button will lockdown the admin panel<br>
			<div class="button" style="float: left;"><a href="?lockdown">I'm very scared</a></div> -->
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>