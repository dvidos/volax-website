<div class="post">
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>
	</div>
	<?php
		if ($data->prologue != '')
		{
			echo '<div class="prologue">';
			echo CHtml::encode($data->prologue);
			echo '</div>' . "\r\n";
		}
	?>
	<?php $this->renderPartial('/post/_postInfoMinimal',array(
		'post'=>$data,
	)); ?>
</div>
