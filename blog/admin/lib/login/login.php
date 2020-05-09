<?php
	// admin panel login library
	// bcrypt password and change credentials 24.11.2019
	// session security 02.12.2019
	// sec_csrf.php 19.02.2020
	// sec_bruteforce.php 28.02.2020
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
		unset($search_settings);
	}

	// check if disable.php exists
	if(file_exists($adminpanel['root_php'] . '/disabled.php'))
	{
		include $adminpanel['root_php'] . '/lib/prevent-index.php'; exit();
	}

	// check php version
	if(!function_exists('password_verify'))
	{
		echo 'PHP version is too old'; exit();
	}

	// import sec_csrf.php library
	include $adminpanel['root_php'] . '/lib/sec_csrf.php';

	// import sec_bruteforce.php library
	include $adminpanel['root_php'] . '/lib/sec_bruteforce.php';
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
	session_name('SIMPLEBLOGSESSID');
	session_start();
	$adminpanel_csrf_generateToken(); unset($adminpanel_csrf_generateToken); // sec_csrf.php
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

		// session expired check
		if(strtotime('now') > $_SESSION['expired'])
		{
			$_SESSION['logged']=false;
			session_destroy();
			session_regenerate_id(false); // reset session
			$reload();
			exit();
		}
		$_SESSION['expired']=strtotime('now')+86400; // renew expired time, 24h

		// detect cookie attack
		if(($_SESSION['logged_ip'] != $_SERVER['REMOTE_ADDR']) || ($_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT']))
		{
			$_SESSION['logged']=false;
			session_destroy();
			session_regenerate_id(false); // reset session
			$reload();
			exit();
		}

		// password change prompt
		if((isset($_POST['newusername'])) && (isset($_POST['newpassword'])) && (adminpanel_csrf_checkToken('post')))
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
		if((isset($_POST['username'])) && (isset($_POST['password'])) && (adminpanel_csrf_checkToken('post')) && (!$adminpanel_loginBan_checkDB())) // ++ sec_csrf.php, ++ sec_bruteforce.php
		{
			if($_POST['username'] === $adminpanel_credentials['login'])
			{
				if(password_verify($_POST['password'], $adminpanel_credentials['password']))
				{
					$_SESSION['logged_user']=$adminpanel_credentials['login'];
					$_SESSION['logged']=true; // success!!!
					$_SESSION['logged_ip']=$_SERVER['REMOTE_ADDR']; // log for cookie attack detection
					$_SESSION['user_agent']=$_SERVER['HTTP_USER_AGENT']; // log for cookie attack detection
					$_SESSION['expired']=strtotime('now')+86400; // 24h
					$adminpanel_loginBan_saveDB(false); // ++ sec_bruteforce.php
					$reload();
					exit();
				}
				else
					$adminpanel_loginBan_saveDB(true); // ++ sec_bruteforce.php
			}
			else
				$adminpanel_loginBan_saveDB(true); // ++ sec_bruteforce.php
		}
	}

	unset($adminpanel_credentials); unset($reload); unset($changeCredentials); // clean

	// ++ sec_bruteforce.php - remove
	$adminpanel_loginBan_removeFromMemory=true;
	include $adminpanel['root_php'] . '/lib/sec_bruteforce.php';
	unset($adminpanel_loginBan_removeFromMemory);
	unset($adminpanel_loginBan);

	//login form
	if(!$_SESSION['logged'])
	{
		include $adminpanel['root_php'] . '/lib/login/' . $adminpanel['login_form'] . '/login-form.php';
		exit();
	}
?>