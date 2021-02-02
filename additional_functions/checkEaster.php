<?php
	// simpleblog_checkDate() reqd

	function simpleblog_checkEaster($easterDays)
	{
		// simpleblog_checkDate extension for easter
		// example usage: simpleblog_checkEaster(49)
		// run `php create-easter-date-table.php` before use

		$thisYear=date('Y'); // cache
		if(!@$dateTable=file('easter_date_table.php')) return false; // abort if no data available
		$dateTable=unserialize($dateTable[1]); // read data

		// calculate end date
		$easterEndDay=$dateTable[$thisYear][0]+$easterDays;
		$easterEndMonth=$dateTable[$thisYear][1];

		// correct end date
		while($easterEndDay > 30)
		{
			if($easterEndMonth%2 === 0)
				$easterEndDay=$easterEndDay-30;
			else
				$easterEndDay=$easterEndDay-31;

			++$easterEndMonth;
		}

		// call simpleblog_checkDate()
		return simpleblog_checkDate($dateTable[$thisYear][0], $dateTable[$thisYear][1], $easterEndDay, $easterEndMonth);
	}

	if(simpleblog_checkEaster(49))
		include './myeastercustomization/index.php'; // echo '@import "' . $_GET['root'] . '/skins/' . $skin . '/myeastercustomization?root=' . $_GET['root'] . '";';
?>
