<?php if(!empty($_GET['tag'])): ?>
	<h1>Αναρτήσεις με tag <i><?php echo CHtml::encode($_GET['tag']); ?></i></h1>
<?php endif; ?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_layoutWideTitleOnly',
	'template'=>"{items}\n{pager}",
)); ?>
