<?php $this->beginContent('/layouts/main'); ?>

	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->


<div style="float:left;width:74%;">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<div style="float:right;width:24%;">
	<div id="sidebar">
		<?php $this->widget('TagCloud', array(
			'maxTags'=>Yii::app()->params['tagCloudCount'],
		)); ?>

		<?php $this->widget('RecentComments', array(
			'maxComments'=>Yii::app()->params['recentCommentCount'],
		)); ?>
	</div><!-- sidebar -->
</div>
<div style="clear:both;"></div>


<?php $this->endContent(); ?>