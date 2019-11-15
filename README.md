# Simple blog renderer v2 alpha
I wanted a wordpress on my linux server, but without javascript. So I wrote simple script, that render my short posts.
<br><br>
No cookies, no sessions and conventional database, with tags, custom pages and hiding articles. Managed via SSH (admin panel is primitive and unsecured).<br><br>
But first:<br>
1) run setup.sh (setup.bat or setup-links.bat on windows*)
2) edit files indicated by the setup script
3) copy /blog/skins/default and create your own.<br><br>
*setup.bat is based on copy, setup-links.bat is based on mklink<br><br>
with my skin looks like this:<br>
![preview](https://raw.githubusercontent.com/MissKittin/simpleblog/master/preview_main.png)<br><br>
# additional functions
checkDate.php -> apply eg custom style beetwen start and end date<br>
checkEaster.php -> checkDate for Easter - calculates the first day of Easter and the end date by adding n days to the first day
<br><br>
# cron
read https://github.com/MissKittin/simpleblog/blob/master/blog/cron.php<br><br>
# maintenace break pattern
edit maintenace-break.php: enable/disable switch and one ip on which the pattern isn't displayed
