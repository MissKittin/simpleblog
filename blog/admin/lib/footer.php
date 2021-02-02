<?php
	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/footer.php')
	{
		include './prevent-index.php'; exit();
	}
?>
<span id="footerText"><span style="display:inline-block; transform: rotate(180deg);">&copy;</span> MissKittin@GitHub, 2019</span>
