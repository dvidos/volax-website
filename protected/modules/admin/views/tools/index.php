<h1>Εργαλεία</h1>

<table><tr><td width="33%">
	
	
	<h3>Xml Sitemap</h3>
	
	<!-- <p>Εδώ μπορείτε να παράξετε ένα αρχείο 
	<?php echo CHtml::link('sitemaps', 'https://support.google.com/webmasters/answer/156184?hl=en', array('target'=>'_blank')); ?>
	για καλύτερη αναζήτηση από την Google και άλλες μηχανές αναζήτησης, όπως προτείνεται από τα 
	<?php echo CHtml::link('Google Webmaster Tools', 'https://www.google.com/webmasters/tools', array('target'=>'_blank')); ?>.</p>
	
	<p>Το αρχείο θα είναι το <?php echo $xml_sitemap_filename; ?> 
	και μπορείτε να το δείτε στο <?php echo CHtml::link($xml_sitemap_url, $xml_sitemap_url); ?></p> -->
	
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


	<h3>Μέγεθος αρχείων</h3>
	
	
	<p>Μέτρηση μεγάθους αρχείων, καταλόγων και εύρεση ορφανών αρχείων</p>
	<p><?php
		echo CHtml::button('Μέγεθος αρχείων', array(
			'onClick'=>'window.location = "' . $this->createUrl('/admin/tools/diskUsage') . '";',
		));
	?></p>
	
	
	
	



</td><td width="0%">



</td></tr></table>


