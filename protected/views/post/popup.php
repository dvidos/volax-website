<?php
$this->pageTitle=$model->title;
$this->layout = 'popup';
?>

<h1><?php echo CHtml::encode($model->title); ?></h1>

<?php echo $model->content; ?>

