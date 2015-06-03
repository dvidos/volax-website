<?php
$this->breadcrumbs=array(
	$model->title,
);
$this->pageTitle=$model->title;
?>

<div class="post medium">
	<?php
		if ($model->masthead != '')
			echo CHtml::tag('div', array('class'=>'masthead'), CHtml::encode($model->masthead));

		echo CHtml::tag('div', array('class'=>'title'), CHtml::link(CHtml::encode($model->title), $model->url));

		if ($model->prologue != '')
			echo CHtml::tag('div', array('class'=>'prologue'), CHtml::encode($model->prologue));

		$imgHtml = $model->getImageHtml();
		if ($imgHtml !== false)
			echo $imgHtml;

		echo CHtml::tag('div', array('class'=>'content'), $model->getContentHtmlIncludingMore());
		
		$this->renderPartial('/post/_postInfoFull',array(
			'post'=>$model,
		)); 
	?>
</div>


<div id="comments">
	<?php if($model->commentCount >= 1): ?>
		<h3>
			<?php echo $model->commentCount>1 ? $model->commentCount . ' σχόλια' : 'Ενα σχόλιο'; ?>
		</h3>

		<?php $this->renderPartial('_commentsList',array(
			'post'=>$model,
			'comments'=>$model->comments,
		)); ?>
	<?php endif; ?>

	<?php if (Yii::app()->params['allowPostingNewComments'] && $model->allow_comments > 0): // ($model->allow_comments > 0): ?>
		<h3>Προσθέστε το σχόλιό σας</h3>

		<?php if(Yii::app()->user->hasFlash('commentSubmitted')): ?>
			<div class="flash-success">
				<?php echo Yii::app()->user->getFlash('commentSubmitted'); ?>
			</div>
		<?php else: ?>
			<?php $this->renderPartial('/comment/_form',array(
				'model'=>$comment,
			)); ?>
		<?php endif; ?>
	<?php endif; ?>

</div><!-- comments -->
