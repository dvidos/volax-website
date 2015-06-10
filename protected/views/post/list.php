<?php
	
	$tag = array_key_exists('tag', $_GET) ? @$_GET['tag'] : '';
	if (!empty($tag))
	{
		$this->pageTitle = $tag . ': Αναρτήσεις';
		echo CHtml::tag('h1', array('style'=>'margin: .5em 0'), CHtml::encode($tag) .': Αναρτήσεις');
		echo CHtml::tag('p', array('style'=>'margin: .5em 0 3em 0;'), 
			'Εμφανίζονται αναρτήσεις με την ετικέττα <b>' . CHtml::encode($tag) . '</b>.' . 
			'&nbsp; &nbsp;' .
			CHtml::link('Ολες οι ετικέττες', array('/post/tags')) . 
			', ' .
			CHtml::link('Ολες οι αναρτήσεις', array('/'))
		);
	}

	$this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_layoutHomePage',
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


