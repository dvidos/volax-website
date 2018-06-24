<div class="tag" id="c<?php echo $data->id; ?>">

	<?php
		echo CHtml::link($data->name, array('/admin/posts/', 'Post[tags]'=>$data->name));
		
		echo ' &nbsp; ' . $data->frequency . '';
		
		//$lower = mb_strtolower($data->name, 'utf-8');
		//if ($lower != $data->name)
		//	echo ' &nbsp; (' . $lower . ')';
	?>
		
</div><!-- comment -->