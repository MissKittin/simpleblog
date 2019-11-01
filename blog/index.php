<?php
	// Blog page renderer
	// 13.04.2019 - 15.04.2019
	// count_pages and articles naming patch 25.09.2019
	// taglinks 29.09.2019 switch 18.10.2019
	// code redesign 01.11.2019

	//isset($_GET['page']) ? $loop_start=$_GET['page'] : $loop_start=1; // max entries on one page, old concept

	$loop_start=1;
	if(isset($_GET['page'])) // is_int() protection
		if(is_numeric($_GET['page']))
			$loop_start=$_GET['page'];
	settype($loop_start, 'integer');

	// pages counter
	function count_pages()
	{
		global $entries_per_page; // in settings.php
		$pages_count=1;
		$pages_ind=0;

		$current_page='1';
		if(isset($_GET['page']))
			$current_page=$_GET['page'];

		global $files; // used in div articles before executing function
		foreach(array_reverse($files) as $file)
		{
			if(($file != '.') && ($file != '..') && (strpos($file, 'public_') === 0))
			{
				// check if article is public (old naming)
				//if(strpos(file_get_contents('articles/' . $file), '$art_public=true;'))
				//{
					// count how many pages are available
					if($pages_ind === $entries_per_page)
					{
						$pages_count++;
						$pages_ind=1; // must reset this
					}
					else
						$pages_ind++;
				//}
			}

		}

		$i=1; // loop indicator
		while($i <= $pages_count)
		{
			if($i == $current_page)
				echo '<div class="page" id="current_page"><a href="?page='. $i .'">' . $i . '</a></div>'; // render current
			else
				echo '<div class="page"><a href="?page='. $i .'">' . $i . '</a></div>'; // render
			$i++;
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo "$page_title"; ?></title>
		<meta charset="utf-8">
		<link rel="shortcut icon" type="image/icon" href="<?php echo "$cms_root"; ?>/favicon.ico">
		<link rel="stylesheet" type="text/css" href="<?php echo "$cms_root"; ?>/style?root=<?php echo "$cms_root"; ?>">
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
				$loop_ind=1; // first if in foreach
				$files=scandir($_SERVER['DOCUMENT_ROOT'] . $cms_root . '/articles/');
				foreach(array_reverse($files) as $file)
				{
					// debug
					//if(($file != '.') && ($file != '..') && (strpos($file, 'public_') === 0))
					//	echo '<!-- processing article ' . ltrim(preg_replace('/\.[^.]+$/', '', $file), '0') . ' -->' . "\n";

					/* Wait a sec!
						"omit" loop doesn't check if article is public or private
						articles may be duplicated on next page [SOLVED]
					*/

					if($loop_ind >= ($loop_start*$entries_per_page)-($entries_per_page-1)) // omit unwanted entries and start from to eg 1-10, 11-20 etc
					{
						if(($file != '.') && ($file != '..') && (strpos($file, 'public_') === 0))
						{
							include 'articles/' . $file;

							// check if article is public (old naming)
							//if($art_public)
							//{
								// render tags if enabled
								if($taglinks)
								{
									$tags='';
									foreach(explode('#', $art_tags) as $tag)
										if($tag != '')
										{
											$tag=trim($tag);
											$tags=$tags . ' <a href="' . $cms_root . 'tag?tag=' . urlencode('#' . $tag) . '">#' . $tag . '</a>';
										}
									$art_tags=$tags;
								}

								// render article
								echo '<div class="article">'."\n";
									echo '<div class="art-tags">'.$art_tags.'</div><div class="art-date">'.$art_date.'</div><div class="art-title"><h2>'.$art_title.'</h2></div>';
									echo "$art_content";
								echo '</div>'."\n";
							//}
							//else
							//	$loop_ind--;
						}

						if($loop_ind === $loop_start*$entries_per_page) // break if the maximum number of entries has been reached
							break;
						else
							$loop_ind++;
					}
					else
						$loop_ind++; // if entry is unwanted
				}
			?>
		</div>
		<div id="pages">
			<?php count_pages(); echo "\n"; ?>
		</div>
		<div id="footer">
			<?php include $_SERVER['DOCUMENT_ROOT'] . $cms_root . '/footer.php'; ?>
		</div>
	</body>
</html>