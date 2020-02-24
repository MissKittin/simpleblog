<?php
	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/login-newpassword-form.php')
	{
		include '../../prevent-index.php'; exit();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="<?php echo $adminpanel['root_html']; ?>/skins/<?php echo $adminpanel['skin']; ?>/login">
	</head>
	<body>
		<div id="login_box">
			<div id="content">
				<?php if(php_sapi_name() === 'cli-server') echo '<form action="' . $adminpanel['root_html'] . '" method="post">'."\n"; else echo '<form action="' . $adminpanel['root_html'] . '/index.php" method="post">'."\n"; ?>
					<div class="input_field">
						<label for="newusername">New login</label>
						<input type="text" name="newusername">
					</div>
					<div class="input_field">
						<label for="newpassword">New password</label>
						<input type="password" name="newpassword">
					</div>
					<div id="input_buttons">
						<input type="submit" value="Change" class="button">
					</div>
					<?php echo adminpanel_csrf_injectToken(); ?>
				</form>
			</div>
		</div>
	</body>
</html>