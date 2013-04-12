<div class="post">
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>
	</div>
	<?php
		if ($data->subtitle != '')
			echo CHtml::tag('div', array('class'=>'subtitle'), CHtml::encode($data->subtitle)) . "\r\n";
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
	<div class="content">
		<?php
			$this->beginWidget('CMarkdown', array('purifyOutput'=>true));
			echo $data->content;
			$this->endWidget();
		?>
	</div>
	<div class="author">
		posted by <?php echo $data->author->username . ' on ' . date('F j, Y',$data->create_time); ?>
	</div>
	<div class="nav">
		<b>Tags:</b>
		<?php echo implode(', ', $data->tagLinks); ?>
		<br/>
		<?php echo CHtml::link('Permalink', $data->url); ?> |
		<?php echo CHtml::link("Comments ({$data->commentCount})",$data->url.'#comments'); ?> |
		Last updated on <?php echo date('F j, Y',$data->update_time); ?>
	</div>
</div>
