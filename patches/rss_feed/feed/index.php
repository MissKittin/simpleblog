<?php
	// simpleblog rss feed patch
	// 13.04.2020

	// optional parameter in settings:
	//	$simpleblog['feed']['domain']='http://custom.domain';
	// cache location dir: $simpleblog['root_php'] /tmp/feed_cache
	// cache (re)generation indicator: $simpleblog['root_php'] /tmp/feed_cache/generate_cache

	// settings
	$simpleblog['cache']['cacheFeed']['cache_dir']=$simpleblog['root_php'] . '/tmp/feed_cache';

	// import apache settings
	if(php_sapi_name() != 'cli-server') include '../settings.php';

	// protocol
	if(isset($_SERVER['HTTPS'])) $protocol='https://'; else $protocol='http://';

	// if url is not patched
	if(!isset($simpleblog['feed']['domain']))
		$simpleblog['feed']['domain']=$protocol . $_SERVER['HTTP_HOST'];

	// add website address to sources and links
	$simpleblog['root_html']=$simpleblog['feed']['domain'] . $simpleblog['root_html'];
	if(isset($cms_root)) $cms_root=$simpleblog['root_html']; // backward compatibility

	// render function
	if(!function_exists('simpleblog_engineFeed'))
	{
		function simpleblog_engineFeed()
		{
			global $simpleblog;
			global $protocol;
			global $cms_root;
			global $cms_root_php;

			$return='';

			foreach(array_filter(scandir($simpleblog['root_php'] . '/articles', 1), function($file){
				if(strpos($file, 'public_') === 0)
					return true;
				return false;
			}) as $article)
			{
				include $simpleblog['root_php'] . '/articles/' . $article;
				$return.='<item>';
					$return.='<title>' . str_replace(['<!--', '-->'], '', $art_title) . '</title>';
					$return.='<link>' . $simpleblog['root_html'] . '/post?id=' . (int)str_replace(['public_', '.php'], '', $article) . '</link>';
					$return.='<description>' . htmlspecialchars($art_content) . '</description>';
				$return.='</item>';
				unset($art_title); unset($art_content);
			}

			return $return;
		}
	}

	// generate cache
	if(file_exists($simpleblog['cache']['cacheFeed']['cache_dir'] . '/generate_cache'))
	{
		unlink($simpleblog['cache']['cacheFeed']['cache_dir'] . '/generate_cache');
		file_put_contents($simpleblog['cache']['cacheFeed']['cache_dir'] . '/feed.php', simpleblog_engineFeed());
	}

	// for admin-feed (1)
	if(!isset($feed_defineOnly)) {

	header('Content-type: text/xml');
?>
<?php echo "<?xml version='1.0' encoding='UTF-8' ?>"; ?>
<rss version='2.0'>
	<channel>
		<title><?php echo $simpleblog['title']; ?></title>
		<link><?php echo $simpleblog['feed']['domain'] . $_SERVER['REQUEST_URI']; ?></link>
		<language><?php echo $simpleblog['html_lang']; ?></language>
		<?php
			// read from cache or render
			if(!@include $simpleblog['cache']['cacheFeed']['cache_dir'] . '/feed.php')
				echo simpleblog_engineFeed();
		?>
	</channel>
</rss>
<?php /* for admin-feed (2) */ } ?>