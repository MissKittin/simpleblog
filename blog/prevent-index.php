<?php
	if(php_sapi_name() != 'cli-server')
		if(!isset($cms_root_php))
		{
			$search_settings='settings.php';
			$search_prevent_index='prevent-index.php';
			while((!file_exists($search_settings)) || (!file_exists($search_prevent_index)))
			{
				$search_settings='../' . $search_settings;
				$search_prevent_index='../' . $search_prevent_index;
			}
			include $search_settings;
		}
	if($cms_fake_notfound) http_response_code(404);
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo "$page_title"; ?></title>
		<meta charset="utf-8">
		<?php include $cms_root_php . '/htmlheaders.php'; ?>
		<?php
			echo (substr(strtok($_SERVER['REQUEST_URI'], '?'), strlen(strtok($_SERVER['REQUEST_URI'], '?')) - 1) === '/') ? '<meta http-equiv="refresh" content="0; url=..">' : '<meta http-equiv="refresh" content="0; url=.">';
		?>
	</head>
</html>