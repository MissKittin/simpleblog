<?php
	// CSRF prevention for adminpanel v1.2u1
	// 17,19.02.2020
	// 28.02.2020 token regeneration and disable flag
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

	// disable library - for debugging only!
	$adminpanel_csrf_disableLibrary=false;
	if($adminpanel_csrf_disableLibrary)
	{
		// define empty functions
		$adminpanel_csrf_generateToken=function(){};
		function adminpanel_csrf_checkToken($method){ return true; }
		function adminpanel_csrf_printToken($parameter)
		{
			switch($parameter)
			{
				case 'parameter':
					return 'csrf_parameter';
				break;
				case 'value':
					return 'csrf_value';
				break;
			}
				
		}
		function adminpanel_csrf_injectToken(){ return "\n"; }
	}
	else
	{
		// define
		$adminpanel_csrf_generateToken=function()
		{
			// use one token per session
			//if(!isset($_SESSION['csrf_token']))
			//	$_SESSION['csrf_token']=substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 32);

			// (re)generate token if is not send/set
			if((!adminpanel_csrf_checkToken('get')) && (!adminpanel_csrf_checkToken('post')))
				$_SESSION['csrf_token']=substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 32);
		};
		function adminpanel_csrf_checkToken($method)
		{
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
			return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">' . "\n";
		}
	}
	unset($adminpanel_csrf_disableLibrary); // remove debug flag from memory
?>