<div class="tag" id="c<?php echo $data->id; ?>">

	<?php
		echo CHtml::link($data->name, array('/post/index', 'tag'=>$data->name));
		
		echo ' (' . $data->frequency . ')';
	?>
		
</div><!-- comment -->