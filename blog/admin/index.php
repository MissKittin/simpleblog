<?php if(php_sapi_name() != 'cli-server') include '../settings.php'; ?>
<?php
	// Admin page
	// 25.09.2019

	// setup
	chdir($_SERVER['DOCUMENT_ROOT'] . '/' . $cms_root . '/articles');

	// primitive security
	if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $cms_root . '/admin/disabled.php'))
	{
		include $_SERVER['DOCUMENT_ROOT'] . '/' . $cms_root . '/admin/disabled.php';
		exit();
	}

	// lock
	if(isset($_GET['lock']))
	{
		if(isset($_GET['sure']))
		{
			chdir($_SERVER['DOCUMENT_ROOT'] . '/' . $cms_root . '/admin');
			$files=scandir('.');
			foreach($files as $file)
				if(($file != '.') && ($file != '..') && ($file != 'index.php'))
				{
					rename($file, 'disabled.php');
				}
			include $_SERVER['DOCUMENT_ROOT'] . '/' . $cms_root . '/admin/disabled.php';
			exit();
		}
		else
		{
			echo '<!DOCTYPE html><head><title>Confirm</title><meta charset="utf-8"></head><body><h1>Are you sure?</h1><a href="?lock&sure">Yes</a></body></html>';
			exit();
		}
	}

	// view source
	if(isset($_GET['source']))
		if(file_exists($_GET['source']))
		{
			header('Content-Type: text/plain;');
			if(preg_match('/\//i', $_GET['source'])) // if string contains '/' character
			{
				echo 'Denied!';
				exit();
			}
			echo file_get_contents($_GET['source']);
			exit();
		}
?>
<?php if(isset($_GET['new'])) { if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset(); ?>
	<?php
		// save new article
		if(isset($_POST['save_new']))
		{
			$file_content='<?php' . "\n";
			$file_content=$file_content . "\t" . '$art_title=' . "'" . $_POST['art_title'] . "';\n";
			$file_content=$file_content . "\t" . '$art_date=' . "'" . $_GET['date'] . "';\n";
			$file_content=$file_content . "\t" . '$art_tags=' . "'" . $_POST['art_tags'] . "';\n";
			if($_GET['public'])
				$file_content=$file_content . "\t" . '$art_public=true;' . "\n";
			else
				$file_content=$file_content . "\t" . '$art_public=false;' . "\n";
			$file_content=$file_content . "\t" . '$art_content=' . "'\n" . $_POST['art_content'] . "\n\t';\n";
			$file_content=$file_content . '?>';
			file_put_contents($_GET['filename'], $file_content);
		}
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>New article</title>
			<meta charset="utf-8">
		</head>
		<body>
			<h1>Write new article</h1>
			<div><a style="text-decoration: none;" href="<?php echo $cms_root; ?>/admin">Back</a></div>
			<?php
				if(isset($_GET['filename']))
					$filename=$_GET['filename'];
				else
				{
					$out_of_space=false;

					$filename=array_reverse(scandir('.'));
					if(substr($filename[0], 0, 6) === 'public')
						$filename['data']=str_replace('.php', '', str_replace('public_', '', $filename[0]));
					else
						$filename['data']=str_replace('.php', '', str_replace('private_', '', $filename[0]));
					$filename['length']=strlen($filename['data']);
					$filename['data']=$filename['data']+1;
					$filename['length']=$filename['length']-strlen($filename['data']);
					if($filename['length'] < 0)
						$out_of_space=true;
					for($i=1; $i<=$filename['length']; $i++)
						$filename['data']='0' . $filename['data'];
					$filename='private_' . $filename['data'] . '.php';

					if($out_of_space)
					{
						echo '<span style="font-weight: bold; color: #cc0000;">! No address space available</span>';
						echo '</body></html>';
						exit();
					}
				}

				$file_exists=false;
				$art_public=true;
				$art_date=date('d.m.o');
				if(file_exists($filename))
				{
					$file_exists=true;
					include $filename;
				}
			?>
			<div style="margin-top: 10px;">
				<form action="?new&filename=<?php echo $filename; ?>&date=<?php echo $art_date; ?>&public=<?php echo $art_public; ?>" method="post">
					Title: <input type="text" name="art_title" <?php if($file_exists) echo 'value="' . $art_title . '"'; ?>><br><br>
					Tags: <input type="text" name="art_tags" <?php if($file_exists) echo 'value="' . $art_tags . '"'; ?>><br><br>
					Content: <div><textarea name="art_content" rows="30" cols="80"><?php if($file_exists) echo $art_content; ?></textarea><div><br>
					<input type="submit" name="save_new" value="Save"> <?php if($file_exists) echo '<a style="text-decoration: none;" target="_blank" href="?show=' . $filename . '">Preview</a> <a style="text-decoration: none;" target="_blank" href="?source=' . $filename . '">Source</a>'; ?>
				</form>
			</div>
		</body>
	</html>
<?php exit(); } ?>
<?php if(isset($_GET['edit'])) { if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset(); ?>
	<?php
		// save edited article
		if(isset($_POST['save_edited']))
		{
			$file_content='<?php' . "\n";
			$file_content=$file_content . "\t" . '$art_title=' . "'" . $_POST['art_title'] . "';\n";
			$file_content=$file_content . "\t" . '$art_date=' . "'" . $_GET['date'] . "';\n";
			$file_content=$file_content . "\t" . '$art_tags=' . "'" . $_POST['art_tags'] . "';\n";
			if($_GET['public'])
				$file_content=$file_content . "\t" . '$art_public=true;' . "\n";
			else
				$file_content=$file_content . "\t" . '$art_public=false;' . "\n";
			$file_content=$file_content . "\t" . '$art_content=' . "'\n" . $_POST['art_content'] . "\n\t';\n";
			$file_content=$file_content . '?>';
			file_put_contents($_GET['edit'], $file_content);
		}
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Edit article</title>
			<meta charset="utf-8">
		</head>
		<body>
			<h1>Edit article</h1>
			<div><a style="text-decoration: none;" href="<?php echo $cms_root; ?>/admin">Back</a></div>
			<?php
				if(!file_exists($_GET['edit']))
				{
					echo '<span style="font-weight: bold; color: #cc0000;">! File not exists</span>';
					echo '</body></html>';
					exit();
				}
				include $_GET['edit'];
			?>
			<div style="margin-top: 10px;">
				<form action="?edit=<?php echo $_GET['edit']; ?>&date=<?php echo $art_date; ?>&public=<?php echo $art_public; ?>" method="post">
					Title: <input type="text" name="art_title" <?php echo 'value="' . $art_title . '"'; ?>><br><br>
					Tags: <input type="text" name="art_tags" <?php echo 'value="' . $art_tags . '"'; ?>><br><br>
					Content: <div><textarea name="art_content" rows="30" cols="80"><?php echo $art_content; ?></textarea><div><br>
					<input type="submit" name="save_edited" value="Save"> <?php echo '<a style="text-decoration: none;" target="_blank" href="?show=' . $_GET['edit'] . '">Preview</a> <a style="text-decoration: none;" target="_blank" href="?source=' . $_GET['edit'] . '">Source</a>'; ?>
				</form>
			</div>
		</body>
	</html>
<?php exit(); }  // main page ?>
<?php
	// show rendered article
	if(isset($_GET['show']))
		if(file_exists($_GET['show']))
		{
			include $_GET['show'];
			// header
			echo '<!DOCTYPE html>
				<html>
					<head>
						<title>Preview</title>
						<meta charset="utf-8">
						<link rel="shortcut icon" type="image/icon" href="' . $cms_root . '/favicon.ico">
						<link rel="stylesheet" type="text/css" href="' . $cms_root . '/style?root=' . $cms_root . '">
					</head>
					<body>
						<div id="articles">
							<div class="article">';
				echo '<div class="art-tags">'.$art_tags.'</div><div class="art-date">'.$art_date.'</div><div class="art-title"><h2>'.$art_title.'</h2></div>';
				echo "$art_content";
			echo '</div></div></body></html>';
			exit();
		}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Admin</title>
		<meta charset="utf-8">
		<style>
			table, tr, th, td {
				border: 1px solid #000000;
				border-collapse: collapse;
			}
		</style>
	</head>
	<body>
		<?php
			// 'publish' link
			if(isset($_GET['publish']))
				if(file_exists($_GET['publish']))
				{
					rename($_GET['publish'], str_replace('private', 'public', $_GET['publish']));
				}
			// 'hide' link
			if(isset($_GET['hide']))
				if(file_exists($_GET['hide']))
				{
					rename($_GET['hide'], str_replace('public', 'private', $_GET['hide']));
				}
		?>
		<h1>Articles</h1>
		<a href="?new">Write new article</a>
		<table>
			<tr><th>File name</th><th>Title</th><th>Date</th><th>Tags</th><th>Public flag</th></tr>
			<?php
				// list and count articles
				$articles_indicator=0;
				$public_articles_indicator=0;
				$files=scandir('.');
				foreach(array_reverse($files) as $file)
					if(($file != '.') && ($file != '..'))
					{
						echo '<tr><td><a style="text-decoration: none;" href="?show=' . $file . '">' . $file . '</a></td>';
						include $file;
						if(substr($file, 0, 6) === 'public')
						{
							$public_articles_indicator++;
							$publish_get_param='hide';
							$publish_get_title="Hide";
						}
						else
						{
							$publish_get_param='publish';
							$publish_get_title="Publish";
						}
						$published=$art_public ? 'Yes' : 'No';
						echo '<td>' . $art_title . '</td><td style="padding: 2px;">' . $art_date . '</td><td>' . $art_tags . '</td><td style="text-align: center;">' . $published . '</td><td><a style="text-decoration: none;" href="?' . $publish_get_param . '=' . $file . '">' . $publish_get_title . '</a></td><td><a style="text-decoration: none;" href="?edit=' . $file . '">Edit</a></td></tr>' . "\n";
						$articles_indicator++;
						unset($published);
					}
			?>
		</table>
		<h3>All articles: <?php echo $articles_indicator; ?>, Published: <?php echo $public_articles_indicator; ?></h3>
		<a href="?lock">Disable administation</a>
	</body>
</html>