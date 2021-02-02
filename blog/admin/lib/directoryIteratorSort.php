<?php
	// function that sorts the directoryIterator output

	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/directoryIteratorSort.php')
	{
		include './prevent-index.php'; exit();
	}

	function adminpanel_directoryIteratorSort($path)
	{
		function compareByName($a, $b)
		{
			return strcmp($a, $b);
		}

		$returnArray=array();
		$arrayIndicator=0;

		foreach(new directoryIterator($path) as $file)
			$returnArray[$file->getFilename()]=array('name' => $file->getFilename(), 'size' => $file->getSize(), 'ctime' => $file->getCTime());

		uksort($returnArray, 'compareByName');
		return $returnArray;
	}
?>