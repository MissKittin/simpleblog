<?php
	function simpleblog_checkDate($start, $end)
	{
		// usage: simpleblog_checkDate('startDD.MM', 'endDD.MM')
		// example: simpleblog_checkDate('01.02', '25.06')

		$start=explode('.', $start); $end=explode('.', $end);
		$today=date('Y-m-d'); $today=date('Y-m-d', strtotime($today)); // get time and convert it to unix timestamp
		$thisYear=date('Y'); // cache year

		if($start[1]<$end[1])
		{
			// start and end date is in one year
			$startDate=date('Y-m-d', strtotime($start[1].'/'.$start[0].'/'.$thisYear));
			$endDate=date('Y-m-d', strtotime($end[1].'/'.$end[0].'/'.$thisYear));
		}
		else
		{
			// both dates are at the turn of the years
			if(($today<=date('Y-m-d', strtotime('12/31/'.$thisYear))) && (date('m')>11))
				$newYear=$thisYear+1; // now is old year
			else
			{
				// now is new year
				$newYear=$thisYear;
				$thisYear=$thisYear-1;
			}
			$startDate=date('Y-m-d', strtotime($start[1].'/'.$start[0].'/'.$thisYear));
			$endDate=date('Y-m-d', strtotime($end[1].'/'.$end[0].'/'.$newYear));
		}

		if(($today>=$startDate) && ($today<=$endDate))
			return true;

		return false;
	}

	if(simpleblog_checkDate('22.05', '1.06'))
		echo '@import "' . $_GET['root'] . '/skins/' . $skin . '/mycustomization?root=' . $_GET['root'] . '";';

?>
