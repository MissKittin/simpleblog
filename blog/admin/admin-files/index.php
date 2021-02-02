<?php header('X-Frame-Options: DENY'); ?>
<?php
	// Admin panel for simpleblog - file manager
	// 26.11.2019
	// uses $simpleblog array
	$module['id']='admin-files';

	// import settings
	include '../admin-settings.php';

	// login subsystem
	include $adminpanel['root_php'] . '/lib/login/login.php';

	// convertBytes library
	include $adminpanel['root_php'] . '/lib/convertBytes.php';

	// directoryIteratorSort library
	include $adminpanel['root_php'] . '/lib/directoryIteratorSort.php';

	// functions
	function adminpanel_rmr($dir, $first)
	{
		if(is_dir($dir))
		{
			foreach(scandir($dir) as $file)
				if(($file != '.') && ($file != '..'))
				{
					if(is_dir($dir . '/' . $file))
					{
						adminpanel_rmr($dir . '/' . $file, false);
						rmdir($dir . '/' . $file);
					}
					else
						unlink($dir . '/' . $file);
				}
			if($first)
				rmdir($dir);
		}
		else
			unlink($dir);
	}
?>
<?php
	// create file
	if((isset($_POST['newfile_name'])) && (adminpanel_csrf_checkToken('post')))
	{
		if(isset($_GET['dir']))
		{
			if((!in_array('..', explode('/', $_GET['dir'] . '/' . $_POST['newfile_name']))) && (file_exists($simpleblog['root_php'] . '/' . $_GET['dir']))) // '..' hack
				file_put_contents($simpleblog['root_php'] . '/' . $_GET['dir'] . '/' . $_POST['newfile_name'], '');
		}
		else
		{
			if(!in_array('..', explode('/', $_POST['newfile_name'])))
				file_put_contents($simpleblog['root_php'] . '/' . $_POST['newfile_name'], '');
		}
	}

	// make directory
	if((isset($_POST['newdir_name'])) && (adminpanel_csrf_checkToken('post')))
	{
		if(isset($_GET['dir']))
		{
			if((!in_array('..', explode('/', $_GET['dir'] . '/' . $_POST['newdir_name']))) && (file_exists($simpleblog['root_php'] . '/' . $_GET['dir']))) // '..' hack
				mkdir($simpleblog['root_php'] . '/' . $_GET['dir'] . '/' . $_POST['newdir_name']);
		}
		else
		{
			if(!in_array('..', explode('/', $_POST['newdir_name'])))
				mkdir($simpleblog['root_php'] . '/' . $_POST['newdir_name']);
		}
	}

	// upload
	if((isset($_GET['uploadConfirm'])) && (ini_get('file_uploads') == 1) && (adminpanel_csrf_checkToken('get')))
	{
		$countfiles=count($_FILES['file']['name']);
		if(isset($_GET['dir']))
		{
			if((!in_array('..', explode('/', $_GET['dir']))) && (file_exists($simpleblog['root_php'] . '/' . $_GET['dir']))) // '..' hack
				for($i=0; $i<$countfiles; ++$i)
					move_uploaded_file($_FILES['file']['tmp_name'][$i], $simpleblog['root_php'] . '/' . $_GET['dir'] . '/' . $_FILES['file']['name'][$i]);
		}
		else
			for($i=0; $i<$countfiles; ++$i)
				move_uploaded_file($_FILES['file']['tmp_name'][$i], $simpleblog['root_php'] . '/' . $_FILES['file']['name'][$i]);
	}

	// curl
	if((isset($_POST['curl'])) && (!isset($adminpanel['nocurl'])) && (adminpanel_csrf_checkToken('get')))
		if(substr($_POST['curl'], 0, 7) !== 'file://')
		{
			$curl=curl_init();
			curl_setopt($curl, CURLOPT_URL, $_POST['curl']);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_VERBOSE, true);
			$downloadedFile=curl_exec($curl);
			if(isset($_GET['dir']))
			{
				if((!in_array('..', explode('/', $_GET['dir'] . '/' . $_POST['curlFilename']))) && (file_exists($simpleblog['root_php'] . '/' . $_GET['dir'])) && (!file_exists($simpleblog['root_php'] . '/' . $_GET['dir'] . '/' . $_POST['curlFilename']))) // '..' hack
					file_put_contents($simpleblog['root_php'] . '/' . $_GET['dir'] . '/' . $_POST['curlFilename'], $downloadedFile);
			}
			else
				if((!preg_match('/\//i', $_POST['curlFilename'])) && (!file_exists($simpleblog['root_php'] . '/' . $_POST['curlFilename']))) // '..' hack
					file_put_contents($simpleblog['root_php'] . '/' . $_POST['curlFilename'], $downloadedFile);
			curl_close($curl);
		}

	// rename link (move function by '..' in file name)
	if((isset($_POST['rename'])) && (adminpanel_csrf_checkToken('post')))
	{
		if(isset($_GET['dir']))
		{
			if((!in_array('..', explode('/', $_GET['dir']))) && (file_exists($simpleblog['root_php'] . '/' . $_GET['dir'] . '/' . $_GET['oldname']))) // '..' hack ($_GET['dir'] only)
				rename($simpleblog['root_php'] . '/' . $_GET['dir'] . '/' . $_GET['oldname'], $simpleblog['root_php'] . '/' . $_GET['dir'] . '/' . $_POST['rename']);
		}
		else
		{
			if((!in_array('..', explode('/', $_POST['rename']))) && (file_exists($simpleblog['root_php'] . '/' . $_GET['oldname']))) // '..' hack (full)
				rename($simpleblog['root_php'] . '/' . $_GET['oldname'], $simpleblog['root_php'] . '/' . $_POST['rename']);
		}	
	}

	// delete link
	if(isset($_GET['delete']))
	{
		if(isset($_GET['dir']))
		{
			if((isset($_GET['yes'])) && (adminpanel_csrf_checkToken('get')))
			{
				if((!in_array('..', explode('/', $_GET['dir'] . '/' . $_GET['delete']))) && (file_exists($simpleblog['root_php'] . '/' . $_GET['dir'] . '/' . $_GET['delete']))) // '..' hack
					adminpanel_rmr($simpleblog['root_php'] . '/' . $_GET['dir'] . '/' . $_GET['delete'], true);
			}
			else
			{
				include './delete.php'; exit();
			}
		}
		else
		{
			if((isset($_GET['yes'])) && (adminpanel_csrf_checkToken('get')))
			{
				if((!in_array('..', explode('/', $_GET['delete']))) && (file_exists($simpleblog['root_php'] . '/' . $_GET['delete'])))
					adminpanel_rmr($simpleblog['root_php'] . '/' . $_GET['delete'], true);
			}
			else
			{
				include './delete.php'; exit();
			}
		}
	}

	// edit link
	if(isset($_GET['edit']))
	{
		if(isset($_GET['dir']))
		{
			if((!in_array('..', explode('/', $_GET['dir'] . '/' . $_GET['edit']))) && (file_exists($simpleblog['root_php'] . '/' . $_GET['dir'] . '/' . $_GET['edit']))) // '..' hack
			{
				if((isset($_POST['file_content'])) && (adminpanel_csrf_checkToken('post')))
				{
					if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();
					file_put_contents($simpleblog['root_php'] . '/' . $_GET['dir'] . '/' . $_GET['edit'], $_POST['file_content']);
					include './edit.php'; exit();
				}
				else
				{
					include './edit.php'; exit();
				}
			}
		}
		else
		{
			if(!in_array('..', explode('/', $_GET['edit'])))
			{
				if((isset($_POST['file_content'])) && (adminpanel_csrf_checkToken('post')))
				{
					if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();
					file_put_contents($simpleblog['root_php'] . '/' . $_GET['edit'], $_POST['file_content']);
					include './edit.php'; exit();
				}
				else
				{
					include './edit.php'; exit();
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Files</title>
		<meta charset="utf-8">
		<?php include $adminpanel['root_php'] . '/lib/htmlheaders.php'; ?>
		<style type="text/css">
			.folder {
				 list-style-image: url("data:image/png;base64,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")
			}
			.file {
				list-style-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH4wYMEwUuJlNtNwAAA0hJREFUWMOdVjFLHEEU/vbtrKcEvLtO0MZG0bNVUtsddrYWFkLSaGWpJKSwS6wsEvCEK/wDIgE5UtiZdLb5ASIIEg2IhTuTInnL23dvdjUDw+4MO/u++b73vpkEop2cnHyemppaDiEghADvfemp50MIyPMcAPD09HR6dnb2fn9//zde0BI5OD8//zY3N7ccQgAAeO+R5zm890VnADzPY+99uLu7Sy4vL19vbW19fy4AkgMOnCR/cRERnHNI07ToRIQ0TYt5IuKeNJtNLC0tXfR6vXcAsLe393IAHFw+dbAkSYbAMbBms4lOp/Ph8PDwdGdnpxZECYAVnN8lC2maFiAsdlqtFhYWFlYYxMHBwcsYkF0CYuqJCFmWSfoLEM45NBoNtFotdDqdlaOjox+bm5vo9Xr/L4EliQwu5XDOYWxsrAAxPz+/2O/3v25sbJhyuBgAfo890zQtyeS9BxFhZGQEo6OjJRZnZ2e7/X7/y/r6+ttKAEQEKyf43XtfAsi5IPvNzQ2urq6QZRmyLEOj0UCSJJienn5zfHz8c21t7VMUgPd+KPksBvibEEJRFTx3fX2NwWBQWs++MTMz83F3d7fXbrd/bW9v2xIQUeFyMpBmRANzziHPc0xOTqLb7ZrO6b3HxMTEYHV1ddFkQFLJC6TGEoAGwVXSbrcxPj4edc/b29tXUQk0GCIqyRLLDw2IE1RKy1Ix2CgAKQH/jHfPi59TLTpB+dDKsqy6DE2zUKD0uVFXqvxdnudDzDmLevljGYglsZLRalyqRFQE199GnTDmBdqArHOjyj11fpgMxBKMG+dFLHmtPAFQgIgyYMlgHU6cF3qnco4DsXR6vvI4tt51MClJjLVYwj7rMIrdktgtpQR6rNfJwLUSyA/0zuTPhrJZ0CzHen0lA7yAk0wHkhcW+SO5W5mgdVUVvZTyDqxdaCDmT/8ZVyyPanNA66f1tQxJb8Ji0ZQuVoZaTyuhYuYjS07bceWdMFbfvCPpB5LmmBTW2loA2jxi5qMrRrMUq5LaMqwylJj5WPRXSVmbhLLW9TErDYUvGrFjvMoBKyWQJahp1bTHzEmfAbE8KTHw+PiI+/t702Tku2RCj+UVTl5MuT08PJQA/AFsdDEU9qilXgAAAABJRU5ErkJggg==");
			}
		</style>
	</head>
	<body>
		<div id="header">
			<?php include $adminpanel['root_php'] . '/lib/header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $adminpanel['root_php'] . '/lib/menu/' . $adminpanel['menu_module'] . '/menu.php'; ?>
		</div>
		<div id="content_header">
			<h3>Files</h3>
		</div>
		<div id="content">
			<table>
				<?php
					$dir='';
					if(isset($_GET['dir']))
						if((!in_array('..', explode('/', $_GET['dir']))) && (file_exists($simpleblog['root_php'] . '/' . $_GET['dir']))) // '..' hack
							$dir=$_GET['dir'] . '/';

					foreach(adminpanel_directoryIteratorSort($simpleblog['root_php'] . '/' . $dir) as $file)
						if(($file['name'] != '.') && ($file['name'] != '..'))
						{
							if(is_dir($simpleblog['root_php'] . '/' . $dir . $file['name']))
							{
								echo '<tr>
									<td><li class="folder"></li></td>
									<td><a href="?dir=' . $dir . $file['name'] . '">' . $file['name'] . '</a></td>
									<td></td>
									<td>' . gmdate('d.m.Y', $file['ctime']) . '</td>
									<td></td>
									<td><a href="?rename=' . $file['name']; if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; echo '">Rename</a></td>
									<td><a href="?delete=' . $file['name']; if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; echo '">Delete</a></td>
								</tr>';
							}
							else
							{
								echo '<tr>
									<td><li class="file"></li></td>
									<td><a href="' . $simpleblog['root_html'] . '/' . $dir . $file['name'] . '" target="_blank">' . $file['name'] . '</a></td>
									<td style="text-align: center;">' . adminpanel_convertBytes($file['size']) . '</td>
									<td>' . gmdate('d.m.Y', $file['ctime']) . '</td>
									<td><a href="?edit=' . $file['name']; if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; echo '">Edit</a></td>
									<td><a href="?rename=' . $file['name']; if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; echo '">Rename</a></td>
									<td><a href="?delete=' . $file['name']; if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; echo '">Delete</a></td>
								</tr>';
							}
						}
				?>
			</table>
			<?php
				if(isset($_GET['create']))
				{
					echo '
						<form action="?'; if(isset($_GET['dir'])) echo 'dir=' . $_GET['dir']; echo '" method="post">
							<label for="newfile_name">New file name</label>
							<input type="text" name="newfile_name" required>
							<input type="submit" class="button" value="Create new file">
							' . adminpanel_csrf_injectToken() . '
						</form>
					';
				}

				if(isset($_GET['mkdir']))
				{
					echo '
						<form action="?'; if(isset($_GET['dir'])) echo 'dir=' . $_GET['dir']; echo '" method="post">
							<label for="newdir_name">New directory name</label>
							<input type="text" name="newdir_name" required>
							<input type="submit" class="button" value="Create new directory">
							' . adminpanel_csrf_injectToken() . '
						</form>
					';
				}

				if(isset($_GET['upload']))
				{
					echo '
						<form action="?uploadConfirm'; if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; echo '&' . adminpanel_csrf_printToken('parameter') . '=' . adminpanel_csrf_printToken('value') . '" method="post" enctype="multipart/form-data">
							<input type="file" name="file[]" id="file" multiple>
							<input class="button" type="submit" value="Upload">
						</form>
					';
				}

				if(isset($_GET['rename']))
				{
					echo '
						<form action="?oldname=' . $_GET['rename']; if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; echo '" method="post">
							<label for="rename">Rename ' . $_GET['rename'] . ' to</label>
							<input type="text" name="rename" value="' . $_GET['rename'] . '" required>
							<input type="submit" class="button" value="Rename">
							' . adminpanel_csrf_injectToken() . '
						</form>
					';
				}

				if(isset($_GET['curl']))
				{
					echo '
						<form action="?'; if(isset($_GET['dir'])) echo 'dir=' . $_GET['dir']; echo '&' . adminpanel_csrf_printToken('parameter') . '=' . adminpanel_csrf_printToken('value') . '" method="post">
							<label for="curl">URL</label>
							<input type="text" name="curl" required>
							<label for="curlFilename">File name</label>
							<input type="text" name="curlFilename" required>
							<input type="submit" class="button" value="Download">
						</form>
					';
				}

				if(isset($_GET['dir']))
					if($_GET['dir'] != '')
						echo '<div class="button button_in_row"><a href="?dir=' . substr($_GET['dir'], 0, strrpos($_GET['dir'], '/')) . '">Back</a></div>';
			?>
			<div class="button button_in_row"><a href="?create<?php if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; ?>">Create</a></div>
			<div class="button button_in_row"><a href="?mkdir<?php if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; ?>">mkdir</a></div>
			<?php if(ini_get('file_uploads') == 1) { ?><div class="button button_in_row"><a href="?upload<?php if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; ?>">Upload</a></div><?php } ?>
			<?php if((function_exists('curl_init')) && (!isset($adminpanel['nocurl']))) { ?><div class="button button_in_row"><a href="?curl<?php if(isset($_GET['dir'])) echo '&dir=' . $_GET['dir']; ?>">curl</a></div><?php } ?>
			<br><br><h3>Move function: click rename and add ../ before name to move upper</h3>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>