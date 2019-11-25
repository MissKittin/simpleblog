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
**Achtung!** It contains one library that is GPL2 licensed. If you don't agree with GPL2, remove `blog/admin/lib/zip.lib.php`
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
