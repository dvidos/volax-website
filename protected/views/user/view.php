<?php
$this->pageTitle=$user->fullname;
?>

<?php
	// a category has a similar appearance as a post, ie. it has a masthead, title, prologue, image, content
	// but it also may have sub-categories and posts.
?>
<div class="post">

	<div class="title">
		<?php echo CHtml::encode($user->fullname); ?>
	</div>
	
	<?php
		if ($user->profile != '')
		{
			echo '<div class="content">';
			echo CHtml::encode($user->profile);
			echo '</div>' . "\r\n";
		}
	?>
	
</div>

<hr style="margin: 2em 0;" />

<div class="compact-post-list">
	<?php
		$dataProvider = new CActiveDataProvider('Post', array(
			'criteria'=>array(
				'condition'=>'author_id = :aid AND (status=' . Post::STATUS_PUBLISHED . ')',
				'params'=>array(':aid'=>$user->id),
			),
			'sort'=>array(
				'defaultOrder'=>'create_time DESC',
			),
		));
			
		$this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'/post/_listEntryCompact',
			'template'=>"{items}\r\n\r\n{pager}",
			'pager'=>Yii::app()->params['defaultPagerParams'],
			'ajaxUpdate'=>false, // to disable ajax update
		));
	?>
</div>

