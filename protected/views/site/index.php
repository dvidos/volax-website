<div class="expanded-post-list">
<?php 
	$this->pageTitle = '';
	$this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'/post/_listEntryExpanded',
		'emptyText'=>'Φαίνεται πως δεν υπάρχουν ακόμα αναρτήσεις εδώ...',
		'template'=>"{items}\r\n\r\n{pager}",
		'pager'=>Yii::app()->params['defaultPagerParams'],
		'ajaxUpdate'=>false, // to disable ajax update
	)); 

?>
</div>

