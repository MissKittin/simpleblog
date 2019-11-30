<?php
	// simpleblog cron (execute-at-the-enter)
	// 09.11.2019

	/* example usage: publish scheduled article
	   make in cron directory new file: on_examplename.php. Put in file:
		<?php
			if($cron['checkDate']('09.11.2019'))
				if($cron['article']('public', '000125')) $cron['disable']();
		?>
	   where date is in DD.MM.YYYY format
	   task with on_ prefix is enabled, with off_ - disabled
	   remember to refresh opcache after adding a new task
	*/

	// deny direct access
	if(php_sapi_name() === 'cli-server')
	{
		if(strtok($_SERVER['REQUEST_URI'], '?') === $simpleblog['root_html'] . '/lib/cron.php')
		{
			include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit();
		}
	}
	else
	{
		if(!isset($simpleblog))
		{
			include 'prevent-index.php'; exit();
		}
	}

	// settings
	$cron['enabled']=true;
	$cron['location']['html']=$simpleblog['root_html'] . '/cron'; // for html
	$cron['location']['php']=$simpleblog['root_php'] . '/cron'; // for php scripts
	$cron['location']['bin']=$simpleblog['root_html'] . '/lib/cron.php'; // this script
	$cron['location']['articles']=$simpleblog['root_php'] . '/articles'; // location of articles

	// check if cron is enabled
	if($cron['enabled'])
	{
		// functions
		$cron['checkDate']=function($dateInput) // $cron['checkDate']('DD.MM.YYYY')
		{
			$date['input']=explode('.', $dateInput); // convert string to array
			$date['today']=date('Y-m-d'); $date['today']=strtotime($date['today']); // get now date and convert it to unix timestamp
			$date['input']=strtotime($date['input'][1].'/'.$date['input'][0].'/'.$date['input'][2]); // convert input date to unix timestamp
			if($date['input'] <= $date['today'])
				return true;
			return false;
		};
		$cron['article']=function($action, $id) // $cron['article']('public' || 'hide', $articleID)
		{
			global $simpleblog;
			switch($action)
			{
				case 'public':
					if(!file_exists($cron['location']['articles'] . '/private_' . $id . '.php')) return false;
					if(!rename($cron['location']['articles'] . '/private_' . $id . '.php', $cron['location']['articles'] . '/public_' . $id . '.php')) return false;
					return true;
				break;
				case 'hide':
					if(!file_exists($cron['location']['articles'] . '/public_' . $id . '.php')) return false;
					if(!rename($cron['location']['articles'] . '/public_' . $id . '.php', $cron['location']['articles'] . '/private_' . $id . '.php')) return false;
					return true;
				break;
			}
		};
		$cron['disable']=function() // disable $this task
		{
			global $cron;
			rename($cron['location']['php'] . '/' . $cron['task'], $cron['location']['php'] . '/off_' . substr($cron['task'], 3));
		};

		// main
		$cron['tasks']=scandir($cron['location']['php']);
		foreach($cron['tasks'] as $cron['task'])
			if(($cron['task'] != '.') && ($cron['task'] != '..') && (substr($cron['task'], 0, 3) === 'on_')) // include tasks only with on_ prefix
				include $cron['location']['php'] . '/' . $cron['task'];
	}

	unset($cron); // clean
?>