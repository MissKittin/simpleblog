function checkDate(startDay, startMonth, endDay, endMonth)
{
	// example: checkDate('01','02', '25','06')

	var currentDate=new Date().getTime()/1000;
	var thisYear=new Date().getFullYear();

	if(startMonth < endMonth) // in the same year
		var newYear=thisYear;
	else // between new year
	{
		if((currentDate <= new Date(thisYear + '12.31').getTime()) && (new Date().getMonth() > 10))
			var newYear=thisYear+1; // old year
		else
		{
			// new year
			var newYear=thisYear;
			thisYear=thisYear-1;
		}
	}

	var startDate=new Date(thisYear + '.' + startMonth + '.' + startDay).getTime() / 1000;
	var endDate=new Date(newYear + '.' + endMonth + '.' + endDay).getTime() / 1000;

	if((currentDate >= startDate) && (currentDate <= endDate))
		return true;

	return false;
}