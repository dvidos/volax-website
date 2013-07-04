<?php foreach($comments as $comment): ?>
<div class="comment" id="c<?php echo $comment->id; ?>">

	<?php echo CHtml::link("#{$comment->id}", $comment->getUrl($post), array(
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
		στις <?php echo date('d/m/Y, H:i',$comment->create_time); ?>
	</div>

</div><!-- comment -->
<?php endforeach; ?>