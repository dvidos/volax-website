<?php
$this->breadcrumbs=array(
	$model->title,
);
$this->pageTitle=$model->title;
?>

<?php $this->renderPartial('_layoutWideFullText', array(
	'data'=>$model,
)); ?>


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

	<?php if ($model->allow_comments > 0): ?>
		<h3>Αφήστε ένα σχόλιο</h3>

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
