<?php 
	$this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'/post/_layoutHomePage',
		'emptyText'=>'Φαίνεται πως δεν υπάρχουν ακόμα αναρτήσεις εδώ...',
		'template'=>"{items}\n{pager}",
		'pager'=>array(
			'class'=>'CLinkPager',
			'header'=>'Σελίδα: &nbsp; ',
			'prevPageLabel'=>'Προηγούμενη',
			'nextPageLabel'=>'Επόμενη',	
		),
		'ajaxUpdate'=>false, // to disable ajax update
	)); 

