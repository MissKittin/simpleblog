<?php header('X-Frame-Options: DENY'); ?>
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

	// restore $maintenance_break array
	foreach(explode(PHP_EOL, file_get_contents($settings_file)) as $mbLine)
	{
		$mbLine=trim($mbLine);
		$mbLineVarname=strstr($mbLine, '=', true);
		if(($mbLineVarname == '$maintenance_break[\'enabled\']') || ($mbLineVarname == '$maintenance_break[\'allowed_ip\']'))
			eval($mbLine);
	}

	// functions
	function reload()
	{
		global $adminpanel;
		echo '<!DOCTYPE html><html><head><title>CMS</title><meta charset="utf-8">'; include $adminpanel['root_php'] . '/lib/htmlheaders.php'; echo '<meta http-equiv="refresh" content="0; url=?"></head></html>';
		exit();
	}
?>
<?php
	// apply settings
	if((isset($_GET['apply'])) && (adminpanel_csrf_checkToken('get')))
	{
		if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();

		// cms
		if(isset($_POST['startuppage']))
			if(($_POST['startuppage'] !== $simpleblog['startup_page']) && file_exists($adminpanel['path']['pages'] . '/' . $simpleblog['startup_page']))
				file_put_contents($settings_file, str_replace('$simpleblog[\'startup_page\']=\'' . $simpleblog['startup_page'] . '\'', '$simpleblog[\'startup_page\']=\'' . $_POST['startuppage'] . '\'', file_get_contents($settings_file)));

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

		if(isset($_POST['emptylabel']))
			if($_POST['emptylabel'] !== $simpleblog['emptyLabel'])
				file_put_contents($settings_file, str_replace('$simpleblog[\'emptyLabel\']=\'' . $simpleblog['emptyLabel'] . '\'', '$simpleblog[\'emptyLabel\']=\'' . str_replace(['&lt;', '&gt;', "'"], ['<', '>', '"'], $_POST['emptylabel']) . '\'', file_get_contents($settings_file)));

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

		if(isset($_POST['postlinks']))
		{
			if(!$simpleblog['postlinks'])
				file_put_contents($settings_file, str_replace('$simpleblog[\'postlinks\']=false', '$simpleblog[\'postlinks\']=true', file_get_contents($settings_file)));
		}
		else
		{
			if($simpleblog['postlinks'])
				file_put_contents($settings_file, str_replace('$simpleblog[\'postlinks\']=true', '$simpleblog[\'postlinks\']=false', file_get_contents($settings_file)));				
		}

		if(isset($_POST['datelinks']))
		{
			if(!$simpleblog['datelinks'])
				file_put_contents($settings_file, str_replace('$simpleblog[\'datelinks\']=false', '$simpleblog[\'datelinks\']=true', file_get_contents($settings_file)));
		}
		else
		{
			if($simpleblog['datelinks'])
				file_put_contents($settings_file, str_replace('$simpleblog[\'datelinks\']=true', '$simpleblog[\'datelinks\']=false', file_get_contents($settings_file)));				
		}

		reload();
	}

	// maintenance break pattern
	if((isset($_GET['applymbp'])) && (adminpanel_csrf_checkToken('get')))
	{
		if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();

		if(isset($_POST['mbenabled']))
		{
			if(!$maintenance_break['enabled'])
				file_put_contents($settings_file, str_replace('$maintenance_break[\'enabled\']=false', '$maintenance_break[\'enabled\']=true', file_get_contents($settings_file)));
		}
		else
		{
			if($maintenance_break['enabled'])
				file_put_contents($settings_file, str_replace('$maintenance_break[\'enabled\']=true', '$maintenance_break[\'enabled\']=false', file_get_contents($settings_file)));		
		}

		if(isset($_POST['mballowedip']))
			if($_POST['mballowedip'] !== $maintenance_break['allowed_ip'])
				file_put_contents($settings_file, str_replace('$maintenance_break[\'allowed_ip\']=\'' . $maintenance_break['allowed_ip'] . '\'', '$maintenance_break[\'allowed_ip\']=\'' . $_POST['mballowedip'] . '\'', file_get_contents($settings_file)));

		reload();
	}

	if((isset($_GET['mbpedit'])) && (adminpanel_csrf_checkToken('get')))
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
	if((isset($_GET['resetCredentials'])) && (adminpanel_csrf_checkToken('get')))
	{
		if(isset($_POST['resetCredentialsConfirm']))
			copy($adminpanel['root_php'] . '/lib/prevent-index.php', $adminpanel['root_php'] . '/passwordChangeRequired.php');
		reload();
	}

	// create backup
	if((isset($_GET['doBackup'])) && (adminpanel_csrf_checkToken('get')))
		if(file_exists($adminpanel['root_php'] . '/lib/zip.lib.php'))
		{
			// set memory limit
			ini_set('memory_limit','256M');

			// zip library
			include $adminpanel['root_php'] . '/lib/zip.lib.php';

			// fileSearchRecursive library
			include $adminpanel['root_php'] . '/lib/fileSearchRecursive.php';

			// create new zip
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
			foreach(adminpanel_fileSearchRecursive($adminpanel['path']['pages'], '') as $pageFile)
				if($pageFile !== 'index.php')
					$zip->addFile(file_get_contents($adminpanel['path']['pages'] . '/' . $pageFile), 'pages/' . $pageFile);

			// dump skins
			foreach(adminpanel_fileSearchRecursive($adminpanel['path']['skins'], '') as $skinFile)
				if($skinFile !== 'index.php')
					$zip->addFile(file_get_contents($adminpanel['path']['skins'] . '/' . $skinFile), 'skins/' . $skinFile);

			// dump settings
			if(php_sapi_name() === 'cli-server')
				$zip->addFile(file_get_contents($simpleblog['root_php'] . '/.router.php'), '.router.php');
			else
				$zip->addFile(file_get_contents($simpleblog['root_php'] . '/settings.php'), 'settings.php');

			// dump maintenance break pattern
			if(file_exists($simpleblog['root_php'] . '/lib/maintenance-break.php'))
				$zip->addFile(file_get_contents($simpleblog['root_php'] . '/lib/maintenance-break.php'), 'lib/maintenance-break.php');

			// dump favicons
			foreach(scandir($adminpanel['path']['favicon']) as $faviconFile)
				if(($faviconFile != '.') && ($faviconFile != '..') && ($faviconFile != 'index.php'))
					$zip->addFile(file_get_contents($adminpanel['path']['favicon'] . '/' . $faviconFile), 'lib/favicon/' . $faviconFile);

			// more files
			$zip->addFile(file_get_contents($simpleblog['root_php'] . '/lib/footer.php'), 'lib/footer.php');
			$zip->addFile(file_get_contents($simpleblog['root_php'] . '/lib/header.php'), 'lib/header.php');
			$zip->addFile(file_get_contents($simpleblog['root_php'] . '/lib/headlinks.php'), 'lib/headlinks.php');
			$zip->addFile(file_get_contents($simpleblog['root_php'] . '/lib/htmlheaders.php'), 'lib/htmlheaders.php');

			// user's files in root
			foreach(scandir($simpleblog['root_php']) as $customFile)
				if(($customFile != '.') && ($customFile != '..') &&
					($customFile != 'admin') &&
					($customFile != 'articles') &&
					($customFile != 'cron') &&
					($customFile != 'index.php') &&
					($customFile != 'lib') &&
					($customFile != 'media') &&
					($customFile != 'pages') &&
					($customFile != 'post') &&
					($customFile != '.router.php') &&
					($customFile != 'settings.php') &&
					($customFile != 'skins') &&
					($customFile != 'tag') &&
					($customFile != 'tmp')
				)
					$zip->addFile(file_get_contents($simpleblog['root_php'] . '/' . $customFile), $customFile);

			// send
			header("Content-type: application/octet-stream"); header("Content-Disposition: attachment; filename=simpleblog_backup_" . date('d-m-Y') . ".zip"); header("Content-Description: Simpleblog backup");
			echo $zip->file();

			exit();
		}

	// patch cms
	if((isset($_GET['patch'])) && (isset($_POST['confirmPatch'])) && (ini_get('file_uploads') == 1) && (adminpanel_csrf_checkToken('get')))
	{
		$skinpack=new ZipArchive;
		$skinpack->open($_FILES['file']['tmp_name'][0]);
		$skinpack->extractTo($simpleblog['root_php']);
		$skinpack->close();
		unlink($_FILES['file']['tmp_name'][0]);
		reload();
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
			<h3>CMS</h3>
		</div>
		<div id="content">
			<h3>Settings</h3>
			<form action="?apply&<?php echo adminpanel_csrf_printToken('parameter'); ?>=<?php echo adminpanel_csrf_printToken('value'); ?>" method="post">
				<label for="startuppage">Startup page</label>
				<div id="startuppage">
					<?php
						if(!file_exists($adminpanel['path']['pages'] . '/' . $simpleblog['startup_page']))
							echo '<span style="color: #ff0000; -webkit-text-stroke: 1px #990000;">&#9888; WTF: startup page "' . $simpleblog['startup_page'] . '" does not exist!</span><br>';

						foreach(array_diff(scandir($adminpanel['path']['pages']), ['.', '..', 'index.php']) as $page)
						{
							$pageSetting='';
							if($page === $simpleblog['startup_page'])
								$pageSetting='checked';
							echo '<input type="radio" name="startuppage" value="' . $page . '" ' . $pageSetting . '> ' . $page . '<br>';
						}
					?>
				</div>
				<br>

				<label for="title">&lt;title&gt;</label>
				<input type="text" name="title" value="<?php echo $simpleblog['title']; ?>">

				<label for="shorttitle">Short title</label>
				<input type="text" name="shorttitle" value="<?php echo $simpleblog['short_title']; ?>">

				<label for="entriesperpage">Entries per page</label>
				<input type="text" name="entriesperpage" pattern="\d+" required value="<?php echo $simpleblog['entries_per_page']; ?>">

				<label for="emptylabel">Empty label</label>
				<input type="text" name="emptylabel" required value="<?php echo str_replace(['<', '>', '"'], ['&lt;', '&gt;', "'"], $simpleblog['emptyLabel']); ?>">

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

				Postlinks
				<label>
					<?php
						if($simpleblog['postlinks'])
							echo '<input type="checkbox" name="postlinks" value="true" checked>';
						else
							echo '<input type="checkbox" name="postlinks" value="true">';
					?>
					<span class="lever"></span>
				</label>
				<br><br>

				Datelinks
				<label>
					<?php
						if($simpleblog['datelinks'])
							echo '<input type="checkbox" name="datelinks" value="true" checked>';
						else
							echo '<input type="checkbox" name="datelinks" value="true">';
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
			<form action="?resetCredentials&<?php echo adminpanel_csrf_printToken('parameter'); ?>=<?php echo adminpanel_csrf_printToken('value'); ?>" method="post">
				<input type="submit" class="button" value="Reset credentials">
				<label>
					<input type="checkbox" name="resetCredentialsConfirm" value="true">
					<span class="lever"></span>
				</label>
			</form>

			<?php if(file_exists($simpleblog['root_php'] . '/lib/maintenance-break.php')) { ?>
			<h3>Maintenance break pattern</h3>
			<form action="?applymbp&<?php echo adminpanel_csrf_printToken('parameter'); ?>=<?php echo adminpanel_csrf_printToken('value'); ?>" method="post">
				Enabled
				<label class="checkbox">
					<?php
						if($maintenance_break['enabled'])
							echo '<input type="checkbox" name="mbenabled" value="true" checked>';
						else
							echo '<input type="checkbox" name="mbenabled" value="true">';
					?>
					<span class="lever"></span>
				</label>
				<br><br>

				<label for="mballowedip">Allowed IP</label>
				<input type="text" name="mballowedip" value="<?php echo $maintenance_break['allowed_ip']; ?>">

				<div class="button button_in_row"><a href="?mbpedit&<?php echo adminpanel_csrf_printToken('parameter'); ?>=<?php echo adminpanel_csrf_printToken('value'); ?>">Edit pattern</a></div> <input type="submit" class="button" value="Apply">
			</form>
			<?php } ?>

			<?php
				if(file_exists($adminpanel['root_php'] . '/lib/zip.lib.php'))
				{
					echo '<h3>Backup</h3>';
					echo '<div class="button button_in_row"><a href="?doBackup&' . adminpanel_csrf_printToken('parameter') . '=' . adminpanel_csrf_printToken('value') . '" target="_blank">Dump content</a></div>';
					echo '<br><br>';
				}
			?>

			<?php
				if(ini_get('file_uploads') == 1)
				{
					echo '<h3>Patch CMS</h3>
						<form action="?patch&' . adminpanel_csrf_printToken('parameter') . '=' . adminpanel_csrf_printToken('value') . '" method="post" enctype="multipart/form-data">
							<input type="file" name="file[]" id="file" multiple>
							<input class="button" type="submit" value="Apply patch">
							<label>
								<input type="checkbox" name="confirmPatch" value="true">
								<span class="lever"></span>
							</label>
						</form>';
				}
			?>

			<!-- <h3>Panic button</h3>
			This button will lockdown the admin panel<br>
			<div class="button button_in_row"><a href="?lockdown">I'm very scared</a></div> -->
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>