<h1>Ορφανά αρχεία</h1>
<style>
	a.plain { font-weight: normal !important; text-decoration: none; }
	a.plain:hover { text-decoration: underline; }
</style>
<?php


	echo '<h2>Στατιστικά αρχεία</h2>';
	echo '<ul>';
	echo '<li><b>' . count($existing_files) . '</b> αρχεία στο /uploads ('.Yii::app()->stringTools->friendlySize($existing_files_size).')';
	echo '<li><b>'.$old_volax_tinos_files_count.'</b> αρχεία χρησιμοποιούνται από τις παλιές σελίδες του volax-tinos.gr ('.Yii::app()->stringTools->friendlySize($old_volax_tinos_files_size).')';
	echo '<li><b>' . count($used_files) . '</b> αρχεία χρησιμοποιούνται ('.Yii::app()->stringTools->friendlySize($used_files_size).')';
	echo '<li><b>' . count($orphan_files) . '</b> ορφανά αρχεία, που δεν χρησιμοποιούνται ('.Yii::app()->stringTools->friendlySize($orphan_files_size).')';
	echo '<li><b>' . count($missing_files) . '</b> αρχεία που δεν βρέθηκαν';
	echo '<li><b>' . count($external_links) . '</b> εξωτερικοί σύνδεσμοι';
	echo '</ul>' . "\r\n\r\n";
	
	
	echo '<h2>' . CHtml::link('Χρησιμοποιούμενα αρχεία (' . count($used_files) . ')', '#', array('onClick'=>'$("#used-files").slideToggle(); return false;')) . '</h2>';
	echo '<p id="used-files" style="display:none;">' . "\r\n";
	foreach ($used_files as $fn => $post_ids)
	{
		echo CHtml::link(CHtml::encode($fn), Yii::app()->baseUrl . $fn, array('class'=>'plain', 'target'=>'_blank')) . ' &nbsp; ';
		foreach ($post_ids as $post_id)
			echo ' ' . CHtml::link($post_id, array('/admin/posts/update', 'id'=>$post_id), array('target'=>'_blank'));
		echo '<br />' . "\r\n";
	}
	echo '</p>';
	
	
	echo '<h2>' . CHtml::link('Ορφανά (αχρησιμοποίητα) αρχεία (' . count($orphan_files) . ')', '#', array('onClick'=>'$("#orphan-files").slideToggle(); return false;')) . '</h2>';
	echo '<p id="orphan-files" style="display:none;">' . "\r\n";
	foreach ($orphan_files as $fn)
		echo CHtml::link(CHtml::encode($fn), Yii::app()->baseUrl . $fn, array('class'=>'plain', 'target'=>'_blank')) . '<br>' . "\r\n";
	echo '</p>';
	
	
	echo '<h2>' . CHtml::link('Αρχεία που δεν βρέθηκαν (' . count($missing_files) . ')', '#', array('onClick'=>'$("#missing-files").slideToggle(); return false;')) . '</h2>';
	echo '<p id="missing-files" style="display:none;">' . "\r\n";
	foreach ($missing_files as $fn => $post_ids)
	{
		echo CHtml::encode($fn) . ' &nbsp; ';
		foreach ($post_ids as $post_id)
			echo ' ' . CHtml::link($post_id, array('/admin/posts/update', 'id'=>$post_id), array('target'=>'_blank'));
		echo '<br />' . "\r\n";
	}
	echo '</p>';
	
	
	echo '<h2>' . CHtml::link('Εξωτερικοί σύνδεσμοι (' . count($external_links) . ')', '#', array('onClick'=>'$("#external-links").slideToggle(); return false;')) . '</h2>';
	echo '<p id="external-links" style="display:none;">' . "\r\n";
	foreach ($external_links as $url => $post_ids)
	{
		echo CHtml::link(CHtml::encode($url), $url, array('class'=>'plain', 'target'=>'_blank')) . ' &nbsp; ';
		foreach ($post_ids as $post_id)
			echo ' ' . CHtml::link($post_id, array('/admin/posts/update', 'id'=>$post_id), array('target'=>'_blank'));
		echo '<br />' . "\r\n";
	}
	echo '</p>';
	
	
	
?>


