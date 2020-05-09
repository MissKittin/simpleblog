<?php /* import apache settings (if not imported by main index) */ if((!isset($simpleblog)) && (php_sapi_name() != 'cli-server')) include '../../settings.php'; ?>
<?php if(file_exists('disabled.php')) { include $simpleblog['root_php'] . '/lib/prevent-index.php'; exit(); } ?>

<?php $simpleblog_viewPageLang='en'; // custom html lang (optional) ?>
<?php $simpleblog_viewPageTitle='Sample page | ' . $simpleblog['title']; // custom title (optional) ?>

<?php function simpleblog_viewPageCustomheaders() { ?>
	<!-- custom html headers here (optional) -->
<?php } ?>

<?php function simpleblog_viewPageArticles() { ?>
	<!-- page content (required) -->
	<h1 style="text-align: center;">Sample page</h1>
	<div style="text-align: center;">Sample text on this page</div>
<?php } ?>

<?php function simpleblog_viewPageBodyAppend() { ?>
	<!-- content at the end of <body> -->
<?php } ?>

<?php include $simpleblog['root_php'] . '/skins/' . $simpleblog['skin'] . '/views/viewPage.php'; ?>
<?php if(isset($simpleblog['execTime'])) error_log('Simpleblog execution time: ' . (microtime(true) - $simpleblog['execTime']) . 's, max mem used: ' . memory_get_peak_usage() . 'B', 0); ?>