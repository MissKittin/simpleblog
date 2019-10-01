<?php
	// Tag browser
	// 28-29.09.2019

	// Enter into jail
	include '../settings.php';
	chdir($_SERVER['DOCUMENT_ROOT'] . '/' . $cms_root . 'articles');
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo "$page_title"; ?></title>
		<meta charset="utf-8">
		<link rel="shortcut icon" type="image/icon" href="<?php echo "$cms_root"; ?>favicon.ico">
		<link rel="stylesheet" type="text/css" href="<?php echo "$cms_root"; ?>style?root=<?php echo "$cms_root"; ?>">
	</head>
	<body>
		<div id="header">
			<?php include '../header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include '../headlinks.php'; ?>
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
			<?php include '../footer.php'; ?>
		</div>
	</body>
</html>