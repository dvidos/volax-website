<?php
	$this->widget('zii.widgets.CMenu',array(
		'htmlOptions'=>array('class'=>'tabs'),
		'activeCssClass'=>'active',
		'items'=>array(
			array('label'=>'Διόρθωση', 'url'=>array('/admin/posts/update', 'id'=>$model->id)),
			array('label'=>'Σημειώσεις', 'url'=>array('/admin/posts/discuss', 'id'=>$model->id)),
			array('label'=>'Ιστορικό (' . $model->revisionCount . ')', 'url'=>array('/admin/posts/history', 'id'=>$model->id)),
			array('label'=>'Σχόλια (' . $model->commentCount . ')', 'url'=>array('/admin/comments/index', 'post_id'=>$model->id)),
			array('label'=>'Πληροφορίες', 'url'=>array('/admin/posts/info', 'id'=>$model->id)),
			array('label'=>'Επισκόπιση', 'url'=>array('/post/view', 'id'=>$model->id), 'linkOptions'=>array('target'=>'_blank')),
		),
	));
?>