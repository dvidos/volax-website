<?php
	$this->pageTitle = CHtml::encode($model->title); 
?>

<h1><?php echo $this->pageTitle; ?></h1>

<?php echo $this->renderPartial('_tabs', array('model'=>$model)); ?>
<div class="tabs-page">
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>