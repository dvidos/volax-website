<?php
	$this->pageTitle=$model->title;
?>

<?php
	// a category has a similar appearance as a post, ie. it has a masthead, title, image, content
	// but it also may have sub-categories and posts.
?>
<div class="post">

	<div class="title">
		<?php echo CHtml::link(CHtml::encode($model->title), $model->url); ?>
	</div>
	
	<div class="content">
		<?php 
			$content = $model->getContentHtml(); 
			$content = Yii::app()->contentProcessor->process($content);
			echo $content;
		?>
	</div>
	
</div>



<div class="expanded-post-list">
	<?php
		$dataProvider = new CActiveDataProvider('Post', array(
			'criteria'=>array(
				'condition'=>'category_id = :cid AND (status=' . Post::STATUS_PUBLISHED . ')',
				'params'=>array(':cid'=>$model->id),
			),
			'sort'=>array(
				'defaultOrder'=>'sticky DESC, create_time DESC',
			),
		));
			
		// itemView  should be dependent on the Layout...
		$this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'/post/_listEntryExpanded',
			'emptyText'=>'Φαίνεται πως δεν υπάρχουν ακόμα αναρτήσεις εδώ...',
			'template'=>"{items}\r\n\r\n{pager}",
			'pager'=>Yii::app()->params['defaultPagerParams'],
			'ajaxUpdate'=>false, // to disable ajax update
		));
	?>
</div>

