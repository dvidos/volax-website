<?php	// width to fit in a 900px space.	$w = 240;	$m = 60;	$h = 150;?><div class="post" style="width:<?php echo $w; ?>px; margin-right: <?php echo ($m-1); ?>px;float:left;">	<div class="title">		<?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>	</div>		<?php		$imgHtml = $data->getImageHtml($w, $h, true);		if ($imgHtml !== false)			echo Chtml::tag('div', array('class'=>'image', 'style'=>'width:'.$w.'px; height:'.$h.'px;'), $imgHtml);	?>		<div class="content">		<!-- ?php echo $data->getContentHtmlUptoMore(); ? -->	</div>			<!-- ?php $this->renderPartial('/post/_postInfoMinimal',array(		'post'=>$data,	)); ? --></div>