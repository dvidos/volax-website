<?php	$widthClass = ($data->desired_width == 1) ? 'narrow' : ($data->desired_width == 3 ? 'wide' : 'medium');	$extraClass = ($data->image_filename == '') ? ' post-with-no-image' : '';?><div class="post <?php echo $widthClass; ?>">	<div class="title">		<?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>	</div>		<?php		$imgHtml = $data->getImageHtml();		if ($imgHtml !== false)			echo Chtml::tag('div', array('class'=>'image'), $imgHtml);		$content = $data->getContentHtmlUptoMore();		$content = $this->widget('application.components.ContentProcessor', array('content'=>$content), true);		echo CHtml::tag('div', array('class'=>'content'), $content);				$this->renderPartial('/post/_postInfoMinimal',array(			'post'=>$data,		)); 	?></div>