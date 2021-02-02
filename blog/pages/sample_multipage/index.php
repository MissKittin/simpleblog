<?php /* import settings (if not imported by main index) */ if(!isset($simpleblog)) include '../../settings.php'; ?>
<?php if(file_exists('disabled.php')) { include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit(); } ?>

<?php
	// Pages - edit the variable value below
	$pages=3;

	// set page number
	$page=1;
	if(isset($_GET['page']))
	{
		if((is_numeric($_GET['page'])) && ($_GET['page'] <= $pages))
		{
			$page=$_GET['page'];
			settype($page, 'integer');
		}
	}
?>

<?php function simpleblog_viewPageArticles() { ?>
	<?php global $page; ?>
	<?php if($page === 1) { ?>
		<h1 style="text-align: center;">This is the first page</h1>
		<div style="text-align: center;">Sample text</div>
	<?php } ?>

	<?php if($page === 2) { ?>
		<h1 style="text-align: center;">This is the second page</h1>
		<div style="text-align: center;">Click page 3</div>
	<?php } ?>

	<?php if($page === 3) { ?>
		<h1 style="text-align: center;">This is the last page</h1>
		<h3 style="text-align: right;">The end</h3>
	<?php } ?>
<?php } ?>

<?php function simpleblog_viewPagePages() { ?>
	<?php global $pages; global $page; ?>
	<?php
		for($i=1; $i<=$pages; $i++)
			if($i === $page)
				echo '<div class="page" id="current_page"><a href="?page='. $i .'">' . $i . '</a></div>';
			else
				echo '<div class="page"><a href="?page='. $i .'">' . $i . '</a></div>';
	?>
<?php } ?>

<?php include $simpleblog['root_php'] . '/skins/' . $simpleblog['skin'] . '/views/viewPage.php'; ?>
<?php if(isset($simpleblog['execTime'])) error_log('Simpleblog execution time: ' . (microtime(true) - $simpleblog['execTime']) . 's, max mem used: ' . memory_get_peak_usage() . 'B', 0); ?>