<?php
	// deny direct access
	if(!isset($simpleblog))
	{
		include './prevent-index.php'; exit();
	}
?>
<a class="headlink" href="<?php echo $simpleblog['root_html']; ?>/pages/samplepage">Sample page</a>
<a class="headlink" href="<?php echo $simpleblog['root_html']; ?>/tag">Tags</a>
<a class="headlink" href="<?php echo $simpleblog['root_html']; ?>/">Home</a>
