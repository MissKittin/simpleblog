<?php if(php_sapi_name() != 'cli-server') include '../../settings.php'; ?>
<?php
	if(php_sapi_name() === 'cli-server')
		if(substr(strtok($_SERVER['REQUEST_URI'], '?'), strlen(strtok($_SERVER['REQUEST_URI'], '?')) - 1) === '/')
		{
			include $simpleblog['root_php'] . '/lib/prevent-index.php';
			exit();
		}

	if(!isset($_GET['root']))
	{
		include $simpleblog['root_php'] . '/lib/prevent-index.php';
		exit();
	}

	header('Content-Type: text/css; X-Content-Type-Options: nosniff;');
?>
<?php
	echo '@import "' . $_GET['root'] . '/skins/' . $simpleblog['skin'] . '/sample_addon?root=' . $_GET['root'] . '";';
?>


/* default skin */

/* layout */
	body { /* colors */
		background-color: #aaaaaa;
	}

	#headlinks {
		margin: 0 auto;
		overflow: auto;
	}
	#headlinks a:link, #headlinks a:hover, #headlinks a:visited {
		text-decoration: none;
		color: #ffffff;
	}
	.headlink {
		background-color: #555555;
		justify-content: center;
		display: flex;
		align-items: center;
		float: right;
		text-align: center;
	}

	#articles {
		margin: 0 auto;
		background-color: #888888;
	}
	.article {
		margin: 0 auto;
		background-color: #aaaaaa;
	}
	.art-tags {
		float: left;
		font-style: italic;
	}
	.art-tags a:link, .art-tags a:hover, .art-tags a:visited {
		text-decoration: none;
		color: #000000;
	}
	.art-date {
		text-align: right;
	}
	.art-date a, .art-date a:hover, .art-date a:visited {
		color: #000000;  /* the same as in body */
		text-decoration: none;
	}
	.art-title a, .art-title a:hover, .art-title a:visited { /* link - article title */
		color: #000000;  /* the same as in body */
		text-decoration: none;
		-webkit-tap-highlight-color: transparent;
	}
	.art-title .placeholder_link_to_article { /* placeholder if title is empty */
		color: transparent;
		position: absolute;
	}
	.art-title .placeholder_link_to_article::selection {
		color: transparent;
		position: absolute;
	}

	.quotation { /* text formatting */
		text-align: center;
		font-style: italic;
	}
	del {
		color: #666666;
	}
	img, video {
		max-width: 100%;
	}

	#pages { /* page switches */
		margin: 0 auto;
		padding: 0;
		overflow: auto;
	}
	.page {
		float: left;
		background-color: #333333;
		justify-content: center;
		display: flex;
		align-items: center;
	}
	.page a:link, .page a:hover, .page a:visited {
		text-decoration: none;
		color: #ffffff;
	}
	#current_page { /* this page switch */
		background-color: #888888;
	}
	#current_page a:link, #current_page a:hover, #current_page a:visited {
		text-decoration: none;
		font-weight: bold;
		color: #111111;
	}

	#footer {
		margin: 0 auto;
		text-align: right;
	}

	#taglinks { /* for /tag */
		text-align: center;
	}
	#taglinks a:link, #taglinks a:hover, #taglinks a:visited {
		text-decoration: none;
		color: #550000;
	}
	.taglink {
		/* empty */
	}

/* hd resolution (default) */
	#headlinks {
		padding: 5px;
		width: 1080px;
	}
	#headlinks a:link, #headlinks a:hover, #headlinks a:visited {
		font-size: 16px;
	}
	.headlink {
		width: 100px;
		height: 40px;
		margin: 2px;
		border-radius: 5px;
	}

	#articles {
		padding: 10px;
		padding-bottom: 2px;
		width: 1080px;
		border-radius: 10px;
	}
	.article {
		margin-bottom: 10px;
		padding: 20px;
		width: 880px;
		border-radius: 5px;
	}

	.quotation {
		font-size: 20px;
	}

	#pages {
		width: 1080px;
	}
	.page {
		width: 40px;
		height: 40px;
		margin: 2px;
	}

	#footer {
		padding: 5px;
		width: 1080px;
		font-size: 15px;
	}

	#taglinks {
		margin-bottom: 2px;
	}
	#taglinks a:link, #taglinks a:hover, #taglinks a:visited {
		font-size: 16px;
	}

/* standard resolution */
@media only screen and (max-width: 1120px) {
	#headlinks {
		padding: 5px;
		width: 800px;
	}
	#headlinks a:link, #headlinks a:hover, #headlinks a:visited {
		font-size: 16px;
	}
	.headlink {
		width: 100px;
		height: 40px;
		margin: 2px;
	}

	#articles {
		padding: 10px;
		padding-bottom: 2px;
		width: 100px;
		width: 800px;
	}
	.article {
		margin-bottom: 10px;
		padding: 20px;
		width: 600px;
	}

	.quotation { /* text formatting */
		font-size: 20px;
	}

	#pages { /* page switches */
		width: 800px;
	}
	.page {
		width: 40px;
		height: 40px;
		margin: 2px;
	}

	#footer {
		padding: 5px;
		width: 800px;
		font-size: 15px;
	}

	#taglinks { /* for /tag */
		margin-bottom: 2px;
	}
	#taglinks a:link, #taglinks a:hover, #taglinks a:visited {
		font-size: 16px;
	}
}

/* phone and tablet resolution */
@media only screen and (max-width: 850px) {
	#headlinks {
		width: 600px;
		
	}
	.headlink {
		width: 90px;
		height: 30px;
	}
	#articles {
		width: 600px;
		padding: 5px;
		padding-top: 10px;
		padding-bottom: 1px;
	}
	.article {
		width: 500px;
	}
	#pages {
		width: 600px;
	}
	.page {
		width: 30px;
		height: 30px;
	}
	#footer {
		width: 600px;
	}
}

/* low resolution */
@media only screen and (max-width: 650px) {
	#headlinks {
		width: 420px;
	}
	#articles {
		width: 460px;
		background-color: #999999;
	}
	.article {
		width: 420px;
	}
	#pages {
		width: 420px;
	}
	.page {
		width: 25px;
		height: 25px;
	}
	#footer {
		width: 460px;
		font-size: 10px;
	}
}