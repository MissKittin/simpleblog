<?php
	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/TabManager.php')
	{
		include '../prevent-index.php'; exit();
	}
?>
<script type="text/javascript" src="<?php echo $adminpanel['root_html']; ?>/lib/TabManager.js"></script>
