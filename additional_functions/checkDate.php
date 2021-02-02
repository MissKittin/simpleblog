<?php
	function simpleblog_checkDate($startDay, $startMonth, $endDay, $endMonth)
	{
		// example: simpleblog_checkDate('01','02', '25','06')

		$currentDate=date('Y-m-d', strtotime(date('Y-m-d'))); // get time and convert it to unix timestamp
		$thisYear=date('Y'); // cache year

		if($startMonth < $endMonth) // in the same year
			$newYear=$thisYear;
		else // between new year
		{
			if(($currentDate <= date('Y-m-d', strtotime('12/31/'.$thisYear))) && (date('m') > 11))
				$newYear=++$thisYear; // old year
			else
			{
				// new year
				$newYear=$thisYear;
				$thisYear=--$thisYear;
			}
		}

		$startDate=date('Y-m-d', strtotime($startMonth.'/'.$startDay.'/'.$thisYear));
		$endDate=date('Y-m-d', strtotime($endMonth.'/'.$endDay.'/'.$newYear));

		if(($currentDate >= $startDate) && ($currentDate <= $endDate))
			return true;

		return false;
	}

	if(simpleblog_checkDate('22.05', '1.06'))
		include './mycustomization/index.php'; // echo '@import "' . $_GET['root'] . '/skins/' . $skin . '/mycustomization?root=' . $_GET['root'] . '";';

?>
