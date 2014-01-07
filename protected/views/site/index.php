<?php $this->pageTitle = 'Αρχική'; ?>
<?php if(!empty($_GET['tag'])): ?>
	<h1>Αναρτήσεις με το tag <i><?php echo CHtml::encode($_GET['tag']); ?></i></h1>
<?php endif; ?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'/post/_layoutDesiredWidth',
	'template'=>"{items}\n{pager}",
)); ?>
