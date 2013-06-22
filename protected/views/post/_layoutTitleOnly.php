<div class="post">
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>
	</div>
	<?php
		if ($data->prologue != '')
			echo CHtml::tag('div', array('class'=>'prologue'), CHtml::encode($data->prologue)) . "\r\n";
	?>
	<?php
		if ($data->image_filename != '')
		{
			echo '<div class="image">';
			$fn = $data->image_filename;
			if (substr($fn, 0, 8) == '/volax4/')
				$fn = substr($fn, 8);
			$img = $this->createUrl('/images/show', array('src'=>$fn, 'width'=>400, 'height'=>300));
			// echo $img;
			//echo CHTml::image($img);
			echo CHTml::image($data->image_filename);
			echo '</div>';
		}
	?>
	<div class="author">
		Από τον χρήστη <?php echo $data->author->username . ' στις ' . date('d/m/Y',$data->create_time); ?>
	</div>
	<div class="nav">
		<b>Tags:</b> <?php echo implode(', ', $data->tagLinks); ?><br/>
		<?php echo CHtml::link('Permalink', $data->url); ?> |
		<?php echo CHtml::link("{$data->commentCount} σχόλια", $data->url.'#comments'); ?> |
		Τελ. ενημέρωση <?php echo date('d/m/Y',$data->update_time); ?>
	</div>
</div>