<?php header('X-Frame-Options: DENY'); ?>
<?php
	// Admin panel for simpleblog
	// 12-13.11.2019
	$module['id']='admin-status';

	// import settings
	if(file_exists('admin-settings.php'))
		include 'admin-settings.php';
	else
		include '../admin-settings.php';

	// login subsystem
	include $adminpanel['root_php'] . '/lib/login/login.php';

	// convertBytes library
	include $adminpanel['root_php'] . '/lib/convertBytes.php';

	// functions
	function adminpanel_stats()
	{
		// helpers

		// count articles
		function adminpanel_countArticles($dir)
		{
			// counters
			$returnArray['published']=0; $returnArray['hidden']=0;

			// cache
			global $adminpanel;
			if(empty($adminpanel['cache']['core_files']))
				$adminpanel['cache']['core_files']=scandir($dir); // dump filelist
			$files=$adminpanel['cache']['core_files'];

			foreach($files as $file)
				if(($file != '.') && ($file != '..'))
				{
					if(substr($file, 0, 6) === 'public')
						$returnArray['published']++;
					else
						$returnArray['hidden']++;
				}

			return $returnArray;
		}

		// count tags
		function adminpanel_countTags($dir)
		{
			// globals
			global $simpleblog;
			global $cms_root;
			global $cms_root_php;

			// counters
			$returnArray['published']=0;
			$returnArray['hidden']=0;

			// cache
			global $adminpanel;
			if(empty($adminpanel['cache']['core_files']))
				$adminpanel['cache']['core_files']=scandir($dir); // dump filelist
			$files=$adminpanel['cache']['core_files'];

			$tags=array();
			foreach($files as $file)
				if(substr($file, 0, 6) === 'public')
				{
					include $dir . '/' . $file;
					foreach(explode('#', $art_tags) as $tag)
					{
						$tag=trim($tag); // remove space at the end
						if(($tag != '') && (!in_array($tag, $tags))) // omit empty value
						{
							$returnArray['published']++;
							array_push($tags, $tag);
						}
					}
				}

			$hiddenTags=array();
			foreach($files as $file)
				if(substr($file, 0, 7) === 'private')
				{
					include $dir . '/' . $file;
					foreach(explode('#', $art_tags) as $tag)
					{
						$tag=trim($tag); // remove space at the end
						if(($tag != '') && (!in_array($tag, $tags)) && (!in_array($tag, $hiddenTags))) // omit empty value and published tags
						{
							$returnArray['hidden']++;
							array_push($hiddenTags, $tag);
						}
					}
				}

			return $returnArray;
		}

		// count pages
		function adminpanel_countPages($dir)
		{
			// counter
			$returnArray['published']=0;
			$returnArray['hidden']=0;

			// cache
			global $adminpanel;
			if(empty($adminpanel['cache']['core_pages']))
				$adminpanel['cache']['core_pages']=scandir($dir); // dump filelist
			$files=$adminpanel['cache']['core_pages'];

			foreach($files as $file)
				if(($file != '.') && ($file != '..') && (is_dir($dir . '/' . $file)))
				{
					if(file_exists($dir . '/' . $file . '/disabled.php'))
						$returnArray['hidden']++;
					else
						$returnArray['published']++;
				}

			return $returnArray;
		}

		// count cron tasks
		function adminpanel_countCron($dir)
		{
			// check if tmp folder exists
			if(file_exists($dir))
			{
				// counters
				$returnArray['enabled']=0; $returnArray['disabled']=0;

				// cache
				global $adminpanel;
				if(empty($adminpanel['cache']['core_cron']))
					$adminpanel['cache']['core_cron']=scandir($dir); // dump filelist
				$files=$adminpanel['cache']['core_cron'];

				foreach($files as $file)
					if(($file != '.') && ($file != '..'))
					{
						if(substr($file, 0, 3) === 'on_')
							$returnArray['enabled']++;
						else
							$returnArray['disabled']++;
					}

				return $returnArray;
			}
			return false;
		}

		// count cms size
		function adminpanel_countCmsSize($dir, $action)
		{
			$returnCount=0;
			switch($action)
			{
				case 'size':
					foreach(new DirectoryIterator($dir) as $file)
						if(($file != '.') && ($file != '..'))
						{
							if(is_dir($dir . '/' . $file))
								$returnCount+=adminpanel_countCmsSize($dir . '/' . $file, 'size');
							else
								$returnCount+=$file->getSize();
						}
					break;
				case 'count':
					foreach(scandir($dir) as $file)
						if(($file != '.') && ($file != '..'))
						{
							if(is_dir($dir . '/' . $file))
								$returnCount+=adminpanel_countCmsSize($dir . '/' . $file, 'count');
							else
								$returnCount++;
						}
					break;
			}
			return $returnCount;
		}

		// main

		global $adminpanel;
		global $simpleblog;

		// collect stats
		$stats['articles']=adminpanel_countArticles($adminpanel['path']['articles']);
		$stats['tags']=adminpanel_countTags($adminpanel['path']['articles']);
		$stats['pages']=adminpanel_countPages($adminpanel['path']['pages']);
		$stats['cron']=adminpanel_countCron($adminpanel['path']['cron']);

		// display ?>
		<h3>Stats</h3>
		<ul>
			<li>Articles published: <?php echo $stats['articles']['published']; ?>, hidden: <?php echo $stats['articles']['hidden']; ?></li>
			<li>Tags published: <?php echo $stats['tags']['published']; ?>, hidden: <?php echo $stats['tags']['hidden']; ?></li>
			<li>Pages published: <?php echo $stats['pages']['published']; ?>, hidden: <?php echo $stats['pages']['hidden']; ?></li>
			<li>Media: <?php echo adminpanel_countCmsSize($adminpanel['path']['media'], 'count'); ?> files (<?php echo adminpanel_convertBytes(adminpanel_countCmsSize($adminpanel['path']['media'], 'size')); ?>)</li>
			<?php if($stats['cron'] != false) echo '<li>Cron tasks: ' . $stats['cron']['enabled'] . ' enabled, ' . $stats['cron']['disabled'] . ' disabled</li>'; ?>
			<?php if(file_exists($adminpanel['path']['tmp'])) echo '<li>Temporary files: ' . adminpanel_countCmsSize($adminpanel['path']['tmp'], 'count') . ' (' . adminpanel_convertBytes(adminpanel_countCmsSize($adminpanel['path']['tmp'], 'size')) . ')</li>'; ?>
			<li>CMS size: <?php echo adminpanel_countCmsSize($simpleblog['root_php'], 'count'); ?> files (<?php echo adminpanel_convertBytes(adminpanel_countCmsSize($simpleblog['root_php'], 'size')); ?>)</li>
		</ul><?php
	}
	function adminpanel_serverInfo()
	{
		global $simpleblog;

		?>
		<h3>Server</h3>
		HTTP Server: <?php echo $_SERVER['SERVER_SOFTWARE']; ?><br>
		PHP version: <?php echo phpversion(); ?><br>
		<?php
			if(file_exists($simpleblog['root_php'] . '/settings.php'))
				echo 'Simpleblog is configured for Apache'."\n";
			elseif(file_exists($simpleblog['root_php'] . '/.router.php'))
				echo 'Simpleblog is configured for PHP built-in server'."\n";
		?>
		<?php
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Admin panel</title>
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
			<h3>CMS Status</h3>
		</div>
		<div id="content">

			<?php adminpanel_stats(); ?>

			<?php adminpanel_serverInfo(); ?>

			<h3>About</h3>
			Admin panel for Simpleblog v2.1u1<br>
			Version 1.2u1.1<br>
			<a style="text-decoration: none; color: #0000ff;" target="_blank" href="https://github.com/MissKittin">MissKittin</a>@<a style="text-decoration: none; color: #0000ff;" target="_blank" href="https://github.com/MissKittin/simpleblog">GitHub</a><br>
			Licensed under <a style="text-decoration: none; color: #0000ff;" target="_blank" href="https://www.gnu.org/licenses/gpl-3.0.html">GNU General Public License v3.0</a>
		</div>
		<div id="footer">
			<?php include $adminpanel['root_php'] . '/lib/footer.php'; ?>
		</div>
	</body>
</html>