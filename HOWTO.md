# First steps after clone
1. you can rename `blog` to another name
2. enter into `blog`
3. run setup.sh or setup-links.sh (setup.bat or setup-links.bat on windows)\*
4. edit files indicated by the setup script (if you changed the directory name, update `$simpleblog['root_html']` variable)
5. copy blog/skins/default and create your own.
6. upload `blog` to server
<br><br>\*setup is based on copy, setup-links is based on ln/mklink
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

# How to write pages
Copy `samplepage` to `your_page_link`, upload images, styles, fonts etc to this dir and edit `index.php`.
<br><br>

# Editing header.php, footer.php and headlinks.php
Don't touch first `<?php` block of code. Write content below `?>`.
<br><br>

# How to create skins
Copy `skins/default` to `skins/your_theme_name`. `index.php` is main file (don't touch first `<?php` block of code, write content below `?>`). You can add more css by creating `skins/your_theme_name/my_addon/index.php` and linking it by css `@import` rule. You can use my functions: https://github.com/MissKittin/simpleblog/tree/master/additional_functions
<br>
To create an installation package, just zip the directory with your theme.
<br><br>

# How to allow javascript
Change https://github.com/MissKittin/simpleblog/blob/master/blog/lib/htmlheaders.php#L3 to<br>
`<meta http-equiv="Content-Security-Policy" content="script-src 'self';">`<br>
This prevent inline script executing, but allow js files from your website.<br>
**Achtung!** Allow inline styles or you breaks $art_style
<br><br>

# Articles addressing scope
Articles are addressed from `000001` to `999999`. You can increase scope by adding zeros at the begin of number, eg `0000999999`<br>
**Achtung!** You must add zeros in all articles! (bash automation soon)
<br><br>

# Supported HTTP servers
PHP built-in server and Apache. If you want run the Simpleblog on other server, I recommend configure it for Apache.
<br><br>

# How to upgrade
1. Merge `favicon`, `footer.php`, `header.php`, `headlinks.php`, `htmlheaders.php` content to new files in `lib`
2. Merge `settings.php` or `router.php` settings, uncomment backward compatibility options
3. Move maintenace break pattern from old `maintenace-break.php` to new `lib/maintenace-break-pattern.php`
4. Remove all files from old version except `articles`, `cron`, `media`, `pages` and `skins`
5. Merge new version with old version
<br><br>

# How it works
The simpleblog is divided into two parts: main page and tags. Tags can be detached by setting `$simpleblog['taglinks']` to false.
<br><br>

## Core
The heart of the Simpleblog is the `core.php` file. This file contains functions that prepare, groups and render articles.
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
Default login and password is `simpleblog`.<br>
`admin/disabled.php` completely disables the panel.<br>
To enable backup function, read https://github.com/MissKittin/simpleblog/tree/master/zip.lib
<br>

### cron (optional)
Executes defined tasks, see https://github.com/MissKittin/simpleblog/blob/master/blog/lib/cron.php<br>
You can detach cron from Simpleblog, just comment https://github.com/MissKittin/simpleblog/blob/master/blog/router.php#L89 or https://github.com/MissKittin/simpleblog/blob/master/blog/settings.php#L34
<br>

### maintenace break pattern (optional)
Edit .router.php or settings.php: enable/disable switch and one ip on which the pattern isn't displayed<br>
See https://github.com/MissKittin/simpleblog/blob/master/blog/lib/maintenace-break.php
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
