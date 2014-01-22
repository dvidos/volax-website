<?php
	$this->pageTitle = 'Διόρθωση: ' . CHtml::encode($model->title); 
?>

<h1><?php echo $this->pageTitle; ?></h1>

<?php 
	if(Yii::app()->user->hasFlash('postSaved'))
	{
		echo CHtml::tag('div', array('class'=>'flash-success'), CHtml::encode(Yii::app()->user->getFlash('postSaved')));
		$js = '$(document).ready(function(){ setTimeout(function() { $(".flash-success").slideUp(); }, 4000); });';
		echo CHtml::tag('script', array(), $js);
	}
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>