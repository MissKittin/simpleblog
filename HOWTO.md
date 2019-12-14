# Table of contents
* [First steps after clone](#first-steps-after-clone)
* [root_html vs root_php](#simpleblogroot_html-vs-simpleblogroot_php)
* [Simpleblog in DOCUMENT_ROOT](#simpleblog-in-document_root)
* [How to write articles](#how-to-write-articles)
* [How to write articles with a lot of resources](#how-to-write-articles-with-a-lot-of-resources)
* [How to write pages](#how-to-write-pages)
* [Editing header.php, footer.php and headlinks.php](#editing-headerphp-footerphp-and-headlinksphp)
* [How to create skins](#how-to-create-skins)
* [How to allow javascript](#how-to-allow-javascript)
* [Articles addressing scope](#articles-addressing-scope)
* [php-cli test pool](#php-cli-test-pool)
* [Supported HTTP servers](#supported-http-servers)
* [How to upgrade](#how-to-upgrade)
* [Apache htpasswd security for admin panel](#apache-htpasswd-security-for-admin-panel)
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
* [History](#history)
	* [alpha](#alpha)
	* [v1](#v1)
	* [v2.0](#v20)
	* [today](#today)
* [Limitations](#limitations)
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

# Simpleblog in DOCUMENT_ROOT
1. (only for php-cli version) open `.router.php` and put `return false;` before `?>`:
```
	// execute cron tasks
	if(file_exists($simpleblog['root_php'] . '/lib/cron.php')) include $simpleblog['root_php'] . '/lib/cron.php';

	// drop cache
	unset($simpleblog_router_cache);

	return false;
?>
```
2. change `$simpleblog['root_html']='/blog';` to `$simpleblog['root_html']='';` in `.router.php` or `settings.php`
3. push `.router.php` or `settings.php`
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

# php-cli test pool
Setup simpleblog for php built-in server, and type `php -S 0.0.0.0:8080 router.php`, where `router.php` is in the the repo root, not the one in the `blog` directory.
<br><br>

# Supported HTTP servers
PHP built-in server and Apache. If you want run the Simpleblog on other server, I recommend configure it for Apache.<br>
`auto_prepend_file` way isn't tested and I don't recommend this (but if you want...)
<br><br>

# How to upgrade
1. Merge `favicon`, `footer.php`, `header.php`, `headlinks.php`, `htmlheaders.php` content to new files in `lib`
2. Merge `settings.php` or `router.php` settings, uncomment backward compatibility options
3. Move maintenance break pattern from old `maintenance-break.php` to new `lib/maintenance-break-pattern.php`
4. Remove all files from old version except `articles`, `cron`, `media`, `pages` and `skins`
5. Merge new version with old version
<br><br>

# Apache htpasswd security for admin panel
Why not? Read some tutorials :)
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
* Files -> simple file manager with cURL function
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
<br><br>

# History
## alpha
In alpha version the simpleblog was only simple script. Articles was created only via SSH.<br>
The alpha version tree:
* articles (dir)
	* 000001.php
* media (dir)
* pages (dir)
	* samplepage (dir)
		* index.php
	* index.php
* skins (dir)
	* default (dir)
		* style.css
* style (dir)
	* index.php
* footer.php
* header.php
* headlinks.php
* index.php
* prevent-index.php
* router.php
* settings.php
<br>
Content of files:

[articles/000001.php](https://github.com/MissKittin/simpleblog/blob/457b31e5abbde2cd5977d11453935beb72bdc6d1/blog/articles/public_000001.php)<br>
[pages/samplepage/index.php](https://github.com/MissKittin/simpleblog/blob/457b31e5abbde2cd5977d11453935beb72bdc6d1/blog/pages/samplepage/index.php)<br>
[skins/default/style.css](https://github.com/MissKittin/simpleblog/blob/457b31e5abbde2cd5977d11453935beb72bdc6d1/blog/skins/default/style.css)<br>
[style/index.php](https://github.com/MissKittin/simpleblog/blob/457b31e5abbde2cd5977d11453935beb72bdc6d1/blog/style/index.php)<br>
[footer.php](https://github.com/MissKittin/simpleblog/blob/457b31e5abbde2cd5977d11453935beb72bdc6d1/blog/footer.php)<br>
[header.php](https://github.com/MissKittin/simpleblog/blob/457b31e5abbde2cd5977d11453935beb72bdc6d1/blog/header.php)<br>
[headlinks.php](https://github.com/MissKittin/simpleblog/blob/457b31e5abbde2cd5977d11453935beb72bdc6d1/blog/headlinks.php)<br>
[prevent-index.php](https://github.com/MissKittin/simpleblog/blob/457b31e5abbde2cd5977d11453935beb72bdc6d1/blog/prevent-index.php)<br>
[settings.php](https://github.com/MissKittin/simpleblog/blob/457b31e5abbde2cd5977d11453935beb72bdc6d1/blog/settings.php)<br>
`index.php` (core of simpleblog alpha)
```
<?php
	// Blog page renderer
	// 13.04.2019 - 15.04.2019
	// count_pages 25.09.2019

	include 'settings.php';

	isset($_GET['page']) ? $loop_start=$_GET['page'] : $loop_start=1; // max entries on one page

	$loop_start=1;
	if(isset($_GET['page'])) // is_int() protection
		if(is_numeric($_GET['page']))
			$loop_start=$_GET['page'];
	settype($loop_start, 'integer');

	// pages counter
	function count_pages()
	{
		global $entries_per_page; // in settings.php
		$pages_count=1;
		$pages_ind=0;

		$current_page='1';
		if(isset($_GET['page']))
			$current_page=$_GET['page'];

		global $files; // used in div articles before executing function
		foreach(array_reverse($files) as $file)
		{
			if(($file != '.') && ($file != '..') && (strpos($file, 'public_') === 0))
			{
				// check if article is public
				if(strpos(file_get_contents('articles/' . $file), '$art_public=true;'))
				{
					// count how many pages are available
					if($pages_ind === $entries_per_page)
					{
						$pages_count++;
						$pages_ind=1; // must reset this
					}
					else
						$pages_ind++;
				}
			}

		}

		$i=1; // loop indicator
		while($i <= $pages_count)
		{
			if($i == $current_page)
				echo '<div class="page" id="current_page"><a href="?page='. $i .'">' . $i . '</a></div>'; // render current
			else
				echo '<div class="page"><a href="?page='. $i .'">' . $i . '</a></div>'; // render
			$i++;
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo "$page_title"; ?></title>
		<meta charset="utf-8">
		<link rel="shortcut icon" type="image/icon" href="<?php echo "$cms_root"; ?>favicon.ico">
		<link rel="stylesheet" type="text/css" href="<?php echo "$cms_root"; ?>style?root=<?php echo "$cms_root"; ?>">
	</head>
	<body>
		<div id="header">
			<?php include 'header.php'; ?>
		</div>
		<div id="headlinks">
			<?php include 'headlinks.php'; ?>
		</div>
		<div id="articles">
			<?php
				$loop_ind=1; // first if in foreach
				$files=scandir('articles/');
				foreach(array_reverse($files) as $file)
				{
					// debug
					//if(($file != '.') && ($file != '..') && (strpos($file, 'public_') === 0))
					//	echo '<!-- processing article ' . ltrim(preg_replace('/\.[^.]+$/', '', $file), '0') . ' -->' . "\n";

					/* Wait a sec!
						"omit" loop doesn't check if article is public or private
						articles may be duplicated on next page
					*/

					if($loop_ind >= ($loop_start*$entries_per_page)-($entries_per_page-1)) // omit unwanted entries and start from to eg 1-10, 11-20 etc
					{
						if(($file != '.') && ($file != '..'))
						{
							include 'articles/' . $file;

							// check if article is public
							if($art_public)
							{
								// render article
								echo '<div class="article">'."\n";
									echo '<div class="art-tags">'.$art_tags.'</div><div class="art-date">'.$art_date.'</div><div class="art-title"><h2>'.$art_title.'</h2></div>';
									echo "$art_content";
								echo '</div>'."\n";
							}
							else
								$loop_ind--;
						}

						if($loop_ind === $loop_start*$entries_per_page) // break if the maximum number of entries has been reached
							break;
						else
							$loop_ind++;
					}
					else
						$loop_ind++; // if entry is unwanted
				}
			?>
		</div>
		<div id="pages">
			<?php count_pages(); echo "\n"; ?>
		</div>
		<div id="footer">
			<?php include 'footer.php'; ?>
		</div>
	</body>
</html>
```
<br>

In September 25, 2019, the naming of files with articles changed and the `$art_public` flag was deprecated:

[index.php](https://github.com/MissKittin/simpleblog/blob/457b31e5abbde2cd5977d11453935beb72bdc6d1/blog/index.php)

The algorithm's operation principle has not changed since then.
<br>

## v1
In this version simple admin script, favicons and tag subsystem have been added. Also since this version skins are php scripts.
<br>

## v2.0
Version 2 is completely redesigned. The simpleblog has been modularized, with true admin panel (v1).
<br>

## today
v2.1 is v2.0 with many improvements, new features and admin panel v1.1. Also fixes bugs and creates new ones. Stable and currently maintained.
<br><br>

# Limitations
The simpleblog is inefficient when there are ~600000 articles in the database. I'm working to resolve this issue.<br>
There are ~50 articles on my blog, the execution time is 0.0067s (on ssd).<br>
Remember, the simpleblog was created to support a small website. For more advanced tasks use Wordpress or Joomla.
<br><br>

# Why?
Because I like PHP, simplicity and control over the code.
