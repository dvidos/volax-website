<?php
$this->pageTitle=$model->title;
?>

<div class="page">

	<h1 class="title"><?php echo CHtml::encode($model->title); ?></h1>
	<?php echo $model->content; ?>

</div>