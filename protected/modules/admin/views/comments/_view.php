<?php
$deleteJS = <<<DEL
$('.container').on('click','.time a.delete',function() {
	var th=$(this),
		container=th.closest('div.comment'),
		id=container.attr('id').slice(1);
	if(confirm('Διαγραφή του σχολίου #'+id+'?')) {
		$.ajax({
			url:th.attr('href'),
			type:'POST'
		}).done(function(){container.slideUp()});
	}
	return false;
});
DEL;
Yii::app()->getClientScript()->registerScript('delete', $deleteJS);
?>
<div class="comment" id="c<?php echo $data->id; ?>">

	<?php echo CHtml::link("#{$data->id}", $data->url, array(
		'class'=>'cid',
		'title'=>'Permalink',
	)); ?>

	<div class="author">
		Από τον <?php echo $data->authorLink; ?> 
		στο <?php echo CHtml::link(CHtml::encode($data->post->title), $data->post->url); ?>
	</div>

	<div class="time">
		<?php if($data->status==Comment::STATUS_PENDING): ?>
			<span class="pending">Εκκρεμές</span> |
			<?php echo CHtml::linkButton('Εγκριση', array(
				'submit'=>array('comments/approve','id'=>$data->id),
			)); ?> |
		<?php endif; ?>
		<?php echo CHtml::link('Διόρθωση',array('comments/update','id'=>$data->id)); ?> |
		<?php echo CHtml::link('Διαγραφή',array('comments/delete','id'=>$data->id),array('class'=>'delete')); ?> |
		<?php echo date('d/m/Y, H:i',$data->create_time); ?>
	</div>

	<div class="content">
		<?php echo nl2br(CHtml::encode($data->content)); ?>
	</div>

</div><!-- comment -->