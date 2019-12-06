<?php
	// Admin panel for simpleblog - articles section
	// 13.11.2019
	$module['id']='admin-articles';

	// import settings
	include '../admin-settings.php';

	// login subsystem
	include $adminpanel['root_php'] . '/lib/login/login.php';

	// import simpleblog core
	include $adminpanel['root_php'] . '/lib/core.php';

	// create html headers
	function adminArticles_htmlheaders()
	{
		global $adminpanel;
		?>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<link rel="stylesheet" type="text/css" href="<?php echo $adminpanel['root_html']; ?>/skins/<?php echo $adminpanel['skin']; ?>">
			<script type="text/javascript" src="<?php echo $adminpanel['root_html']; ?>/lib/TabManager.js"></script>
		<?php
	}

	// functions
	function newArticle_countNewArticleName($dir)
	{
		global $out_of_space; // flag

		// read content and set indicator
		$filename=scandir($dir);
		$filename['data']=0;

		// select greatest value
		foreach($filename as $filename['item'])
		{
			if(($filename['item'] != '.') && ($filename['item'] != '..'))
			{
				if(substr($filename['item'], 0, 6) === 'public')
					$filename['item']=str_replace('.php', '', str_replace('public_', '', $filename['item']));
				else
					$filename['item']=str_replace('.php', '', str_replace('private_', '', $filename['item']));

				if($filename['item'] > $filename['data'])
					$filename['data']=$filename['item'];
			}
		}

		// calculate name length
		$filename['length']=strlen($filename['data']);
		$filename['data']=$filename['data']+1; // greater than greatest
		$filename['length']=$filename['length']-strlen($filename['data']);

		// if out of space
		if($filename['length'] < 0)
			$out_of_space=true;

		// add zeros
		for($i=1; $i<=$filename['length']; $i++)
			$filename['data']='0' . $filename['data'];

		return 'private_' . $filename['data'] . '.php';
	}
?>
<?php
	// article creator
	if(isset($_GET['write']))
	{
		if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();
?>
<?php
	// save new article
	if(isset($_POST['article_content']))
		file_put_contents($adminpanel['path']['articles'] . '/' . $_GET['filename'], $_POST['article_content']);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>New article</title>
		<?php adminArticles_htmlheaders(); ?>
	</head>
	<body>
		<div id="header">
			<?php include $adminpanel['root_php'] . '/lib/header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $adminpanel['root_php'] . '/lib/menu/' . $adminpanel['menu_module'] . '/menu.php'; ?>
		</div>
		<div id="content_header">
			<h3>Edit article</h3>
		</div>
		<div id="content">
			<?php
				if(isset($_GET['filename']))
					$filename=$_GET['filename'];
				else
				{
					$out_of_space=false;
					$filename=newArticle_countNewArticleName($adminpanel['path']['articles']);
					if($out_of_space)
					{
						echo '<span style="font-weight: bold; color: #cc0000;">! No address space available</span>';
						echo '</body></html>';
						exit();
					}
				}
			?>
			<form action="?write&filename=<?php echo $filename; ?>" method="post">
				<textarea name="article_content" style="height: 1024px; width: 99%;"><?php if(file_exists($adminpanel['path']['articles'] . '/' . $filename)) echo file_get_contents($adminpanel['path']['articles'] . '/' . $filename); else echo '<?php' . "\n\t" . '$art_title=\'\';' . "\n\t" . '$art_date=\'' . date('d.m.Y') . '\';' . "\n\t" . '$art_tags=\'\';' . "\n" . "\n\t" . '//$art_style[\'article\']=\'\';' . "\n\t" . '//$art_style[\'tags\']=\'\';' . "\n\t" . '//$art_style[\'taglink\']=\'\';' . "\n\t" . '//$art_style[\'date\']=\'\';' . "\n\t" . '//$art_style[\'title\']=\'\';' . "\n\t" . '//$art_style[\'title-header\']=false;' . "\n\n\t" . '$art_content=\'' . "\n\t\t\n\t" . '\';' . "\n" . '?>'; ?></textarea>
				<div style="float: left;" class="button"><a href="?">Back</a></div> <?php if(file_exists($adminpanel['path']['articles'] . '/' . $filename)) echo '<div style="float: left;" class="button"><a href="?show=' . $_GET['filename'] . '" target="_blank">Preview</a></div>'; ?> <input type="submit" class="button" value="Save">
			</form>
			<h3>Cheat sheet</h3>
			// article meta<br>
			$art_title='Article title';<br>
			$art_date='DD.MM.YYYY';<br>
			$art_tags='#sample tag #sample second tag';<br><br>
			// article styles<br>
			$art_style['article']=''; // div id article<br>
			$art_style['tags']=''; // div id art-tags<br>
			$art_style['taglink']=''; // links in art-tags (if taglinks enabled)<br>
			$art_style['date']=''; // div id art-date<br>
			$art_style['title']=''; // div id art-title<br>
			$art_style['title-header']=false; // disable h2 in art-title<br><br>
			$art_content='Article content';
			<br><h3>Add from media</h3>
			&lt;img src="' . $simpleblog['root_html'] . '/media/image.img" alt="image"&gt;
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>
<?php exit(); } ?>
<?php
	// article editor
	if(isset($_GET['edit']))
		if((file_exists($adminpanel['path']['articles'] . '/' . $_GET['edit'])) && (!preg_match('/\//i', $_GET['edit']))) // && if not string contains '/' character
		{
			if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();
?>
<?php
	// save edited article
	if(isset($_POST['article_content']))
		file_put_contents($adminpanel['path']['articles'] . '/' . $_GET['edit'], $_POST['article_content']);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Edit article</title>
		<?php adminArticles_htmlheaders(); ?>
	</head>
	<body>
		<div id="header">
			<?php include $adminpanel['root_php'] . '/lib/header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $adminpanel['root_php'] . '/lib/menu/' . $adminpanel['menu_module'] . '/menu.php'; ?>
		</div>
		<div id="content_header">
			<h3>Edit article</h3>
		</div>
		<div id="content">
			<form action="?edit=<?php echo $_GET['edit']; ?>" method="post">
				<textarea name="article_content" style="height: 1024px; width: 99%;"><?php echo file_get_contents($adminpanel['path']['articles'] . '/' . $_GET['edit']); ?></textarea>
				<div style="float: left;" class="button"><a href="?">Back</a></div> <div style="float: left;" class="button"><a href="?show=<?php echo $_GET['edit']; ?>" target="_blank">Preview</a></div> <input type="submit" class="button" value="Save">
			</form>
			<h3>Cheat sheet</h3>
			// article meta<br>
			$art_title='Article title';<br>
			$art_date='DD.MM.YYYY';<br>
			$art_tags='#sample tag #sample second tag';<br><br>
			// article styles<br>
			$art_style['article']=''; // div id article<br>
			$art_style['tags']=''; // div id art-tags<br>
			$art_style['taglink']=''; // links in art-tags (if taglinks enabled)<br>
			$art_style['date']=''; // div id art-date<br>
			$art_style['title']=''; // div id art-title<br>
			$art_style['title-header']=false; // disable h2 in art-title<br><br>
			$art_content='Article content';
			<br><h3>Add from media</h3>
			&lt;img src="' . $simpleblog['root_html'] . '/media/image.img" alt="image"&gt;
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>
<?php exit(); } ?>
<?php
	// main page

	// show article
	if(isset($_GET['show']))
		if((file_exists($adminpanel['path']['articles'] . '/' . $_GET['show'])) && (!preg_match('/\//i', $_GET['show'])))
		{
			echo '<!DOCTYPE html><html><head><title>Preview</title><meta charset="utf-8">'; include $adminpanel['path']['htmlheaders']; echo '<head><body><div id="articles">'."\n";
			simpleblog_engineCore($adminpanel['path']['articles'] . '/' . $_GET['show'], $simpleblog['taglinks'], $simpleblog['postlinks'], $simpleblog['datelinks']);
			echo '</div></body></html>';
			exit();
		}

	// 'publish' link
	if(isset($_GET['publish']))
		if(file_exists($adminpanel['path']['articles'] . '/' . $_GET['publish']))
			rename($adminpanel['path']['articles'] . '/' . $_GET['publish'], $adminpanel['path']['articles'] . '/' . str_replace('private', 'public', $_GET['publish']));
	// 'hide' link
	if(isset($_GET['hide']))
		if(file_exists($adminpanel['path']['articles'] . '/' . $_GET['hide']))
			rename($adminpanel['path']['articles'] . '/' . $_GET['hide'], str_replace('public', 'private', $adminpanel['path']['articles'] . '/' . $_GET['hide']));
	// 'delete' link
	if(isset($_GET['delete']))
		if((file_exists($adminpanel['path']['articles'] . '/' . $_GET['delete'])) && (!preg_match('/\//i', $_GET['delete'])))
		{
			if(isset($_GET['yes']))
				unlink($adminpanel['path']['articles'] . '/' . $_GET['delete']);
			else
			{
				echo '<!DOCTYPE html><html><head>
						<title>Articles</title>
						'; adminArticles_htmlheaders(); echo '
					</head><body>
						<div id="content" style="padding-bottom: 30px;">
							<h1>' . $_GET['delete'] . ' - Are you sure?</h1>
							<div style="float: left;" class="button"><a href="?">Back</a></div> <div style="float: left;" class="button"><a href="?delete=' . $_GET['delete'] . '&yes">Delete</a></div>
						</div>
					</body></html>';
				exit();
			}
		}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Articles</title>
		<?php adminArticles_htmlheaders(); ?>
	</head>
	<body>
		<div id="header">
			<?php include $adminpanel['root_php'] . '/lib/header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $adminpanel['root_php'] . '/lib/menu/' . $adminpanel['menu_module'] . '/menu.php'; ?>
		</div>
		<div id="content_header">
			<h3>Articles</h3>
		</div>
		<div id="content">
			<table>
				<tr><th>File name</th><th>Title</th><th>Date</th><th>Tags</th></tr>
				<?php
					// list and count articles
					$articles_indicator=0;
					$public_articles_indicator=0;
					$files=scandir($adminpanel['path']['articles']);
					foreach(array_reverse($files) as $file)
						if(($file != '.') && ($file != '..'))
						{
							echo '<tr><td><a target="_blank" style="text-decoration: none;" href="?show=' . $file . '">' . $file . '</a></td>';
							include $adminpanel['path']['articles'] . '/' . $file;
							if(substr($file, 0, 6) === 'public')
							{
								$public_articles_indicator++;
								$publish_get_param='hide'; $publish_get_title="Hide";
							}
							else
							{
								$publish_get_param='publish'; $publish_get_title="Publish";
							}
							echo '<td>' . str_replace(['<', '>'], ['&lt;', '&gt;'], $art_title) . '</td><td style="padding: 2px;">' . $art_date . '</td><td>' . $art_tags . '</td><td><a style="text-decoration: none;" href="?' . $publish_get_param . '=' . $file . '">' . $publish_get_title . '</a></td><td><a style="text-decoration: none;" href="?edit=' . $file . '">Edit</a></td><td><a style="text-decoration: none;" href="?delete=' . $file . '">Delete</a></td></tr>' . "\n";
							$articles_indicator++;
							unset($art_title); unset($art_date); unset($art_tags); unset($art_style); unset($art_content);
						}
				?>
			</table>
			<h3>All articles: <?php echo $articles_indicator; ?>, Published: <?php echo $public_articles_indicator; ?></h3>
			<div style="float: left;" class="button"><a href="?write">Write</a></div>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>
