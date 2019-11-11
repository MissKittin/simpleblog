<?php if(php_sapi_name() != 'cli-server') include '../settings.php'; ?>
<?php
	// Tag browser
	// 28-29.09.2019

	// Enter into jail
	chdir($cms_root_php . '/articles');
?>
<?php
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
		}
		elseif(($i === '<?php') || ($strip)) // string begins php code
			$strip=true;
		else // html string
			echo $i . "\n";
	}
?>