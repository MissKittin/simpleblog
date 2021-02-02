<?php
	// prevent direct
	if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strrpos(strtok($_SERVER['REQUEST_URI'], '?'), '/')) === '/header.php')
	{
		include './prevent-index.php'; exit();
	}
?>
<h2 style="float: left;">Administration</h2>
<style>@media only screen and (max-width: 600px) { #shortTitleButton { display: none; } }</style><div id="shortTitleButton" style="float: left; margin-top: 15px;" class="button"><a href="<?php echo $simpleblog['root_html']; ?>" target="_blank"><?php echo $simpleblog['short_title']; ?></a></div>
<?php if(php_sapi_name() === 'cli-server') echo '<form action="' . $adminpanel['root_html'] . '" method="post">'."\n"; else echo '<form action="' . $adminpanel['root_html'] . '/index.php" method="post">'."\n"; ?>
	<input id="logout_button" class="button" type="submit" name="logout" value="Logout">
</form>
<span style="position: absolute; bottom: 2px; right: 5px;">Simpleblog v2</span>
