<?php
	// function that converts bytes to readable unit
	// 05.12.2019

	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/convertBytes.php')
	{
		include 'prevent-index.php'; exit();
	}

	function adminpanel_convertBytes($input)
	{
		$depth=0;
		$value=$input;
		while($value >= 1024)
		{
			$value=$value/1024;
			$depth++;
		}
		switch($depth)
		{
			case 0:
				$unit='B';
				break;
			case 1:
				$unit='kB';
				break;
			case 2:
				$unit='MB';
				break;
			case 3:
				$unit='GB';
				break;
			case 4:
				$unit='TB';
				break;
			default:
				$unit='?B';
				break;
		}
		return round($value, 1) . $unit;
	}
?>