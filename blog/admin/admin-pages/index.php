<?php header('X-Frame-Options: DENY'); ?>
<?php
	// Admin panel for simpleblog - pages section
	// 20.11.2019
	// WTF: no upload button ?
	$module['id']='admin-pages';

	// import settings
	include '../admin-settings.php';

	// login subsystem
	include $adminpanel['root_php'] . '/lib/login/login.php';

	// convertBytes library
	include $adminpanel['root_php'] . '/lib/convertBytes.php';
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
					if((isset($_GET['yes'])) && (adminpanel_csrf_checkToken('get')))
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
					if((isset($_POST['file_content'])) && (adminpanel_csrf_checkToken('post')))
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
			else if(isset($_POST['rename']))
			{
				// rename link
				if((!preg_match('/\//i', $_POST['rename'])) && (adminpanel_csrf_checkToken('post'))) // '..' hack and sec_csrf
				{
					rename($adminpanel['path']['pages'] . '/' . $_GET['manage'] . '/' . $_GET['oldname'], $adminpanel['path']['pages'] . '/' . $_GET['manage'] . '/' . $_POST['rename']);
					include('manage.php'); exit();
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
					if((isset($_GET['yes'])) && (adminpanel_csrf_checkToken('get')))
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
					if((!file_exists($adminpanel['path']['pages'] . '/' . $_GET['manage'] . '/' . $_POST['create'])) && (!preg_match('/\//i', $_GET['manage'])) && (!preg_match('/\//i', $_GET['create'])) && (adminpanel_csrf_checkToken('post')))
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
			if((isset($_GET['yes'])) && (adminpanel_csrf_checkToken('get')))
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

	// rename link
	if(isset($_POST['rename']))
	{
		if((!preg_match('/\//i', $_POST['rename'])) && (adminpanel_csrf_checkToken('post'))) // '..' hack and sec_csrf
			rename($adminpanel['path']['pages'] . '/' . $_GET['oldname'], $adminpanel['path']['pages'] . '/' . $_POST['rename']);
	}

	// disable/enable link
	if(isset($_GET['disable']))
		if((file_exists($adminpanel['path']['pages'] . '/' . $_GET['disable'])) && (!file_exists($adminpanel['path']['pages'] . '/' . $_GET['disable'] . '/disabled.php')) && (!preg_match('/\//i', $_GET['disable'])) && (adminpanel_csrf_checkToken('get')))
		{
			copy($adminpanel['root_php'] . '/lib/prevent-index.php', $adminpanel['path']['pages'] . '/' . $_GET['disable'] . '/disabled.php');
		}
	if(isset($_GET['enable']))
		if((file_exists($adminpanel['path']['pages'] . '/' . $_GET['enable'] . '/disabled.php')) && (!preg_match('/\//i', $_GET['enable'])) && (adminpanel_csrf_checkToken('get')))
		{
			unlink($adminpanel['path']['pages'] . '/' . $_GET['enable'] . '/disabled.php');
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
			if((!file_exists($adminpanel['path']['pages'] . '/' . $_GET['create'])) && (!preg_match('/\//i', $_GET['create'])) && (adminpanel_csrf_checkToken('get')))
			{
				mkdir($adminpanel['path']['pages'] . '/' . $_GET['create']);
				file_put_contents($adminpanel['path']['pages'] . '/' . $_GET['create'] . '/index.php', '<?php if(php_sapi_name() != \'cli-server\') include \'../../settings.php\'; ?>'."\n".'<?php if(file_exists(\'disabled.php\')) { include $simpleblog[\'root_php\'] . \'/lib/prevent-index.php\'; exit(); } ?>'."\n".'<!DOCTYPE html>'."\n".'<html>'."\n\t".'<head>'."\n\t\t".'<title><?php echo $simpleblog[\'title\']; ?></title>'."\n\t\t".'<meta charset="utf-8">'."\n\t\t".'<?php include $simpleblog[\'root_php\'] . \'/lib/htmlheaders.php\'; ?>'."\n\t".'</head>'."\n\t".'<body>'."\n\t\t".'<div id="header">'."\n\t\t\t".'<?php include $simpleblog[\'root_php\'] . \'/lib/header.php\'; ?>'."\n\t\t".'</div>'."\n\t\t".'<div id="headlinks">'."\n\t\t\t".'<?php include $simpleblog[\'root_php\'] . \'/lib/headlinks.php\'; ?>'."\n\t\t".'</div>'."\n\t\t".'<div id="articles">'."\n\t\t\t\n\t\t".'</div>'."\n\t\t".'<div id="footer">'."\n\t\t\t".'<?php include $simpleblog[\'root_php\'] . \'/lib/footer.php\'; ?>'."\n\t\t".'</div>'."\n\t".'</body>'."\n".'</html>'."\n".'<?php if(isset($simpleblog[\'execTime\'])) error_log(\'Simpleblog execution time in seconds: \' . (microtime(true) - $simpleblog[\'execTime\']), 0); ?>');
				copy($adminpanel['root_php'] . '/lib/prevent-index.php', $adminpanel['path']['pages'] . '/' . $_GET['create'] . '/disabled.php');
			}
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

							if(file_exists($adminpanel['path']['pages'] . '/' . $file . '/disabled.php'))
								$disableLink='<a href="?enable=' . $file . '&' . adminpanel_csrf_printToken('parameter') . '=' . adminpanel_csrf_printToken('value') . '">Enable</a>';
							else
								$disableLink='<a href="?disable=' . $file . '&' . adminpanel_csrf_printToken('parameter') . '=' . adminpanel_csrf_printToken('value') . '">Disable</a>';

							echo '<tr>
								<td><a href="' . $adminpanel['path']['pages_html'] . '/' . $file . '" target="_blank">' . $file . '</a></td>
								<td style="text-align: center">' . $countfiles . '</td>
								<td style="text-align: center">' . adminpanel_convertBytes($size) . '</td>
								<td><a href="?manage=' . $file . '">Manage</a></td>
								<td><a href="?delete=' . $file . '">Delete</a></td>
								<td><a href="?rename=' . $file . '">Rename</a></td>
								<td><a href="?link=' . $file . '">Link</a></td><td>' . $disableLink . '</td>
							</tr>';
						}
				?>
			</table>
			<div class="button button_in_row"><a href="?create">Create</a></div>
			<?php
				if(isset($_GET['rename']))
				{
					echo '<br><br><br>
						<form action="?oldname=' . $_GET['rename'] . '" method="post">
							<label for="rename">Rename ' . $_GET['rename'] . ' to</label>
							<input type="text" name="rename" value="' . $_GET['rename'] . '" required>
							<input type="submit" class="button" value="Rename">
							' . adminpanel_csrf_injectToken() . '
						</form>
					';
				}

				if(isset($_GET['link']))
				{
					echo '<br><br><h3>Generated link</h3>';
					echo '&lt;a class="headlink" href="&lt;?php echo $simpleblog[\'root_html\']; ?&gt;/pages/' . $_GET['link'] . '"&gt;Page title&lt;/a&gt;<br><br>';
					echo '<div class="button button_in_row"><a href="' . $adminpanel['root_html'] . '/admin-elements?edit=headlinks">Go to editor</a></div>';
				}
			?>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>