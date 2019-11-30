<?php
	// deny direct access
	if(php_sapi_name() === 'cli-server')
	{
		if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/headlinks.php')
		{
			include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
		}
	}
	else
	{
		if(!isset($simpleblog))
		{
			include 'prevent-index.php'; exit();
		}
	}
?>
<a class="headlink" href="<?php echo $simpleblog['root_html']; ?>/pages/samplepage">Sample page</a>
<a class="headlink" href="<?php echo $simpleblog['root_html']; ?>/tag">Tags</a>
<a class="headlink" href="<?php echo $simpleblog['root_html']; ?>/">Home</a>
