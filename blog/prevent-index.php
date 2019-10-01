<!DOCTYPE html>
<html>
	<head>
		<?php
			echo (substr(strtok($_SERVER['REQUEST_URI'], '?'), strlen(strtok($_SERVER['REQUEST_URI'], '?')) - 1) === '/') ? '<meta http-equiv="refresh" content="0; url=..">' : '<meta http-equiv="refresh" content="0; url=.">';
		?>
	</head>
</html>