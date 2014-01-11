<?php 
	$this->pageTitle = 'Αρχική'; 

	if(!empty($_GET['tag']))
	{
		echo '<h1>Αναρτήσεις με το tag <i>' . CHtml::encode($_GET['tag']) . '</i></h1>' . "\r\n\r\n";
	}

	$this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'/post/_layoutDesiredWidth',
		'template'=>"{items}\n{pager}",
		'pager'=>array(
			'class'=>'CLinkPager',
			'header'=>'Σελίδα: &nbsp; ',
			'prevPageLabel'=>'Προηγούμενη',
			'nextPageLabel'=>'Επόμενη',	
		),
		'ajaxUpdate'=>false, // to disable ajax update
	)); 
?>
