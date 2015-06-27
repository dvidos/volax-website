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
			CHtml::link('Ολες οι αναρτήσεις', Yii::app()->homeUrl)
		);
	}
?>


<div class="expanded-post-list">
	<?php
		$this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'_listEntryExpanded',
			'emptyText'=>'Φαίνεται πως δεν υπάρχουν ακόμα αναρτήσεις εδώ...',
			'template'=>"{items}\r\n\r\n{pager}",
			'pager'=>Yii::app()->params['defaultPagerParams'],
			'ajaxUpdate'=>false, // to disable ajax update
		)); 
	?>
</div>

