# cache library for index and tags
speeds up page loading by dumping page content/tags list into one file
<br><br>

# zip installation
use only if you have installed admin panel and tag subsystem (or modify package)<br>
download `simpleblog-cache-patch-full.zip`,<br>
open admin panel -> CMS,<br>
upload `simpleblog-cache-patch-full.zip` in `Patch CMS` section,<br>
set switch to `ON` position<br>
and click `Apply patch`
<br><br>

# manual installation
run setup and upload tree to server<br>
(setup is based on copy, setup-links is based on ln/mklink)
<br><br>

# generating cache
go to `tmp/posts_cache` or `tmp/tag_cache` and create `generate_cache` file<br>
cache will be generated on page refresh<br>
or go to admin panel -> Cache and click generate buttons
