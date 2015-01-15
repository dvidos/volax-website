<div class="post wide">
	<?php 
		echo CHtml::tag('div', array('class'=>'title'), CHtml::link(CHtml::encode($data->title), $data->url));

		if ($data->prologue != '')
			echo CHtml::tag('div', array('class'=>'prologue'), CHtml::encode($data->prologue));

		$this->renderPartial('/post/_postInfoMinimal',array(
			'post'=>$data,
		)); 
	?>
</div>
