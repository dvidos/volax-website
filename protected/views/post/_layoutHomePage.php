<?php	$widthClass = ($data->desired_width == 1) ? 'narrow' : ($data->desired_width == 3 ? 'wide' : 'medium');?><div class="post <?php echo $widthClass; ?>">		<?php		if ($data->masthead != '')			echo CHtml::tag('div', array('class'=>'masthead'), CHtml::encode($data->masthead));		echo CHtml::tag('div', array('class'=>'title'), CHtml::link(CHtml::encode($data->title), $data->url));		$imgHtml = $data->getImageHtml();		if ($imgHtml !== false)			echo Chtml::tag('div', array('class'=>'image'), $imgHtml);		if ($data->prologue != '')			echo CHtml::tag('div', array('class'=>'prologue'), CHtml::encode($data->prologue));				$content = $data->getContentHtmlUptoMore();		$content = $this->widget('application.components.ContentProcessor', array('content'=>$content), true);		echo CHtml::tag('div', array('class'=>'content'), $content);						$this->renderPartial('/post/_postInfoMinimal',array(			'post'=>$data,		)); 	?></div>