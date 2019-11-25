# Simple blog v2 beta
I wanted a wordpress on my linux server, but without javascript. So I wrote <del>simple script</del> CMS, that render my short posts.
<br><br>

No cookies(\*\*), no sessions(\*\*) and conventional database, with tags, custom pages and hiding articles.<br>
\*\* cookies and sessions for login, javascript in TabManager.js
<br><br>

But first:<br>
1. you can rename `blog` to another name
2. enter into `blog`
3. run setup.sh or setup-links.sh (setup.bat or setup-links.bat on windows)\*
4. edit files indicated by the setup script (if you changed the directory name, update `$simpleblog['root_html']` variable)
5. copy blog/skins/default and create your own.
6. upload `blog` to server
<br><br>

\*setup is based on copy, setup-links is based on ln/mklink
<br><br>

# manual
https://github.com/MissKittin/simpleblog/blob/master/HOWTO.md
<br><br>

# admin panel
default login and password is `simpleblog`<br>
admin/disabled.php completely disables the panel - remove this file
<br><br>

# additional functions
checkDate.php -> apply eg custom style beetwen start and end date<br>
checkEaster.php -> checkDate for Easter - calculates the first day of Easter and the end date by adding n days to the first day
<br><br>

# skurkawudka czorno
standard skin with "allsaints" customization<br>
![preview](https://raw.githubusercontent.com/MissKittin/simpleblog/master/screenshots/preview_main.png)
