<?php
Yii::app()->clientScript->registerScript('search',
	"$('.search-button').click(function(){ $('.search-form').toggle(); return false; });".
	"$('.search-form form').submit(function(){ $.fn.yiiGridView.update('user-grid', { data: $(this).serialize() }); return false; });");
?>

<h1>Αναρτήσεις</h1>

<p><?php
	echo CHtml::link('Νέα ανάρτηση', array('create'), array('class'=>'button')); 
	echo ' ';
	echo CHtml::link('Αναζήτηση','#',array('class'=>'search-button')); 
?></p>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'title',
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->title), array("update", "id"=>$data->id))',
		),
		array(
			'name'=>'category_id',
			'value'=>'$data->category == null ? "None" : $data->category->title',
			'filter'=>Category::dropDownListItems(),
		),
		array(
			'name'=>'author_id',
			'value'=>'$data->author == null ? "None" : $data->author->username',
			'filter'=>User::dropDownListItems(),
		),
		array(
			'name'=>'status',
			'value'=>'Status::item("PostStatus",$data->status)',
			'filter'=>Status::items('PostStatus'),
		),
		array(
			'name'=>'in_home_page',
			'header'=>'Αρχική',
			'value'=>'$data->in_home_page ? "Ναι" : ""',
			'filter'=>array('1'=>'Ναι','0'=>'Οχι'),
		),
		array(
			'name'=>'create_time',
			'type'=>'raw',
			'value'=>'date("d-m-y H:i", $data->create_time)',
			'filter'=>false,
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
)); ?>
