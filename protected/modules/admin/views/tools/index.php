<h1>Εργαλεία</h1>

<table>
<tr><td width="33%">
	<p>Εδώ μπορείτε να παράξετε ένα αρχείο 
	<?php echo CHtml::link('sitemaps', 'https://support.google.com/webmasters/answer/156184?hl=en', array('target'=>'_blank')); ?>
	για καλύτερη αναζήτηση από την Google και άλλες μηχανές αναζήτησης</p>
	
	<p><?php
		echo CHtml::button('Παραγωγή xml sitemap', array(
			'onClick'=>'window.location = "' . $this->createUrl('/admin/tools/xmlSitemap') . '";',
		)) . '<br />';;
	?></p>
	
	<p>Για να στείλετε το αρχείο στην Google, επισκεφτείτε το 
	<?php echo CHtml::link('Google Webmaster Tools', 'https://www.google.com/webmasters/tools', array('target'=>'_blank')); ?>
	</p>
	
	
	
	
</td><td width="33%">

b



</td><td width="33%">

c



</td></tr>
</table>


