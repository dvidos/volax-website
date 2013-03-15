<?php
$this->breadcrumbs=array(
	$model->title,
);
$this->pageTitle=$model->title;
?>

<h1><?php echo CHtml::encode($model->title); ?></h1>

<?php
	$dataProvider = new CActiveDataProvider('Post', array(
		'criteria'=>array(
			'condition'=>'category_id = :cid AND (status=' . Post::STATUS_PUBLISHED . ' OR status=' . Post::STATUS_ARCHIVED . ')',
			'params'=>array(':cid'=>$model->id),
		),
		'sort'=>array(
			'defaultOrder'=>'create_time DESC',
		),
	));
		
	$this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'/post/_indexEntry',
		'template'=>"{items}\n{pager}",
	));
?>


