<?php
$this->pageTitle='Αναζήτηση';
$this->breadcrumbs=array(
	'Αναζήτηση',
);
?>

<h1>Αναζήτηση</h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array('action'=>$this->createUrl('site/search'), 'method'=>'get')); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'keyword'); ?>
		<?php echo $form->textField($model,'keyword', array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'keyword'); ?>
	</div>

	<div class="row checkbox">
		<?php echo $form->checkBox($model,'searchTitlesOnly'); ?>
		<?php echo $form->labelEx($model,'searchTitlesOnly'); ?>
		<?php echo $form->error($model,'searchTitlesOnly'); ?>
	</div>

	<div class="row submit">
		<?php echo CHtml::submitButton('Αναζήτηση'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->




<div class="search-results">
<?php
	if (!empty($model->posts_results))
	{
		echo CHtml::tag('h2', array(), count($model->posts_results) . ' αναρτήσεις');
		foreach ($model->posts_results as $post)
			$this->renderPartial('/post/_searchResult', array('data'=>$post));
	}
	
	if (!empty($model->categories_results))
	{
		echo CHtml::tag('h2', array(), count($model->categories_results) . ' κατηγορίες');
		foreach ($model->categories_results as $category)
			$this->renderPartial('/category/_searchResult', array('data'=>$category));
	}
	
	if (!empty($model->tags_results))
	{
		echo CHtml::tag('h2', array(), count($model->tags_results) . ' tags');
		echo '<p>';
		foreach ($model->tags_results as $tag)
		{
			$url = Yii::app()->createUrl('post/list', array('tag'=>$tag->name));
			echo CHtml::link(CHtml::encode($tag->name), $url, array('class'=>'title')) . '<br>';
		}
		echo '</p>';
	}
	
	if (empty($model->posts_results) && empty($model->categories_results) && empty($model->tags_results))
	{
		echo '<p>Δεν βρέθηκαν αποτελέσματα αναζήτησης για <i>'.CHtml::encode($model->keyword).'</i></p>';
	}
?>
</div>

