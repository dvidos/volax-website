<?php
Yii::app()->clientScript->registerScript('search',
	"$('.search-button').click(function(){ $('.search-form').toggle(); return false; });".
	"$('.search-form form').submit(function(){ $.fn.yiiGridView.update('user-grid', { data: $(this).serialize() }); return false; });");
?>

<h1>Διαφημίσεις</h1>

<p><?php
	echo CHtml::link('Δημιουργία', array('create'), array('class'=>'button')); 
	echo ' ';
	echo CHtml::link('Αναζήτηση','#',array('class'=>'search-button')); 
?></p>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'advertisement-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'title',
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->title), array("update", "id"=>$data->id))',
		),
		'customer',
		array(
			'name'=>'is_active',
			'header'=>'Active',
			'value'=>'$data->is_active ? "Yes" : ""',
			'filter'=>array('1'=>'Yes','0'=>'No'),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
)); ?>
