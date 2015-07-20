<?php
	$this->pageTitle = CHtml::encode($model->title); 
?>

<h1><?php echo $this->pageTitle; ?></h1>

<?php echo $this->renderPartial('_tabs', array('model'=>$model)); ?>

<div class="tabs-page">

	<?php
		$images = $model->getContentImages();
		$links = $model->getContentLinks();
		$display_length = 80;
	?>
	<table class="bordered">
		<tr><td width="20%"><b>Μέγεθος κειμένου</b></td><td><?php echo Yii::app()->stringTools->friendlySize(strlen($model->content)); ?></td></tr>
		<tr><td width="20%"><b>Εικόνες</b></td><td><?php 
			foreach ($images as $url)
			{
				$ex = Post::checkUrlExistance($url);
				$color = ($ex == -1) ? '#777' : ($ex == 1 ? '#0c0' : '#c00');
				$caption = urldecode($url);
				$caption = (strlen($caption) > $display_length) ? '...' . substr($caption, -$display_length) : $caption;
				echo CHtml::link($caption, $url, array('target'=>'_blank', 'style'=>'color:'.$color.';font-weight:normal;text-decoration:none;')) . '<br>';
			}
		?></td></tr>
		<tr><td width="20%"><b>Links</b></td><td><?php 
			foreach ($links as $url)
			{
				$ex = Post::checkUrlExistance($url);
				$color = ($ex == -1) ? '#777' : ($ex == 1 ? '#0c0' : '#c00');
				$caption = urldecode($url);
				$caption = (strlen($caption) > $display_length) ? substr($caption, 0, $display_length) . '...' : $caption;
				echo CHtml::link($caption, $url, array('target'=>'_blank', 'style'=>'color:'.$color.';font-weight:normal;text-decoration:none;')) . '<br>';
			}
		?></td></tr>
	</table>
	<p><?php echo 'RootPath is "' . dirname(Yii::app()->basePath) . '", RootUrl is "' . Yii::app()->baseUrl . '"'; ?>
</div>



