<?php header('X-Frame-Options: DENY'); ?>
<?php header('X-XSS-Protection:0'); ?>
<?php
	// Admin panel for simpleblog - cron section
	// 25.11.2019
	$module['id']='admin-cron';

	// import settings
	include '../admin-settings.php';

	// login subsystem
	include $adminpanel['root_php'] . '/lib/login/login.php';

	// restore $cron
	foreach(explode(PHP_EOL, file_get_contents($adminpanel['path']['cron_bin'])) as $cronLine)
	{
		$cronLine=trim($cronLine);
		$cronLineVarname=strstr($cronLine, '=', true);
		if($cronLineVarname == '$cron[\'enabled\']')
			eval($cronLine);
	}
?>
<?php
	// disable or enable cron
	if((isset($_GET['apply'])) && (adminpanel_csrf_checkToken('get')))
	{
		if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();
		if(isset($_POST['cronenabled']))
		{
			if(!$cron['enabled'])
				file_put_contents($adminpanel['path']['cron_bin'], str_replace('$cron[\'enabled\']=false', '$cron[\'enabled\']=true', file_get_contents($adminpanel['path']['cron_bin'])));
		}
		else
		{
			if($cron['enabled'])
				file_put_contents($adminpanel['path']['cron_bin'], str_replace('$cron[\'enabled\']=true', '$cron[\'enabled\']=false', file_get_contents($adminpanel['path']['cron_bin'])));		
		}
		echo '<!DOCTYPE html><html><head><title>Cron</title><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" type="text/css" href="' . $adminpanel['root_html'] . '/skins/' . $adminpanel['skin'] . '"><meta http-equiv="refresh" content="0; url=?"></head></html>';
		exit();
	}

	// enable or disable task
	if((isset($_GET['setState'])) && (isset($_GET['task'])) && (adminpanel_csrf_checkToken('get')))
		if(file_exists($adminpanel['path']['cron'] . '/' . $_GET['task']))
		{
			if(substr($_GET['task'], 0, 2) === 'on')
				rename($adminpanel['path']['cron'] . '/' . $_GET['task'], $adminpanel['path']['cron'] . '/off_' . substr($_GET['task'], 3));
			else
				rename($adminpanel['path']['cron'] . '/' . $_GET['task'], $adminpanel['path']['cron'] . '/on_' . substr($_GET['task'], 4));
		}

	// create task
	if((isset($_GET['create'])) && (isset($_POST['file_name'])) && (adminpanel_csrf_checkToken('post')))
		file_put_contents($adminpanel['path']['cron'] . '/off_' . $_POST['file_name'], '');

	// delete task
	if((isset($_GET['delete'])) && (isset($_GET['task'])))
		if((file_exists($adminpanel['path']['cron'] . '/' . $_GET['task'])) && (!preg_match('/\//i', $_GET['task']))) // && if not string contains '/' character
		{
			if((isset($_GET['yes'])) && (adminpanel_csrf_checkToken('get')))
				unlink($adminpanel['path']['cron'] . '/' . $_GET['task']);
			else
			{
				include 'delete.php'; exit();
			}
		}

	// edit task
	if((isset($_GET['edit'])) && (isset($_GET['task'])))
		if((file_exists($adminpanel['path']['cron'] . '/' . $_GET['task'])) && (!preg_match('/\//i', $_GET['task']))) // && if not string contains '/' character
		{
			if((isset($_POST['file_content'])) && (adminpanel_csrf_checkToken('post')))
			{
				if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();
				file_put_contents($adminpanel['path']['cron'] . '/' . $_GET['task'], $_POST['file_content']);
				include 'edit.php'; exit();
			}
			else
			{
				include 'edit.php'; exit();
			}
		}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Cron</title>
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
			<h3>Cron</h3>
		</div>
		<div id="content">
			<form action="?apply&<?php echo adminpanel_csrf_printToken('parameter'); ?>=<?php echo adminpanel_csrf_printToken('value'); ?>" method="post">
				Enabled
				<label class="checkbox">
					<?php
						if($cron['enabled'])
							echo '<input type="checkbox" name="cronenabled" value="true" checked>';
						else
							echo '<input type="checkbox" name="cronenabled" value="true">';
					?>
					<span class="lever"></span>
				</label>
				<input type="submit" class="button" value="Set">
			</form>
			<table>
				<tr><th>Name</th><th>State</th></tr>
				<?php
					foreach(scandir($adminpanel['path']['cron']) as $task)
						if(($task != '.') && ($task != '..'))
						{
							if(substr($task, 0, 2) === 'on')
								echo '<tr><td>' . substr($task, 3) . '</td><td style="text-align: center;">Enabled</td><td><a href="?task=' . $task . '&setState&' . adminpanel_csrf_printToken('parameter') . '=' . adminpanel_csrf_printToken('value') . '">Disable</a></td><td><a href="?task=' . $task . '&edit">Edit</a></td><td><a href="?task=' . $task . '&delete">Delete</a></td></tr>';
							else
								echo '<tr><td>' . substr($task, 4) . '</td><td style="text-align: center;">Disabled</td><td><a href="?task=' . $task . '&setState&' . adminpanel_csrf_printToken('parameter') . '=' . adminpanel_csrf_printToken('value') . '">Enable</a></td><td><a href="?task=' . $task . '&edit">Edit</a></td><td><a href="?task=' . $task . '&delete">Delete</a></td></tr>';
						}
				?>
			</table>
			<?php
				if(isset($_GET['create']))
					echo '
						<form action="?create" method="post">
							<label for="">Name (eg: new_task.php)</label>
							<input type="text" name="file_name" required>
							<input type="submit" class="button" value="Create">
							' . adminpanel_csrf_injectToken() . '
						</form>
					';
				else
					echo '<div class="button button_in_row"><a href="?create">Create</a></div>';
			?>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>