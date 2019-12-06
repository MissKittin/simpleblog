# Table of contents
* [First steps after clone](#first-steps-after-clone)
* [root_html vs root_php](#simpleblogroot_html-vs-simpleblogroot_php)
* [How to write articles](#how-to-write-articles)
* [How to write articles with a lot of resources](#how-to-write-articles-with-a-lot-of-resources)
* [How to write pages](#how-to-write-pages)
* [Editing header.php, footer.php and headlinks.php](#editing-headerphp-footerphp-and-headlinksphp)
* [How to create skins](#how-to-create-skins)
* [How to allow javascript](#how-to-allow-javascript)
* [Articles addressing scope](#articles-addressing-scope)
* [Supported HTTP servers](#supported-http-servers)
* [How to upgrade](#how-to-upgrade)
* [How it works](#how-it-works)
	* [Core](#core)
	* [Frontend](#frontend)
	* [Settings](#settings)
	* [Administration](#administration)
	* [Modules](#modules)
		* [admin panel](#admin-panel-optional)
		* [cron](#cron-optional)
		* [maintenance break pattern](#maintenance-break-pattern-optional)
		* [pages](#pages-optional-enabled-by-default)
		* [prevent-index.php](#prevent-indexphp-required)
		* [temporary files](#temporary-files-not-installed-by-default)
* [Why?](#why)
<br><br>

# First steps after clone
1. you can rename `blog` to another name
2. enter into `blog`
3. run setup.sh or setup-links.sh (setup.bat or setup-links.bat on windows)\*
4. edit files indicated by the setup script (if you changed the directory name, update `$simpleblog['root_html']` variable)
5. copy blog/skins/default and create your own.
6. upload `blog` to server
<br><br>\*setup is based on copy, setup-links is based on ln/mklink
<br><br>

# $simpleblog['root_html'] vs $simpleblog['root_php']
In normal way `$simpleblog['root_php'] = $_SERVER['DOCUMENT_ROOT'] . $simpleblog['root_html']`, and it's ok: eg path for server is `/var/www/blog` and for browser is `/blog`.<br>
But if you use http proxy script, path for browser is `/proxy/blog` and it's not `/var/www/proxy/blog`. In this case you have to change: `$simpleblog['root_php']=$_SERVER['DOCUMENT_ROOT'] . '/blog'` and `$simpleblog['root_html']='/proxy/blog'`. Problem solved.
<br><br>

# How to write articles
Copy `public_000001.php` to `public_000002.php`, open it and change variables content.<br>
`$art_date` is in DD.MM.YYYY format.<br>
If you want style article uncomment `$art_style['something']`, where
* `$art_style['article']='content';` adds inline style to `<div class="article" style="content">`
* `$art_style['tags']='content';` adds inline style to `<div class="art-tags" style="content">`
* `$art_style['taglink']='content';` adds inline style to `<div class="art-tags"><a style="content">#tag</a></div>`
* `$art_style['date']='content';` adds inline style to `<div class="art-date" style="content">`
* `$art_style['title']='content';` adds inline style to `<div class="art-title" style="content">`
* `$art_style['title-header']=false;` disables `<h2>` tag in `<div class="art-title">`
<br><br>

# How to write articles with a lot of resources
Create new page, divide the article into parts, add switches on the page.<br>
Create standalone article, put in this article a little bit of full article and add link to page with full article.<br>
See https://github.com/MissKittin/simpleblog/tree/master/blog/pages/sample_multipage<br>
Problem solved!
<br><br>

# How to write pages
Copy `samplepage` to `your_page_link`, upload images, styles, fonts etc to this dir and edit `index.php`.
<br><br>

# Editing header.php, footer.php and headlinks.php
Don't touch first `<?php` block of code. Write content below `?>`.
<br><br>

# How to create skins
Copy `skins/default` to `skins/your_theme_name`. `index.php` is main file (don't touch first `<?php` block of code, write content below `?>`). You can add more css by creating `skins/your_theme_name/my_addon/index.php` and linking it by css `@import` rule or include directly by PHP. You can use my functions: https://github.com/MissKittin/simpleblog/tree/master/additional_functions
<br>
To create an installation package, just zip the directory with your theme.<br>

Common CSS classes and ids in cms:
* #header -> header.php content
* #headlinks -> headlinks.php content
	* .headlink -> link class
* #articles -> page content
* #footer -> footer.php content
<br>

Main page:
* #articles
	* .article -> article box, rendered by `simpleblog_engineCore()`
		* .art-tags -> box with tags, links inside (if taglinks enabled)
		* .art-date -> box with date, links inside (if datelinks enabled)
		* .art-title -> box with title, links and placeholder inside (if postlinks enabled)
<br>

Tag (list):
* #articles
	* #taglinks -> list with published tags, rendered by `simpleblog_engineTag()`
		* .taglink -> link with tag, rendered by `simpleblog_engineTag()`
-------------------------------------
Tag (articles):
* #articles
	* .article -> article box, rendered by `simpleblog_engineCore()`
		* .art-tags -> box with tags, links inside (if taglinks enabled)
		* .art-date -> box with date, links inside (if datelinks enabled)
		* .art-title -> box with title, links and placeholder inside (if postlinks enabled)
<br>

.art-title link placeholder is invisible link, that keeps postlink functionality if title is empty. It's styled in skin file.<br>
Pages uses commons ids and classes.
<br><br>

# How to allow javascript
Change https://github.com/MissKittin/simpleblog/blob/master/blog/lib/htmlheaders.php#L3 to<br>
`<meta http-equiv="Content-Security-Policy" content="script-src 'self';">`<br>
This prevent inline script executing, but allow js files from your website.<br>
**Achtung!** Allow inline styles or you breaks $art_style
<br><br>

# Articles addressing scope
Articles are addressed from `000001` to `999999`. You can increase scope by adding zeros at the begin of number, eg `0000999999`<br>
**Achtung!** You must add zeros in all articles!<br>
Automation: edit `$simpleblog_path`, put this file on your server, and run it in browser.<br>
**Remember to delete this file after the operation!**
```
<?php
	// path
	$simpleblog_path='/path/to/blog'; // without $_SERVER['DOCUMENT_ROOT'] path
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Grow Scope</title>
		<meta charset="utf-8">
	</head>
	<body>
	<?php
		if(isset($_POST['howManyZeros']))
		{
			$prefix='';

			for($i=0; $i<$_POST['howManyZeros']; $i++)
				$prefix=$prefix.'0';

			foreach(scandir($_SERVER['DOCUMENT_ROOT'] . $simpleblog_path . '/articles') as $file)
				if(($file != '.') && ($file != '..'))
				{
					if(substr($file, 0, 6) === 'public')
					{
						rename($_SERVER['DOCUMENT_ROOT'] . $simpleblog_path . '/articles/' . $file, $_SERVER['DOCUMENT_ROOT'] . $simpleblog_path . '/articles/public_' . $prefix . substr($file, strlen('public_')));
						echo '<h2>' . $file . ' -&gt; ' . 'public_' . $prefix . substr($file, strlen('public_')) . '</h2>';
					}
					if(substr($file, 0, 7) === 'private')
					{
						rename($_SERVER['DOCUMENT_ROOT'] . $simpleblog_path . '/articles/' . $file, $_SERVER['DOCUMENT_ROOT'] . $simpleblog_path . '/articles/private_' . $prefix . substr($file, strlen('private_')));
						echo '<h2>' . $file . ' -&gt; ' . 'private_' . $prefix . substr($file, strlen('private_')) . '</h2>';
					}
				}

			echo '<h1>Done!</h1>';
		}
		else
		{ ?>
			<h1>Grow Simpleblog articles scope</h1>
			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
				<input type="number" name="howManyZeros" required>
				<input type="submit" value="Jump into large rabbit hole">
			</form>
		<?php }
	?>
	</body>
</html>
```
<br><br>

# Supported HTTP servers
PHP built-in server and Apache. If you want run the Simpleblog on other server, I recommend configure it for Apache.
<br><br>

# How to upgrade
1. Merge `favicon`, `footer.php`, `header.php`, `headlinks.php`, `htmlheaders.php` content to new files in `lib`
2. Merge `settings.php` or `router.php` settings, uncomment backward compatibility options
3. Move maintenance break pattern from old `maintenance-break.php` to new `lib/maintenance-break-pattern.php`
4. Remove all files from old version except `articles`, `cron`, `media`, `pages` and `skins`
5. Merge new version with old version
<br><br>

# How it works
The simpleblog is divided into three parts: main page, tags and post. Tags/Post can be detached by setting `$simpleblog['taglinks']`/`$simpleblog['postlinks']` and `$simpleblog['datelinks']` to false.<br>
After that you can remove:
* for tag: `tag` directory and `lib/coreTag.php`
* for post: `post` directory and `lib/corePost.php`
<br><br>

## Core
The heart of the Simpleblog is divided into four parts:
* `core.php` -> contains function that render articles.
* `coreIndex.php` -> contains functions that prepare and groups articles for main page
* `coreTag.php` -> the same as `coreIndex.php` but for tag part
* `corePost.php` -> the same as `coreIndex.php` but for post part
<br>

## Frontend
The frontend consists of the following files:
* htmlheaders.php that imports favicon, theme, etc
* header.php with top header
* headlinks.php with defined menu
* footer.php

## Settings
Settings are located in router.php (php-cli configuration) or in settings.php (apache configuration).
<br>

## Administration
You can manage the Simpleblog in two ways: through admin panel and ssh.
<br>

## Modules
The Simpleblog has a modular structure
<br>

### admin panel (optional)
Basic script set that allow manage the Simpleblog from a web browser.<br>
Requires PHP >= 5.5.0 (whole cms is developed on PHP v7)<br>
Default login and password is `simpleblog`.<br>
`admin/disabled.php` completely disables the panel ("panic button"). Remove this file if you want use admin panel.<br>
To enable backup function, read https://github.com/MissKittin/simpleblog/tree/master/zip.lib
<br><br>
Admin panel modules:
* core modules:
	* `admin-settings.php` -> admin panel configuration and credentials, don't touch this (change password on first login)
	* `footer.php`, `header.php`
	* `menu` (material installed by default)
	* `login` (material forms installed by default)
	* `skins` (material-green installed by default)
* Status (`index.php` in `admin`)
* Articles -> manage articles
* CMS -> edit simpleblog settings, html titles, maintenance break settings, change login credentials and create backup
* Cron -> manage cron tasks
* Elements -> edit `header.php`, `headlinks.php`, `footer.php`, `htmlheaders.php` and manage favicons
* Files -> simple file manager
* Media -> manage uploaded media files
* Pages -> manage pages
* Skins -> install, uninstall, edit and change current skin
<br>

### cron (optional)
Executes defined tasks, see https://github.com/MissKittin/simpleblog/blob/master/blog/lib/cron.php<br>
You can detach cron from Simpleblog, just comment https://github.com/MissKittin/simpleblog/blob/master/blog/router.php#L89 or https://github.com/MissKittin/simpleblog/blob/master/blog/settings.php#L34
<br>

### maintenance break pattern (optional)
Edit .router.php or settings.php: enable/disable switch and one ip on which the pattern isn't displayed<br>
See https://github.com/MissKittin/simpleblog/blob/master/blog/lib/maintenance-break.php
<br>

### pages (optional, enabled by default)
Static pages linked in `headlinks.php`
<br>

### prevent-index.php (required)
A simple script that prevents directory listing.
<br>

### temporary files (not installed by default)
Just create this directory manually, path `blog/tmp`<br>
Eg for timestamps and indicators created by cron tasks.
<br>

<br>

# Why?
Because I like PHP, simplicity and control over the code.
