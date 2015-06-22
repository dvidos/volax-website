<?php 
	//header('Content-type: application/xml'); 
	//header('Content-type: text/plain'); 
	//$filename = 'Volax.gr Xml Sitemap.xml';
	//header("Content-disposition: attachment; filename=\"$filename\"");
	//foreach ($posts as $post)
	//	echo 'Post ' . $post->title . '<br />';
	//foreach ($categories as $category)
	//	echo 'Category ' . $category->title . '<br />';
	
	
	function formatUrl($loc, $changefreq, $priority, $lastmod = 0)
	{
		/*
		Tag				Required?	Description
		-----------		----------	---------------------------------------
		<urlset>		Required	Encloses all information about the set of URLs included in the sitemap.
		<url>			Required	Encloses all information about a specific URL.
		<loc>			Required	Specifies the URL. For images and video, specifies the landing page (aka play page).
		<lastmod>		Optional	Shows the date the URL was last modified, in YYYY-MM-DDThh:mmTZD format (time value is optional).
		<changefreq>	Optional	Provides a hint about how frequently the page is likely to change. Valid values are:
									- always. Use for pages that change every time they are accessed.
									- hourly, daily, weekly, monthly, yearly
									- never. Use this value for archived URLs.
		<priority>		Optional	Describes the priority of a URL relative to all the other URLs on the site. 
									This priority can range from 1.0 (extremely important) to 0.1 (not important at all).
									Note that the priority tag does not affect your site ranking in Google search results. 
									Priority values are only considered relative to other pages on your site so, 
									assigning a high priority (or specifying the same priority for all URLs) 
									will not boost your entire site search ranking.
		*/
		
		$html = '';
		$html .= "\t<url>\r\n";
		$html .= "\t\t" . CHtml::tag('loc', array(), $loc) . "\r\n";
		$html .= "\t\t" . CHtml::tag('changefreq', array(), $changefreq) . "\r\n";
		$html .= "\t\t" . CHtml::tag('priority', array(), $priority) . "\r\n";
		
		if ($lastmod != 0)
			$html .= "\t\t" . CHtml::tag('lastmod', array(), date('Y-m-d\TH:iP', $lastmod)) . "\r\n";
			
		$html .= "\t</url>\r\n";
		
		return $html;
	}
	
	function generateXml($posts, $categories)
	{
		$xml = '';
		
		$xml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
		$xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n";

		$xml .= formatUrl(Yii::app()->createAbsoluteUrl('/'), 'daily', '0.8', 0);
		
		foreach ($posts as $post)
			$xml .= formatUrl($post->getUrl(true), 'weekly', '0.5', $post->update_time);
			
		foreach ($categories as $category)
			$xml .= formatUrl($category->getUrl(true), 'weekly', '0.6');
	
		$xml .= "</urlset>\r\n";
		
		return $xml;
	}
	
	echo generateXml($posts, $categories);
	
	