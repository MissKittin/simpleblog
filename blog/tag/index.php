<?php if(php_sapi_name() != 'cli-server') include '../settings.php'; ?>
<?php
	// Tag browser
	// 28-29.09.2019

	// Enter into jail
	chdir($cms_root_php . '/articles');
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo "$page_title"; ?></title>
		<meta charset="utf-8">
		<?php include $cms_root_php . '/htmlheaders.php'; ?>
	</head>
	<body>
		<div id="header">
			<?php include $cms_root_php . '/header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include $cms_root_php . '/headlinks.php'; ?>
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
									// debug
									//echo '<!-- processing article ' . ltrim(preg_replace('/\.[^.]+$/', '', $file), '0') . ' -->' . "\n";

									// render article
									if(isset($art_style['article'])) echo '<div class="article" style="' . $art_style['article'] . '">'."\n"; else echo '<div class="article">'."\n";
										if(isset($art_style['tags'])) echo '<div class="art-tags" style="' . $art_style['tags'] . '">'.$art_tags.'</div>'; else echo '<div class="art-tags">'.$art_tags.'</div>';
										if(isset($art_style['date'])) echo '<div class="art-date" style="' . $art_style['date'] . '">'.$art_date.'</div>'."\n"; else echo '<div class="art-date">'.$art_date.'</div>'."\n";
										if(isset($art_style['title'])) echo '<div class="art-title" style="' . $art_style['title'] . '">'; else echo '<div class="art-title">';
											if(isset($art_style['title-header'])) { if(($art_style['title-header'] === '') || ($art_style['title-header'])) echo '<h2>'.$art_title.'</h2>'; else echo $art_title; } else echo '<h2>'.$art_title.'</h2>';
										echo '</div>'."\n";
										echo $art_content;
									echo '</div>'."\n";

									// clean
									unset($art_style);

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
			<?php include $cms_root_php . '/footer.php'; ?>
		</div>
	</body>
</html>