<?php
$this->pageTitle=$model->username;
?>

<?php
	// a category has a similar appearance as a post, ie. it has a masthead, title, prologue, image, content
	// but it also may have sub-categories and posts.
?>
<div class="post">

	<div class="title">
		<?php echo CHtml::encode($model->username); ?>
	</div>
	
	<?php
		if ($model->profile != '')
		{
			echo '<div class="content">';
			echo CHtml::encode($model->profile);
			echo '</div>' . "\r\n";
		}
	?>
	
</div>


<?php
	$dataProvider = new CActiveDataProvider('Post', array(
		'criteria'=>array(
			'condition'=>'author_id = :aid AND (status=' . Post::STATUS_PUBLISHED . ' OR status=' . Post::STATUS_ARCHIVED . ')',
			'params'=>array(':aid'=>$model->id),
		),
		'sort'=>array(
			'defaultOrder'=>'create_time DESC',
		),
	));
		
	$itemViewFile = '/post/_layoutWideTitleOnly';
	$this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>$itemViewFile,
		'template'=>"{items}\n{pager}",
		'pager'=>array(
			'class'=>'CLinkPager',
			'header'=>'Σελίδα: &nbsp; ',
			'prevPageLabel'=>'Προηγούμενη',
			'nextPageLabel'=>'Επόμενη',	
		),
		'ajaxUpdate'=>false, // to disable ajax update
	));
?>


