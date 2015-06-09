<?php
	$this->pageTitle=$model->title;
?>

<?php
	// a category has a similar appearance as a post, ie. it has a masthead, title, prologue, image, content
	// but it also may have sub-categories and posts.
?>
<div class="post">

	<?php
		if ($model->masthead != '')
		{
			echo '<div class="masthead">';
			echo CHtml::encode($model->masthead);
			echo '</div>' . "\r\n";
		}
	?>
	
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($model->title), $model->url); ?>
	</div>
	
	
	<?php
		if ($model->prologue != '')
		{
			echo '<div class="prologue">';
			echo CHtml::encode($model->prologue);
			echo '</div>' . "\r\n";
		}
	?>
	
	
	<?php
		if ($model->image_filename != '')
		{
			echo '<div class="image">';
			$fn = $model->image_filename;
			if (substr($fn, 0, 8) == '/volax4/')
				$fn = substr($fn, 8);
			$img = $this->createUrl('/images/show', array('src'=>$fn, 'width'=>400, 'height'=>300));
			// echo $img;
			//echo CHTml::image($img);
			echo CHTml::image($model->image_filename);
			echo '</div>';
		}
	?>
	
	
	<div class="content">
		<?php echo $model->getContentHtml(); ?>
	</div>
	
</div>




<?php
	$dataProvider = new CActiveDataProvider('Post', array(
		'criteria'=>array(
			'condition'=>'category_id = :cid AND (status=' . Post::STATUS_PUBLISHED . ' OR status=' . Post::STATUS_ARCHIVED . ')',
			'params'=>array(':cid'=>$model->id),
		),
		'sort'=>array(
			'defaultOrder'=>'create_time DESC',
		),
	));
		
	// itemView  should be dependent on the Layout...
	$itemViewFile = $model->getLayoutItemViewFile();
	$this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>$itemViewFile,
		'emptyText'=>'Φαίνεται πως δεν υπάρχουν ακόμα αναρτήσεις εδώ...',
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


