<?php
	// Bruteforce attack prevention library for adminpanel v1.2u1.1
	// 21,28.02.2020
	// included by login subsystem (lib/login/login.php)

	// Achtung! Place database in a directory with access-denied eg. tmp
	// and make sure the directory exists

	// Settings:
	// $adminpanel_loginBan['enabled'] [bool] - enable or disable library
	// $adminpanel_loginBan['DBFile'] [string] - path to database
	// $adminpanel_loginBan['DBFile_dynamic'] [bool] - remove database file if is empty
	// $adminpanel_loginBan['attempts'] [int] - how many attempts allowed
	// $adminpanel_loginBan['time'] [int] - ban duration in seconds

	// Functions:
	// $adminpanel_loginBan_checkDB() - check if IP is banned
	// $adminpanel_loginBan_saveDB(false) - remove IP from database if logged successfully
	// $adminpanel_loginBan_saveDB(true) - add IP to database or increment login attempts

	// Cleaning:
	// $adminpanel_loginBan_removeFromMemory=true;
	// ((reimport this library))
	// unset($adminpanel_loginBan_removeFromMemory);
	// unset($adminpanel_loginBan);

	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/sec_bruteforce.php')
	{
		include './prevent-index.php'; exit();
	}

	// setup or clean?
	if(isset($adminpanel_loginBan_removeFromMemory))
	{
		unset($adminpanel_loginBan_readDB);
		unset($adminpanel_loginBan_checkDB);
		unset($adminpanel_loginBan_saveDB);
		unset($adminpanel_loginBan_DB);
	}
	else
	{
		if($adminpanel_loginBan['enabled']) // enable or disable library
		{
			$adminpanel_loginBan_readDB=function()
			{
				global $adminpanel_loginBan;

				if(!file_exists($adminpanel_loginBan['DBFile']))
					return array();
				return unserialize(file_get_contents($adminpanel_loginBan['DBFile']));
			};
			$adminpanel_loginBan_checkDB=function()
			{
				global $adminpanel_loginBan_DB; 
				global $adminpanel_loginBan_saveDB;

				if(isset($adminpanel_loginBan_DB[$_SERVER['REMOTE_ADDR']]['unban']))
				{
					if($adminpanel_loginBan_DB[$_SERVER['REMOTE_ADDR']]['unban'] > time())
						return true;
					else
						$adminpanel_loginBan_saveDB(false);
				}
				return false;
			};
			$adminpanel_loginBan_saveDB=function($ban)
			{
				global $adminpanel_loginBan;
				global $adminpanel_loginBan_DB;

				if($ban)
				{
					if(!isset($adminpanel_loginBan_DB[$_SERVER['REMOTE_ADDR']]))
						$adminpanel_loginBan_DB[$_SERVER['REMOTE_ADDR']]['attempts']=1;
					else
					{
						$adminpanel_loginBan_DB[$_SERVER['REMOTE_ADDR']]['attempts']+=1;
						if($adminpanel_loginBan_DB[$_SERVER['REMOTE_ADDR']]['attempts'] >= $adminpanel_loginBan['attempts'])
							$adminpanel_loginBan_DB[$_SERVER['REMOTE_ADDR']]['unban']=time()+$adminpanel_loginBan['time'];
					}
					file_put_contents($adminpanel_loginBan['DBFile'], serialize($adminpanel_loginBan_DB));
				}
				else
				{
					if((isset($adminpanel_loginBan_DB[$_SERVER['REMOTE_ADDR']])) && (file_exists($adminpanel_loginBan['DBFile'])))
					{
						unset($adminpanel_loginBan_DB[$_SERVER['REMOTE_ADDR']]);
						if((empty($adminpanel_loginBan_DB)) && ($adminpanel_loginBan['DBFile_dynamic']))
							unlink($adminpanel_loginBan['DBFile']);
						else
							file_put_contents($adminpanel_loginBan['DBFile'], serialize($adminpanel_loginBan_DB));
					}
				}
			};
			$adminpanel_loginBan_DB=$adminpanel_loginBan_readDB(); // store unserialized database in memory
		}
		else // create empty functions
		{
			$adminpanel_loginBan_checkDB=function(){ return false; };
			$adminpanel_loginBan_saveDB=function($ban){};
		}
	}
?>