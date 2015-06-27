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

		$content = $model->getContentHtmlIncludingMore();
		$content = $this->widget('application.components.ContentProcessor', array('content'=>$content), true);
		echo CHtml::tag('div', array('class'=>'content'), $content);
		

	?>
	
	
	<div class="post-info">
		<?php
			echo 'Από τον χρήστη ' . CHtml::link(CHtml::encode($model->author->fullname), array('/user/view', 'id'=>$model->author_id));
			echo ', ';
			echo $model->getFriendlyCreateTime();
			
			if ($model->category != null) {
				echo '<br />';
				echo 'Στήλη: <b>' . CHtml::link($model->category->title, $model->category->getUrl()) . '</b>';
			}

			if (count($model->tagLinks) > 0) {
				echo '<br />';
				echo 'Tags: <b>' . implode(', ', $model->tagLinks) . '</b>';
			}
				
			if ($model->commentCount > 0) {
				$caption = $model->commentCount == 1 ? 'σχόλιο' : 'σχόλια';
				echo ', ' . CHtml::link($model->commentCount . ' ' . $caption, $model->url.'#comments');
			}
		?>
	</div>
</div>


<div id="comments">
	<?php if($model->commentCount >= 1): ?>
		<h3>
			<?php echo $model->commentCount>1 ? $model->commentCount . ' σχόλια' : 'Ενα σχόλιο'; ?>
		</h3>

		<?php foreach($model->comments as $comment): ?>
		<div class="comment" id="c<?php echo $comment->id; ?>">

			<?php echo CHtml::link("#{$comment->id}", $comment->getUrl($model), array(
				'class'=>'cid',
				'title'=>'Permalink',
			)); ?>
			
			<div class="author">
				<?php echo $comment->authorLink; ?>
			</div>

			<div class="content">
				<?php echo nl2br(CHtml::encode($comment->content)); ?>
			</div>

			<div class="time">
				<?php echo $comment->getFriendlyCreateTime(); ?>
			</div>

		</div><!-- comment -->
		<?php endforeach; ?>

	<?php endif; ?>

	
	<?php if(Yii::app()->user->hasFlash('commentSubmitted')): ?>
		<div class="flash-success">
			<?php echo Yii::app()->user->getFlash('commentSubmitted'); ?>
		</div>
	<?php endif; ?>
	
	
	<?php 
		if (Yii::app()->params['allowPostingNewComments'] && $model->allow_comments > 0)
		{
			// echo CHtml::tag('p', array('style'=>'margin-bottom: 0;'), 
				// CHtml::link('Προσθέστε το σχόλιό σας...', '#', array(
					// 'class'=>'gray', 
					// 'onClick'=>'$("#add-comment-form").slideToggle(); return false;',
				// ))
			// );
			echo CHtml::button('Προσθήκη σχολίου', array(
				'style'=>'display: block; margin: 1em 0 0 0;',
				'onClick'=>'$("#add-comment-form").slideToggle(); return false;',
			));
			
			// we shall display the form if any errors are present.
			$form_display_style = ($comment->errors == null || empty($comment->errors)) ? 'none' : 'block';
			$add_comment_form = $this->renderPartial('/comment/_form',array(
				'model'=>$comment,
			), true);
			
			echo CHtml::tag('div', array(
				'id'=>'add-comment-form',
				'style'=>'margin-top: 0; display: ' .$form_display_style . ';',
			), $add_comment_form);
		}				
	?>

</div><!-- comments -->


