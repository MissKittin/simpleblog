# Simple blog renderer v2 beta
I wanted a wordpress on my linux server, but without javascript. So I wrote <del>simple script</del> CMS, that render my short posts.
<br><br>

No cookies(\*\*), no sessions(\*\*) and conventional database, with tags, custom pages and hiding articles.<br>
\*\* cookies and sessions for login, javascript in TabManager.js
<br><br>

But first:<br>
1. enter into blog/
2. run setup.sh (setup.bat or setup-links.bat on windows\*)
3. edit files indicated by the setup script
4. copy blog/skins/default and create your own.
5. upload cms to server
<br><br>

\*setup.bat is based on copy, setup-links.bat is based on mklink
<br><br>

# admin panel
edit admin/admin-settings.php line 25 and 26<br>
admin/disabled.php completely disables the panel
<br><br>

# cron
read https://github.com/MissKittin/simpleblog/blob/master/blog/lib/cron.php#L5
<br><br>

# maintenace break pattern
edit settings.php: enable/disable switch and one ip on which the pattern isn't displayed
<br><br>

# additional functions
checkDate.php -> apply eg custom style beetwen start and end date<br>
checkEaster.php -> checkDate for Easter - calculates the first day of Easter and the end date by adding n days to the first day
<br><br>

# skurkawudka czorno
standard skin with "allsaints" customization<br>
![preview](https://raw.githubusercontent.com/MissKittin/simpleblog/master/preview_main.png)
<br><br>
