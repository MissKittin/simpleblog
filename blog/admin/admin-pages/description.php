<?php
	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/description.php')
	{
		include '../lib/prevent-index.php'; exit();
	}
?>
<?php
	$module['description']='Pages';
?>