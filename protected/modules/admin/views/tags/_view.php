<div class="tag" id="c<?php echo $data->id; ?>">

	<?php
		echo CHtml::link($data->name, array('/admin/posts/', 'Post[tags]'=>$data->name));
		echo ' (' . $data->frequency . ')';
	?>
		
</div><!-- comment -->