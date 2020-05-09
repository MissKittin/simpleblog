<?php
	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/fileSearchRecursive.php')
	{
		include 'prevent-index.php'; exit();
	}

	function adminpanel_fileSearchRecursive($dir, $prefix)
	{
		$returnArray=array();
		foreach(scandir($dir) as $file)
			if(($file != '.') && ($file != '..'))
			{
				if(is_dir($dir . '/' . $file))
					foreach(adminpanel_fileSearchRecursive($dir . '/' . $file, $file . '/') as $addToArray)
						array_push($returnArray, $prefix . $addToArray);
				else
					array_push($returnArray, $prefix . $file);
			}
		return $returnArray;
	}
?>