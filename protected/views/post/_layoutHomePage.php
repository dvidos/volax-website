<?php	// width based on desired width. to fit in a 900px space.	$w = 0;	$m = 0;	if ($data->desired_width == 1) {		$w = 240;		$m = 60;	} 	else if ($data->desired_width == 3) {		$w = 720 + 60 + 60;		$m = 60;	}	else {		$w = 480 + 60;		$m = 60;	}?><?php	$extraClass = '';	if ($data->image_filename == '')		$extraClass .= ' post-with-no-image';			echo '<div class="post' . $extraClass . '" style="width:' . $w . 'px; margin-right:' . $m . 'px;float:left;">' . "\r\n";?>	<?php		if ($data->masthead != '')		{			echo '<div class="masthead">';			echo CHtml::encode($data->masthead);			echo '</div>' . "\r\n";		}	?>	<div class="title">		<?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>	</div>		<?php		$imgHtml = $data->getImageHtml($w);		if ($imgHtml !== false)			echo Chtml::tag('div', array('class'=>'image'), $imgHtml);	?>		<?php		if ($data->prologue != '')		{			echo '<div class="prologue">';			echo CHtml::encode($data->prologue);			echo '</div>' . "\r\n";		}	?>		<div class="content">		<?php echo $data->getContentHtmlUptoMore(); ?>	</div>		<?php $this->renderPartial('/post/_postInfoMinimal',array(		'post'=>$data,	)); ?></div>