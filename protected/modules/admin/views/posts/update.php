<?php
	$this->pageTitle = 'Διόρθωση: ' . CHtml::encode($model->title); 
?>

<div style="float:right; padding-top: .5em;">
	<?php 
		if ($model->id > 0) {
			echo CHtml::link('Επισκόπιση', array('/post/view', 'id'=>$model->id), array('target'=>'_blank')); 
			echo ' | ';
		}
		if ($model->id > 0) {
			echo CHtml::link($model->revisionCount . ' αλλαγές', array('/admin/postRevisions/index', 'PostRevision[post_id]'=>$model->id)); 
			echo ' | ';
		}
	?>
	<?php echo CHtml::link('Οδηγίες', array('/page/view', 'url_keyword'=>'editorNotes')); ?>
</div>
<h1><?php echo $this->pageTitle; ?></h1>
<div style="clear:both;"></div>

<?php 
	if(Yii::app()->user->hasFlash('postSaved'))
	{
		echo CHtml::tag('div', array('class'=>'flash-success'), CHtml::encode(Yii::app()->user->getFlash('postSaved')));
		$js = '$(document).ready(function(){ setTimeout(function() { $(".flash-success").slideUp(); }, 4000); });';
		echo CHtml::tag('script', array(), $js);
	}
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>