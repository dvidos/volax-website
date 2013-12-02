<div class="post">
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>
	</div>
	<?php $this->renderPartial('/post/_postInfoMinimal',array(
		'post'=>$data,
	)); ?>
</div>
