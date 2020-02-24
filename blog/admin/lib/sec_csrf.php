<?php
	// CSRF prevention for adminpanel v1.2u1
	// 17,19.02.2020
	// included by login subsystem (lib/login/login.php)

	// adminpanel_csrf_generateToken() for login.php eg:
	//	$adminpanel_csrf_generateToken(); unset($adminpanel_csrf_generateToken);
	// adminpanel_csrf_checkToken('get'|'post') for php code eg:
	//	if(adminpanel_csrf_checkToken('post')) echo 'do_action';
	// adminpanel_csrf_printToken('parameter'|'value') for custom GET links eg:
	//	'<a href="action.php?' . adminpanel_csrf_printToken('parameter') . '=' . adminpanel_csrf_printToken('value') . '">Delete</a>'
	// adminpanel_csrf_injectToken() for html forms eg:
	//	echo adminpanel_csrf_injectToken();

	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/sec_csrf.php')
	{
		include 'prevent-index.php'; exit();
	}

	// define
	$adminpanel_csrf_generateToken=function()
	{
		global $_SESSION;
		if(!isset($_SESSION['csrf_token']))
			$_SESSION['csrf_token']=substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 32);
	};
	function adminpanel_csrf_checkToken($method)
	{
		global $_SESSION;
		global $_GET; global $_POST;
		if(isset($_SESSION['csrf_token']))
			switch($method)
			{
				case 'get':
					if(isset($_GET['csrf_token']))
						if($_SESSION['csrf_token'] === $_GET['csrf_token'])
							return true;
				break;
				case 'post':
					if(isset($_POST['csrf_token']))
						if($_SESSION['csrf_token'] === $_POST['csrf_token'])
							return true;
				break;
			}
		return false;
	}
	function adminpanel_csrf_printToken($parameter)
	{
		global $_SESSION;
		switch($parameter)
		{
			case 'parameter':
				return 'csrf_token';
			break;
			case 'value':
				return $_SESSION['csrf_token'];
			break;
		}
		return false;
	}
	function adminpanel_csrf_injectToken()
	{
		global $_SESSION;
		return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">' . "\n";
	}
?>