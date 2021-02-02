<?php
	// Simpleblog v2 core functions
	// article renderer
	// 11.11.2019

	// deny direct access
	if(!isset($simpleblog))
	{
		include './prevent-index.php'; exit();
	}

	// render articles
	function simpleblog_engineCore($article, $taglinks, $postlinks, $datelinks)
	{
		// Usage: simpleblog_engineCore($article, $simpleblog['taglinks'], $simpleblog['postlinks'], $simpleblog['datelinks'])
		// where $simpleblog['taglinks'], $simpleblog['postlinks'] and $simpleblog['datelinks'] are boolean

		// import variables
		global $simpleblog;
		global $cms_root;
		global $cms_root_php;

		$return=''; // output

		// read article source
		include $article;

		// render tags if enabled
		if($taglinks)
		{
			$tags='';
			foreach(explode('#', $art_tags) as $tag)
				if($tag != '')
				{
					$tag=trim($tag);
					if(isset($art_style['taglink']))
						$tags=$tags . ' <a href="' . $simpleblog['root_html'] . '/tag?tag=' . urlencode('#' . $tag) . '" style="' . $art_style['taglink'] . '">#' . $tag . '</a>';
					else
						$tags=$tags . ' <a href="' . $simpleblog['root_html'] . '/tag?tag=' . urlencode('#' . $tag) . '">#' . $tag . '</a>';
				}
			$art_tags=$tags;
		}

		// render date if enabled
		if($datelinks)
		{
			$art_date=explode('.', $art_date);
			if(isset($art_style['date']))
				$art_date='<a href="' . $simpleblog['root_html'] . '/post?date=' . $art_date[0] . '.' . $art_date[1] . '.' . $art_date[2] . '" style="' . $art_style['date'] . '">' . $art_date[0] . '</a>.<a href="' . $simpleblog['root_html'] . '/post?date=' . $art_date[1] . '.' . $art_date[2] . '" style="' . $art_style['date'] . '">' . $art_date[1] . '</a>.<a href="' . $simpleblog['root_html'] . '/post?date=' . $art_date[2] . '" style="' . $art_style['date'] . '">' . $art_date[2] . '</a>';
			else
				$art_date='<a href="' . $simpleblog['root_html'] . '/post?date=' . $art_date[0] . '.' . $art_date[1] . '.' . $art_date[2] . '">' . $art_date[0] . '</a>.<a href="' . $simpleblog['root_html'] . '/post?date=' . $art_date[1] . '.' . $art_date[2] . '">' . $art_date[1] . '</a>.<a href="' . $simpleblog['root_html'] . '/post?date=' . $art_date[2] . '">' . $art_date[2] . '</a>';
		}

		// render article
		if(isset($art_style['article'])) $return.='<div class="article" style="' . $art_style['article'] . '">'."\n"; else $return.='<div class="article">'.PHP_EOL;
			if(isset($art_style['tags'])) $return.='<div class="art-tags" style="' . $art_style['tags'] . '">'.$art_tags.'</div>'; else $return.='<div class="art-tags">'.$art_tags.'</div>';
			if(isset($art_style['date'])) $return.='<div class="art-date" style="' . $art_style['date'] . '">'.$art_date.'</div>'.PHP_EOL; else $return.='<div class="art-date">'.$art_date.'</div>'.PHP_EOL;
			if(isset($art_style['title'])) $return.='<div class="art-title" style="' . $art_style['title'] . '">'; else $return.='<div class="art-title">';
				if($postlinks) $return.='<a href="' . $simpleblog['root_html'] . '/post?id=' . (int)str_replace('public_', '', pathinfo($article, PATHINFO_FILENAME)) . '"><span class="placeholder_link_to_article">PLACEHOLDER_LINK_TO_ARTICLE</span>';
					if(isset($art_style['title-header'])) { if(($art_style['title-header'] === '') || ($art_style['title-header'])) $return.='<h2>'.$art_title.'</h2>'; else $return.=$art_title; } else $return.='<h2>'.$art_title.'</h2>';
				if($postlinks) $return.='</a>';
			$return.='</div>'.PHP_EOL;
			$return.=$art_content;
		$return.='</div>'.PHP_EOL;

		return $return;
	}
?>