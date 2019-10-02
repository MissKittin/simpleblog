# Simple blog renderer
I wanted a wordpress on my linux server, but without javascript. So I wrote simple script, that render my short posts.
<br><br>
No cookies, no sessions and conventional database, with tags, custom pages and hiding articles. Managed via SSH (admin panel is primitive and unsecured).<br>
But first: copy /blog/skins/default and create your own.
<br><br>
/blog/admin/disabled.php, /blog/pages/index.php, /blog/skins/index.php, /blog/skins/default/index.php and /blog/media/index.php are symbolic links to /blog/prevent-index.php
