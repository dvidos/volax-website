<h1><?php echo $model->title; ?></h1>

<?php echo $this->renderPartial('_tabs', array('model'=>$model)); ?>
<div class="tabs-page">
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
