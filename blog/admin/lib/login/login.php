<?php
	// admin panel login library
?>
<?php
	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/login.php')
	{
		include '../prevent-index.php' ;exit();
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
	}
	else
	{
		if(isset($_POST['username']) && isset($_POST['password']))
			if($_POST['username'] === $adminpanel_credentials['login'])
				if($_POST['password'] === $adminpanel_credentials['password'])
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
		include $adminpanel['root_php'] . '/lib/login/login-form.php';
		exit();
	}

	unset($adminpanel_credentials); unset($reload); // clean
?>