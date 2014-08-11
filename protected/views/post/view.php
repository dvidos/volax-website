<?php
$this->breadcrumbs=array(
	$model->title,
);
$this->pageTitle=$model->title;
?>

<div class="post">
	<?php
		if ($model->masthead != '')
		{
			echo '<div class="masthead">';
			echo CHtml::encode($model->masthead);
			echo '</div>' . "\r\n";
		}
	?>
	
	
	<h1 class="title">
		<?php echo CHtml::link(CHtml::encode($model->title), $model->url); ?>
	</h1>
	
	
	<?php
		if ($model->prologue != '')
		{
			echo '<div class="prologue">';
			echo CHtml::encode($model->prologue);
			echo '</div>' . "\r\n";
		}
	?>
	
	
	<?php
		$imgHtml = $model->getImageHtml(500);
		if ($imgHtml !== false)
			echo Chtml::tag('div', array('class'=>'image'), $imgHtml);
	?>
	
	
	<div class="content">
		<?php echo $model->getContentHtmlIncludingMore(); ?>
	</div>
	
	
	<?php $this->renderPartial('/post/_postInfoFull',array(
		'post'=>$model,
	)); ?>
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

	<?php if (false): // ($model->allow_comments > 0): ?>
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
