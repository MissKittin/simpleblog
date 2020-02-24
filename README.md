# Simpleblog v2.1u1
I wanted a wordpress on my linux server, but without javascript. So I wrote <del>simple script</del> CMS, that render my short posts.
<br><br>

No cookies(\*\*), no sessions(\*\*) and conventional database, with tags, custom pages and hiding articles.<br>
\*\* cookies and sessions for login, javascript in TabManager.js
<br><br>

If you know the basics of PHP, HTML and CSS, the Simpleblog is for you :)<br>
But first read manual.
<br><br>

# manual
https://github.com/MissKittin/simpleblog/blob/master/HOWTO.md
<br><br>

# admin panel
requires PHP >= 5.5.0<br>
default login and password is `simpleblog`<br>
admin/disabled.php completely disables the panel - remove this file<br>
online backup: https://github.com/MissKittin/simpleblog/tree/master/zip.lib
<br><br>

# additional cron tasks
`requireHTTPS.php`
<br><br>

# additional functions
`checkDate.php` -> apply eg custom style beetwen start and end date<br>
`checkEaster.php` -> checkDate for Easter - calculates the first day of Easter and the end date by adding n days to the first day
<br><br>

# patches
a draft of the patch that adds the comments function is ready, but I don't using comments on my blog. If you use the simpleblog and you want this patch, write to me.
<br><br>

# skurkawudka czorno
skin of my blog with "allsaints" customization (only for me :-)<br>
![preview](https://raw.githubusercontent.com/MissKittin/simpleblog/master/screenshots/preview_main.png)
