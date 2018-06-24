<h1>Εργαλεία</h1>

<table><tr><td width="33%">
	
	
	<h3>Xml Sitemap</h3>
	<p>Παραγωγή αρχείου <?php echo CHtml::link('sitemaps', 'https://support.google.com/webmasters/answer/156184?hl=en', array('target'=>'_blank')); ?>, 
	στο <?php echo CHtml::link($xml_sitemap_url, $xml_sitemap_url); ?>, 
	για τα <?php echo CHtml::link('Google Webmaster Tools', 'https://www.google.com/webmasters/tools', array('target'=>'_blank')); ?>.</p>
	<?php
		if(Yii::app()->user->hasFlash('xmlSitemap'))
		{
			echo CHtml::tag('div', array('class'=>'flash-success'), CHtml::encode(Yii::app()->user->getFlash('xmlSitemap')));
			$js = '$(document).ready(function(){ setTimeout(function() { $(".flash-success").slideUp(); }, 4000); });';
			echo CHtml::tag('script', array(), $js);
		}
	?>
	<p><?php
		echo CHtml::button('Παραγωγή xml sitemap', array(
			'onClick'=>'window.location = "' . $this->createUrl('/admin/tools/xmlSitemap') . '";',
		));
	?></p>
	

	
	

	
	
	
	
</td><td width="33%">


	<h3>Search Post Content</h3>
	<p><?php
		echo CHtml::button('Search Post Content', array(
			'onClick'=>'window.location = "' . $this->createUrl('/admin/tools/searchPostContent') . '";',
		));
	?></p>
	
	
	



</td><td width="0%">

	
	<h3>Πληροφοριακά εργαλεία</h3>
	<ul>
		<li><?php echo CHtml::link('Χρήση δίσκου, αρχεία ανά μέγεθος', array('/admin/tools/diskUsage')); ?></li>
		<li><?php echo CHtml::link('Αρχεία, ορφανά και χρησιμοποιούμενα', array('/admin/tools/orphanFiles')); ?></li>
		<li><?php echo CHtml::link('Posts Images', array('/admin/tools/postsImages')); ?></li>
		<li><?php echo CHtml::link('Posts Links', array('/admin/tools/postsLinks')); ?></li>
		<li><?php echo CHtml::link('Posts Integrity', array('/admin/tools/postsIntegrity')); ?></li>
		<li><?php echo CHtml::link('Posts Languages', array('/admin/tools/postsLanguages')); ?></li>
		<li><?php echo CHtml::link('PHP info', array('/admin/tools/phpinfo')); ?></li>
	</ul>
	
	
</td></tr></table>


