<?php if(php_sapi_name() != 'cli-server') include '../settings.php'; ?>
<?php
	// Tag browser
	// 28-29.09.2019

	// Enter into jail
	chdir($_SERVER['DOCUMENT_ROOT'] . '/' . $cms_root . '/articles');
?>
<?php
/*
	// Get Layout from main index
	// 10.10.2019

	// This hack is dirty and created for experimental purposes only

	$strip=false;
	foreach(preg_split('/((\r?\n)|(\r\n?))/', file_get_contents('../index.php')) as $i)
	{
		if($i === '?>') // end of php code
			$strip=false;
		elseif(trim($i) === '</div>') // end of div
		{
			$strip=false;
			echo $i . "\n";
		}
		elseif(strpos($i, '<?php echo') !== false) // string has variable
			echo str_replace('$page_title', $page_title, str_replace('$cms_root', $cms_root, str_replace('"; ?>', '', str_replace('<?php echo "', '', $i)))) . "\n";
		elseif(strpos($i, '<?php include') !== false) // string is php include
			include str_replace('\'; ?>', '', str_replace('<?php include \'', '../', trim($i)));
		elseif(trim($i) === '<?php count_pages(); echo "\n"; ?>') // page switches section
		{}
		elseif((trim($i) === '<div id="articles">')) // articles section
		{
			$strip=true;
			echo $i . "\n";

			// Main
			// tag is selected
			if(isset($_GET['tag']))
			{
				$empty_tag=true;

				$files=scandir('.');
				foreach(array_reverse($files) as $file)
					if(substr($file, 0, 6) === 'public')
					{
						include $file;
						foreach(explode('#', $art_tags) as $tag)
						{
							$tag=trim($tag);
							if('#' . $tag === $_GET['tag'])
							{
								//echo '<!-- processing article ' . ltrim(preg_replace('/\.[^.]+$/', '', $file), '0') . ' -->' . "\n"; // debug
								echo '<div class="article">'."\n";
									echo '<div class="art-tags">'.$art_tags.'</div><div class="art-date">'.$art_date.'</div><div class="art-title"><h2>'.$art_title.'</h2></div>';
									echo "$art_content";
								echo '</div>'."\n";
								$empty_tag=false;
							}
						}
					}
				if($empty_tag)
					echo '<h1 style="text-align: center;">Empty</h1>';
			}
			else
			{
				// list tags
				echo '<div id="taglinks">';

				$no_tags=true; // if there are no tags in database
				$tags=array(); $i=0; // bypass if tag is already listed

				$files=scandir('.');
				foreach(array_reverse($files) as $file)
				{
					if(substr($file, 0, 6) === 'public')
					{
						include $file;
						foreach(explode('#', $art_tags) as $tag)
						{
							$tag=trim($tag); // remove space at the end
							if(($tag != '') && (!in_array($tag, $tags))) // omit empty value
							{
								echo '<a class="taglink" href="?tag=' . urlencode('#' . $tag) . '">#' . $tag . '</a><br>';
								$tags[$i]=$tag; $i++;
								$no_tags=false; // dont print 'Empty'
							}
						}
					}
				}
				if($no_tags)
					echo '<h1 style="text-align: center;">Empty</h1>';

				echo '</div>';
			}
		}
		elseif(($i === '<?php') || ($strip)) // string begins php code
			$strip=true;
		else // html string
			echo $i . "\n";
	}

	exit();
*/
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo "$page_title"; ?></title>
		<meta charset="utf-8">
		<?php include $_SERVER['DOCUMENT_ROOT'] . $cms_root . '/htmlheaders.php'; ?>
	</head>
	<body>
		<div id="header">
			<?php include $_SERVER['DOCUMENT_ROOT'] . $cms_root . '/header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $_SERVER['DOCUMENT_ROOT'] . $cms_root . '/headlinks.php'; ?>
		</div>
		<div id="articles">
			<?php
				// tag is selected
				if(isset($_GET['tag']))
				{
					$empty_tag=true;

					$files=scandir('.');
					foreach(array_reverse($files) as $file)
						if(substr($file, 0, 6) === 'public')
						{
							include $file;
							foreach(explode('#', $art_tags) as $tag)
							{
								$tag=trim($tag);
								if('#' . $tag === $_GET['tag'])
								{
									//echo '<!-- processing article ' . ltrim(preg_replace('/\.[^.]+$/', '', $file), '0') . ' -->' . "\n"; // debug
									echo '<div class="article">'."\n";
										echo '<div class="art-tags">'.$art_tags.'</div><div class="art-date">'.$art_date.'</div><div class="art-title"><h2>'.$art_title.'</h2></div>';
										echo "$art_content";
									echo '</div>'."\n";
									$empty_tag=false;
								}
							}
						}
					if($empty_tag)
						echo '<h1 style="text-align: center;">Empty</h1>';
				}
				else
				{
					// list tags
					echo '<div id="taglinks">';

					$no_tags=true; // if there are no tags in database
					$tags=array(); $i=0; // bypass if tag is already listed

					$files=scandir('.');
					foreach(array_reverse($files) as $file)
					{
						if(substr($file, 0, 6) === 'public')
						{
							include $file;
							foreach(explode('#', $art_tags) as $tag)
							{
								$tag=trim($tag); // remove space at the end
								if(($tag != '') && (!in_array($tag, $tags))) // omit empty value
								{
									echo '<a class="taglink" href="?tag=' . urlencode('#' . $tag) . '">#' . $tag . '</a><br>';
									$tags[$i]=$tag; $i++;
									$no_tags=false; // dont print 'Empty'
								}
							}
						}
					}
					if($no_tags)
						echo '<h1 style="text-align: center;">Empty</h1>';

					echo '</div>';
				}
			?>
		</div>
		<div id="footer">
			<?php include $_SERVER['DOCUMENT_ROOT'] . $cms_root . '/footer.php'; ?>
		</div>
	</body>
</html>