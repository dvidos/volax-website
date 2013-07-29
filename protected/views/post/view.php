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
		// couldn't we use image2 for this?
		if ($model->image_filename != '')
		{
			echo '<div class="image">';
			$fn = $model->image_filename;
			$w = 500;
			
			// must find a way to automate this...
			if (substr($fn, 0, 8) == '/volax4/')
				$fn = substr($fn, 8);
			if (substr($fn, 0, 4) == '/v4/')
				$fn = substr($fn, 4);
				
			// width will be dependent on post layout...
			$url = Yii::app()->createUrl('/images/show', array(
				'src'=>$fn,
				'width'=>$w,
			));
			echo CHTml::image($url);
			// echo CHTml::image($model->image_filename);
			echo '</div>';
		}
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
