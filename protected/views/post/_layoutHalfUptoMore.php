<?php	// width based on desired width. to fit in a 900px space.	$w = 0;	$m = 0;	$w = 390;	$m = 60;?><div class="post" style="width:<?php echo $w; ?>px; margin-right: <?php echo ($m-1); ?>px;float:left;">	<div class="title">		<?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>	</div>			<?php		$imgHtml = $data->getImageHtml($w);		if ($imgHtml !== false)			echo Chtml::tag('div', array('class'=>'image'), $imgHtml);	?>			<div class="content">		<?php echo $data->getContentHtmlUptoMore(); ?>	</div>			<?php $this->renderPartial('/post/_postInfoFull',array(		'post'=>$data,	)); ?></div>