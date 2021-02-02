function checkEaster(easterDays)
{
	// checkDate extension for easter
	// example usage: checkEaster(49)

	var calculateEaster=function()
	{
		var thisYear=new Date().getFullYear(); // cache

		// constants
		if((thisYear >= 1900) && (thisYear <= 2099))
		{
			var tabA=24;
			var tabB=5;
		}
		else if((thisYear >= 2100) && (thisYear <= 2199))
		{
			var tabA=24;
			var tabB=6;
		}
		else if((thisYear >= 2200) && (thisYear <= 2299))
		{
			var tabA=25;
			var tabB=0;
		}
		else if((thisYear >= 2300) && (thisYear <= 2399))
		{
			var tabA=26;
			var tabB=1;
		}
		else if((thisYear >= 2400) && (thisYear <= 2499))
		{
			var tabA=25;
			var tabB=1;
		}
		else
			return false;

		// Gauss' algorithm
		var a=thisYear % 19;
		var b=thisYear % 4;
		var c=thisYear % 7;
		var d=(a * 19 + 24) % 30;
		var e=((2 * b)+(4 * c)+(6 * d)+5) % 7;
		var easterDay=22 + d + e; var easterMonth=3;

		// correct start date
		while(easterDay > 31)
		{
			easterDay=easterDay - 31;
			easterMonth++;
		}

		var easter=[easterDay, easterMonth];
		return easter;
	};

	// calculate end date
	var easterStart=calculateEaster(); if(easterStart == false) return false;
	var easterEndDay=easterStart[0]+easterDays; var easterEndMonth=easterStart[1];

	// correct end date
	while(easterEndDay > 30)
	{
		if(easterEndMonth%2 == 0)
			easterEndDay=easterEndDay-30;
		else
			easterEndDay=easterEndDay-31;
		easterEndMonth++;
	}

	return checkDate(easterStart[0],easterStart[1], easterEndDay, easterEndMonth);
}
