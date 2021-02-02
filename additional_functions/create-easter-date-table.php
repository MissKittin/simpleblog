<?php
	// data generator for simpleblog_checkEaster()
	// for php cli use only

	chdir(__DIR__);

	if(isset($_SERVER['REQUEST_URI']))
	{
		include '../../lib/prevent-index.php';
		exit();
	}

	function calculateEaster($thisYear)
	{
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
			++$easter['month'];
		}

		return $easter;
	};

	$thisYear=date('Y');
	for($i=1; $i<=100; ++$i)
	{
		$calculateEasterOutput=calculateEaster($thisYear);
		$outputArray[$thisYear]=array($calculateEasterOutput['day'], $calculateEasterOutput['month']);
		++$thisYear;
	}

	file_put_contents('easter_date_table.php', '<?php include __DIR__ . \'/../../lib/prevent-index.php\'; exit(); ?>'.PHP_EOL.serialize($outputArray));
?>