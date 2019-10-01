<?php
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strlen(strtok($_SERVER['REQUEST_URI'], '?')) - 1) === '/')
	{
		echo '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="0; url=' . substr(strtok($_SERVER['REQUEST_URI'], '?'), 0, -1) . '"></head></html>';
		exit();
	}

	if(!isset($_GET['root']))
	{
		echo '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="0; url=."></head></html>';
		exit();
	}

	include $_SERVER['DOCUMENT_ROOT'] . '/' . $_GET['root'] . 'settings.php';
	header("Content-Type: text/css; X-Content-Type-Options: nosniff;");
	echo '@import "' . $cms_root . 'skins/' . $skin . '/style.css"';
?>