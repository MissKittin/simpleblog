<?php
	// admin panel login library
	// bcrypt password and change credentials 24.11.2019
?>
<?php
	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/login.php')
	{
		include '../prevent-index.php'; exit();
	}
?>
<?php
	// search settings
	if(!isset($adminpanel))
	{
		$search_settings='admin-settings.php';
		while(!file_exists($search_settings))
			$search_settings='../' . $search_settings;
		include $search_settings;
	}

	// check if disable.php exists
	if(file_exists($adminpanel['root_php'] . '/disabled.php'))
	{
		include $adminpanel['root_php'] . '/lib/prevent-index.php'; exit();
	}
?>
<?php
	//functions
	$reload=function()
	{
		global $adminpanel;
		echo '
			<!DOCTYPE html>
			<html>
				<head>
					<title>Logged</title>
					<meta name="viewport" content="width=device-width, initial-scale=1">
					<link rel="stylesheet" type="text/css" href="' . $adminpanel['root_html'] . '/skins/' . $adminpanel['skin'] . '/login">
					<meta http-equiv="refresh" content="0">
				</head>
				<body>
					<h1 id="loading">Loading...</h1>
				</body>
			</html>
		';
	};
	$changeCredentials=function($username, $password)
	{
		global $adminpanel;
		global $adminpanel_credentials;
		file_put_contents($adminpanel['root_php'] . '/admin-settings.php', str_replace('$adminpanel_credentials[\'login\']=\'' . $adminpanel_credentials['login'] . '\'', '$adminpanel_credentials[\'login\']=\'' . $username . '\'', file_get_contents($adminpanel['root_php'] . '/admin-settings.php')));
		file_put_contents($adminpanel['root_php'] . '/admin-settings.php', str_replace('$adminpanel_credentials[\'password\']=\'' . $adminpanel_credentials['password'] . '\'', '$adminpanel_credentials[\'password\']=\'' . password_hash($password, PASSWORD_BCRYPT) . '\'', file_get_contents($adminpanel['root_php'] . '/admin-settings.php')));
		unlink($adminpanel['root_php'] . '/passwordChangeRequired.php');
		if(function_exists('opcache_get_status')) if(opcache_get_status()) opcache_reset();
	};

	//header
	session_name('SESSID');
	session_start();
	if(!isset($_SESSION['logged']))
		$_SESSION['logged']=false;

	//logout
	if(isset($_POST['logout']))
		if(($_POST['logout'] === 'logout') || ($_POST['logout'] === 'Logout'))
		{
			$_SESSION['logged']=false;
			session_destroy();
			$reload();
			exit();
		}

	//login
	if($_SESSION['logged'])
	{
		if(!isset($session_regenerate)) // disable session regenerating (set it in script before include)
			session_regenerate_id(true); // cookie attack prevention

		// password change prompt
		if((isset($_POST['newusername'])) && (isset($_POST['newpassword'])))
		{
			$changeCredentials($_POST['newusername'], $_POST['newpassword']);
			$reload();
			exit();
		}
		else if(file_exists($adminpanel['root_php'] . '/passwordChangeRequired.php'))
		{
			include $adminpanel['root_php'] . '/lib/login/' . $adminpanel['login_form'] . '/login-newpassword-form.php';
			exit();
		}
	}
	else
	{
		if(isset($_POST['username']) && isset($_POST['password']))
			if($_POST['username'] === $adminpanel_credentials['login'])
				if(password_verify($_POST['password'], $adminpanel_credentials['password']))
				{
					$_SESSION['logged_user']=$adminpanel_credentials['login'];
					$_SESSION['logged']=true; // success!!!
					$_SESSION['logged_ip']=$_SERVER['REMOTE_ADDR'];
					$reload();
					exit();
				}
	}

	//login form
	if(!$_SESSION['logged'])
	{
		include $adminpanel['root_php'] . '/lib/login/' . $adminpanel['login_form'] . '/login-form.php';
		exit();
	}

	unset($adminpanel_credentials); unset($reload); unset($changeCredentials); // clean
?>