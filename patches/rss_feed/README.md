# rss feed
download `simpleblog-rss-feed-patch-full.zip`,<br>
open admin panel -> CMS,<br>
upload `simpleblog-rss-feed-patch-full.zip` in `Patch CMS` section,<br>
set switch to `ON` position<br>
and click `Apply patch`
<br><br>

# manual installation
run setup and upload tree to server
<br><br>

# rss button & html meta
in `htmlheaders.php`: `<?php /* rss feed */ ?><link rel="alternate" type="application/rss+xml" title="<?php echo $simpleblog['title']; ?>" href="<?php echo $simpleblog['root_html']; ?>/feed">`<br>
in `headlinks.php`: `<a class="headlink" style="background-image: url('<?php echo $simpleblog['root_html']; ?>/feed/feed.svg'); background-size: contain; background-repeat: no-repeat; background-position: center center; background-color: transparent;" href="<?php echo $simpleblog['root_html']; ?>/feed"></a>`
<br><br>

# generating cache
go to `tmp/feed_cache` and create `generate_cache` file<br>
cache will be generated on rss request<br>
or go to admin panel -> Feed and click generate button
