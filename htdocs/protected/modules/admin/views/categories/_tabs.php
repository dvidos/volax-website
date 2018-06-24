<?php
	$this->widget('zii.widgets.CMenu',array(
		'htmlOptions'=>array('class'=>'tabs'),
		'activeCssClass'=>'active',
		'items'=>array(
			array('label'=>'Διόρθωση', 'url'=>array('/admin/categories/update', 'id'=>$model->id)),
			array('label'=>'Σημειώσεις', 'url'=>array('/admin/categories/discuss', 'id'=>$model->id)),
			array('label'=>'Υποκατηγορίες (' . $model->subcategoriesCount . ')', 'url'=>array('/admin/categories/index', 'Category[parent_id]'=>$model->id)),
			array('label'=>'Αναρτήσεις (' . $model->postsCount . ')', 'url'=>array('/admin/posts/index', 'Post[category_id]'=>$model->id)),
			array('label'=>'Επισκόπιση', 'url'=>array('/category/view', 'id'=>$model->id), 'linkOptions'=>array('target'=>'_blank')),
		),
	));
?>