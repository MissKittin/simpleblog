<?php if(php_sapi_name() != 'cli-server') include 'settings.php'; ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo "$page_title"; ?></title>
		<meta charset="utf-8">
		<link rel="shortcut icon" type="image/icon" href="<?php echo "$cms_root"; ?>/favicon.ico">
		<link rel="stylesheet" type="text/css" href="<?php echo "$cms_root"; ?>/style?root=<?php echo "$cms_root"; ?>">
		<?php
			echo (substr(strtok($_SERVER['REQUEST_URI'], '?'), strlen(strtok($_SERVER['REQUEST_URI'], '?')) - 1) === '/') ? '<meta http-equiv="refresh" content="0; url=..">' : '<meta http-equiv="refresh" content="0; url=.">';
		?>
	</head>
</html>