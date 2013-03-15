
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_indexEntry',
	'template'=>"{items}\n{pager}",
)); ?>
