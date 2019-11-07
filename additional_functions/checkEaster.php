<?php
	// simpleblog_checkDate() reqd

	function simpleblog_checkEaster($easterEndDays)
	{
		// simpleblog_checkDate extension for easter
		// usage: simpleblog_checkEaster(daysToEnd)
		// example: simpleblog_checkEaster(49)

		$calculateEaster=function()
		{
			$thisYear=date('Y');
			if(($thisYear >= 1900) && ($thisYear <= 2099))
			{
				$tabA=24;
				$tabB=5;
			}
			else if(($thisYear >= 2100) && ($thisYear <= 2199))
			{
				$tabA=24;
				$tabB=6;
			}
			else if(($thisYear >= 2200) && ($thisYear <= 2299))
			{
				$tabA=25;
				$tabB=0;
			}
			else if(($thisYear >= 2300) && ($thisYear <= 2399))
			{
				$tabA=26;
				$tabB=1;
			}
			else if(($thisYear >= 2400) && ($thisYear <= 2499))
			{
				$tabA=25;
				$tabB=1;
			}
			else
				return false;

			$a=$thisYear % 19;
			$b=$thisYear % 4;
			$c=$thisYear % 7;
			$d=($a * 19 + 24) % 30;
			$e=((2 * $b)+(4 * $c)+(6 * $d)+5) % 7;
			$easter['day']=22 + $d + $e; $easter['month']=3;

			while($easter['day'] > 31)
			{
				$easter['day']=$easter['day'] - 31;
				$easter['month']++;
			}

			return $easter;
		};

		$easterStart=$calculateEaster(); if($easterStart == false) return false;
		$easterEnd['day']=$easterStart['day']+$easterEndDays; $easterEnd['month']=$easterStart['month'];

		while($easterEnd['day'] > 30)
		{
			if($easterEnd['month']%2 == 0)
				$easterEnd['day']=$easterEnd['day']-30;
			else
				$easterEnd['day']=$easterEnd['day']-31;
			$easterEnd['month']++;
		}

		return simpleblog_checkDate($easterStart['day'].'.'.$easterStart['month'], $easterEnd['day'].'.'.$easterEnd['month']);
	}

	if(simpleblog_checkEaster(49))
		echo '@import "' . $_GET['root'] . '/skins/' . $skin . '/myeastercustomization?root=' . $_GET['root'] . '";';
?>
