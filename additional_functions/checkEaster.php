<?php
	// simpleblog_checkDate() reqd

	function simpleblog_checkEaster($easterEndDays)
	{
		// simpleblog_checkDate extension for easter
		// usage: simpleblog_checkEaster(daysToEnd)
		// example: simpleblog_checkEaster(49)

		// cache: put empty 'generateEasterCache' file in this directory

		$calculateEaster=function($overrideThisYear=false)
		{
			// calculate for cache or without cache
			if($overrideThisYear !== false)
				$thisYear=$overrideThisYear;
			else
				$thisYear=date('Y'); // cache year

			// constants
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

			// Gauss' algorithm
			$a=$thisYear % 19;
			$b=$thisYear % 4;
			$c=$thisYear % 7;
			$d=($a * 19 + 24) % 30;
			$e=((2 * $b)+(4 * $c)+(6 * $d)+5) % 7;
			$easter['day']=22 + $d + $e; $easter['month']=3;

			// correct start date
			while($easter['day'] > 31)
			{
				$easter['day']=$easter['day'] - 31;
				$easter['month']++;
			}

			return $easter;
		};

		// generate/read cache (rewrite $calculateEaster())
		if(file_exists('generateEasterCache'))
		{
			$cache_thisYear=date('Y');
			for($i=1; $i<=100; ++$i)
			{
				$cache_output=$calculateEaster($cache_thisYear); // run $calculateEaster() once per loop
				$cache_outputArray[$cache_thisYear]=array($cache_output['day'], $cache_output['month']);
				++$cache_thisYear;
			}
			file_put_contents('easterCache.php', serialize($cache_outputArray));
			unlink('generateEasterCache');

			$calculateEaster=function()
			{
				$inputArray=unserialize(file_get_contents('easterCache.php'));
				$thisYear=date('Y');
				return array('day'=>$inputArray[$thisYear][0], 'month'=>$inputArray[$thisYear][1]);
			};
		}
		elseif(file_exists('easterCache.php'))
			$calculateEaster=function()
			{
				$inputArray=unserialize(file_get_contents('easterCache.php'));
				$thisYear=date('Y');
				return array('day'=>$inputArray[$thisYear][0], 'month'=>$inputArray[$thisYear][1]);
			};

		// calculate end date
		$easterStart=$calculateEaster(); if($easterStart == false) { echo '/* no constants defined */' . "\n"; return false; }
		$easterEnd['day']=$easterStart['day']+$easterEndDays; $easterEnd['month']=$easterStart['month'];

		// correct end date
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
