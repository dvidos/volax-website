<?php 
	$filename = 'Volax.gr Xml Sitemap.xml';
	header('Content-type: application/xml'); 
	header("Content-disposition: attachment; filename=\"$filename\"");
?><?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"> 
<?php
/*

Tag				Required?	Description
-----------		----------	---------------------------------------
<urlset>		Required	Encloses all information about the set of URLs included in the sitemap.
<url>			Required	Encloses all information about a specific URL.
<loc>			Required	Specifies the URL. For images and video, specifies the landing page (aka play page).
<lastmod>		Optional	Shows the date the URL was last modified, in YYYY-MM-DDThh:mmTZD format (time value is optional).
<changefreq>	Optional	Provides a hint about how frequently the page is likely to change. Valid values are:
							- always. Use for pages that change every time they are accessed.
							- hourly
							- daily
							- weekly
							- monthly
							- yearly
							- never. Use this value for archived URLs.
<priority>		Optional	Describes the priority of a URL relative to all the other URLs on the site. 
							This priority can range from 1.0 (extremely important) to 0.1 (not important at all).
							Note that the priority tag does not affect your site ranking in Google search results. 
							Priority values are only considered relative to other pages on your site so, 
							assigning a high priority (or specifying the same priority for all URLs) 
							will not boost your entire site search ranking.
*/


	echo "\t<url>\r\n";
	echo "\t\t" . CHtml::tag('loc', array(), $this->createAbsoluteUrl('/')) . "\r\n";
	echo "\t\t" . CHtml::tag('changefreq', array(), 'daily') . "\r\n";
	echo "\t\t" . CHtml::tag('priority', array(), '0.8') . "\r\n";
	echo "\t</url>\r\n";
		
	foreach ($posts as $post)
	{
		echo "\t<url>\r\n";
		echo "\t\t" . CHtml::tag('loc', array(), $post->getUrl(true)) . "\r\n";
		echo "\t\t" . CHtml::tag('priority', array(), '0.5') . "\r\n";
		echo "\t\t" . CHtml::tag('lastmod', array(), date('Y-m-d\TH:iP', $post->update_time)) . "\r\n";
		echo "\t</url>\r\n";
	}
	
	foreach ($categories as $category)
	{
		echo "\t<url>\r\n";
		echo "\t\t" . CHtml::tag('loc', array(), $category->getUrl(true)) . "\r\n";
		echo "\t\t" . CHtml::tag('changefreq', array(), 'weekly') . "\r\n";
		echo "\t\t" . CHtml::tag('priority', array(), '0.6') . "\r\n";
		echo "\t</url>\r\n";
	}
?>
</urlset>
