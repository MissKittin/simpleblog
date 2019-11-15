<?php
	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/footer.php')
	{
		include 'prevent-index.php'; exit();
	}
?>
<span id="footerText">2019, MissKittin@GitHub</span>
